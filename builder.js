const fs = require('fs-extra');
const path = require('path');
const postcss = require('postcss');
const cssnested = require('postcss-nested');
const cssCustomMedia = require('postcss-custom-media');
const postcssGlobalData = require('@csstools/postcss-global-data');
const atImport = require("postcss-import")
const parser = require('postcss-comment')
const autoprefixer = require('autoprefixer');
const uglifycss = require('uglifycss');
const babel = require('@babel/core');
const watch = require('node-watch');
const isProd = process.argv[2] == '--prod' ? true : false;
require('dotenv').config({ path: '.docker/.env' })
const { optimize } = require('svgo');

const src = 'assets/';
const dist = `web/wp-content/themes/${process.env.WP_THEME_NAME}/`;

let date = new Date();
let version = `${date.getMonth()}${date.getDay()}${date.getHours()}${date.getMinutes()}${date.getSeconds()}`;
let hasError = false;
const core = {
    initTime: new Date(),
    compile(file, dist_name, ext) {
        if (ext == '.js') {
            try {
                this.babel(fs.readFileSync(file, 'utf8'), dist_name);
            } catch (error) {
                hasError = true;
                console.log(error);
            }
        }
        else if (ext == '.css') {
            const str = fs.readFileSync(file, 'utf8')
            this.postcss(str, css => {
                fs.ensureDirSync(path.dirname(dist_name));
                fs.writeFileSync(dist_name, css);
            }, 'print.css');
        }
        else if (ext == '.svg') {
            if (isProd) {
                const svgString = fs.readFileSync(file, 'utf8');
                const result = optimize(svgString, {
                    path: dist_name,
                    multipass: true,
                    plugins: ["removeUselessDefs"]
                });
                const optimizedSvgString = result.data;
                fs.ensureDirSync(path.dirname(dist_name));
                fs.writeFileSync(dist_name, optimizedSvgString);
            } else {
                fs.copySync(file, dist_name);
            }
        }
        else fs.copySync(file, dist_name);
    },
    compile_syles(update = false) {
        const str = fs.readFileSync(`${src}css/app.css`, 'utf8')
        core.postcss(str, (css, map) => {
            fs.writeFileSync(`${dist}assets/styles.css`, css, () => true);
            fs.writeFile(`${dist}assets/styles.css.map`, map.toString(), () => true)
            update && core.console('styles.css', 'update');
        }, 'styles.css');
    },
    dirScan(dir) {
        const recursive = dir => {
            fs.readdirSync(dir).forEach(res => {
                const file = path.resolve(dir, res);
                const stat = fs.statSync(file);
                if (stat && stat.isDirectory()) recursive(file);
                else if (!/.DS_Store$/.test(file)) {
                    if (/\/css\//.test(file)) {
                    } else {
                        const name = file.replace(`${__dirname}/`, '');
                        const filename = path.parse(name).base;
                        const ext = path.extname(filename);
                        core.compile(file, dist + name, ext);
                    }
                }
            });
        }
        recursive(dir);
    },
    rmDir(dirPath, removeSelf) {
        if (removeSelf === undefined) removeSelf = true;
        try { var files = fs.readdirSync(dirPath); }
        catch (e) { return; }
        for (let file of files) {
            const filePath = `${dirPath}/${file}`;
            fs.statSync(filePath).isFile() ? fs.unlinkSync(filePath) : core.rmDir(filePath);
        }
        removeSelf && fs.rmdirSync(dirPath);
    },
    time: () => time = (new Date() - core.initTime) / 1000,
    babel(result, dest) {
        //set version to import file
        //let res = isProd ? result.replace(/(import[ {}'".\/a-z_,]+)(.js)/igm, `$1.js?v=${version}`) : result;

        let res = result.replace(/(import[ {}'".\/a-z_,]+)(.js)/igm, `$1.js?v=${version}`);

        result = babel.transform(res, {
            minified: isProd ? true : false,
            comments: false,
            //compact: true,
            //presets: isProd ? [["minify", { "builtIns": 'entry' }]] : []
        }).code;
        fs.ensureDirSync(path.dirname(dest));
        fs.writeFileSync(dest, result);
    },
    postcss(str, func, name) {
        postcss([cssnested,
            postcssGlobalData({
                files: [`${src}css/styles/customMedias.css`]
            }),
            cssCustomMedia(),
            autoprefixer({ add: true })])
            .use(atImport({
                path: ["assets/css"],
            }))
            .process(str, {parser: parser, map: { inline: false, annotation: "styles.css.map" } })
            .catch(error => {
                console.log(`\x1b[90m${error}\x1b[39m\x1b[23m`);
                console.log(error.reason, 'line:', error.line, 'col', error.column);
                core.console(name, "error");
            })
            .then(result => {
                if (result) {
                    func(isProd ? uglifycss.processString(result.css) : result.css, result.map);
                }
            })
    },
    console(filename, evt) {
        let status;
        if (evt == 'remove') status = `31mremoved`;
        if (evt == 'error') status = `31merror`;
        if (evt == 'update') status = `32mupdated`;
        if (evt == 'add') status = `36madded`;
        console.log(`\x1b[1m${filename}\x1b[22m`, `\x1b[${status}\x1b[39m`);
    }
}

core.rmDir(`${dist}${src}`);

core.dirScan(src);

core.compile_syles();

fs.writeFileSync(`${dist}${src}version.txt`, version);

if (hasError) {
    core.console('', 'error');
    return;
}

console.log(`${core.time()}s`);

if (isProd) return

watch(src, { recursive: true }, (evt, file) => {
    if (/.DS_Store$/.test(file)) return
    core.initTime = new Date();
    const isFile = file.indexOf('.') > 0 ? true : false;
    const filename = path.basename(file);
    const ext = path.extname(filename);
    const dist_file = dist + file;
    const folder = file.split('/')[1];
    const exist = fs.existsSync(dist_file) ? true : false;

    if (folder !== 'css') {
        if (!fs.existsSync(dist_file)) evt = 'add';
        if (evt == 'update' || evt == 'add') core.compile(file, dist_file, ext);
    }
    if (exist && evt == 'remove') {
        isFile ? fs.unlinkSync(dist_file) : core.rmDir(dist_file);
    }

    if (hasError) evt = 'error';

    if (folder === 'css') {

        core.dirScan(`${src}css`);
        core.compile_syles(true);

    } else if (folder === 'js') {
        core.console(filename, evt);
    } else {
        core.console(filename, evt);
    }

    hasError = false;
});

console.log(`I'm Watching you...`);
const fs = require('fs-extra');
const path = require('path');
const postcss = require('postcss');
const cssnested = require('postcss-nested');
const cssCustomMedia = require('postcss-custom-media');
const postcssGlobalData = require('@csstools/postcss-global-data');
const autoprefixer = require('autoprefixer');
const uglifycss = require('uglifycss');
const babel = require('@babel/core');
const watch = require('node-watch');
const isProd = process.argv[2] == '--prod' ? true : false;
require('dotenv').config({ path: '.docker/.env' })

const { optimize } = require('svgo');

const src = 'assets/';
const dist = `web/wp-content/themes/${process.env.WP_THEME_NAME}/`;


var VLQ = function() {
	var encoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
	var reverseEncoding = [];
	for (var i = encoding.length; i >= 0; --i) {
		reverseEncoding[encoding.charCodeAt(i)] = i;
	}

	// Converts a value 0 to 63 into the equivalent base64 character.
	// Returns the empty string if digit is not in the
	// right range.
	function encodeDigit(digit) {
		return encoding.charAt(digit);
	}

	// Converts the character code of a base64 digit
	// into its value.
	// Returns undefined if given an invalid character code.
	function decodeCharCode(digit) {
		return reverseEncoding[digit];
	}

	// Converts a VLQ encoded string into an array
	// of integers.
	function decode(vlqString) {
		var length = vlqString.length, position = 0, result = [],
				digit, sign, startingPosition;

		while (position < length) {
			value = 0;
			// startingPosition is the index into the string
			// that the encoding of the current integer starts
			startingPosition = position;
			do {
				if (position >= length) {
					// we've gone off the end of the string.
					throw new Error("decode: Not a valid VLQ string: '"+vlqString+"'.");
				}
				digit = decodeCharCode(vlqString.charCodeAt(position));
				if (digit === undefined) {
					throw new Error("decode: Invalid character "+vlqString.charCodeAt(position)+" at position "+position+" in string "+vlqString+" - not a base64 character.");
				}

				// set the next five bits of value
				value = value | ((digit & 31) << (position - startingPosition) * 5);

				++position;
			} while (digit & 32);

			// The rightmost bit represents the sign (+ve/-ve).
			sign = value & 1;
			value = value >> 1;

			result.push(sign ? -value : value);
		}
		return result;
	}

	// Encodes a number into a VLQ string.
	function encodeValue(value) {
		var result = "", sign, current;

		if (value === 0) {
			// have to do something special to cope with -0
			sign = (1 / value) === -Infinity ? 1 : 0;
		} else {
			sign = value < 0 ? 1 : 0;
		}

		// negative numbers have their rightmost bit set.
		value = (Math.abs(value) << 1) | sign;

		// while there are bits remaining to encode:
		do {
			// get the lowest five bits remaining
			current = value & 31;
			// shift the bits remaining right by 5.
			value = value >> 5;
			// if there are more bits to write, set the 6th bit.
			if (value != 0) {
				current = current | 32;
			}
			// encode the current set of bits into a base64
			// character and append it to the result so far.
			result += encodeDigit(current);
		} while (value != 0);

		return result;
	}

	// Encodes an array of integers into a VLQ string.
	function encode(array) {
		var result = [];
		for (var i = array.length - 1; i >= 0; --i) {
			result[i] = encodeValue(array[i]);
		}
		return result.join("");
	}

	function DeltaCodec() {
		this.mostRecentEncodeValue = [];
		this.mostRecentDecodeValue = [];
	}
	DeltaCodec.prototype.encode = function(array) {
		var last = array.length - 1,
				toEncode = array.slice();
		for (var i = last; i >= 0; --i) {
			toEncode[i] = toEncode[i] - (this.mostRecentEncodeValue[i] || 0);
			this.mostRecentEncodeValue[i] = array[i];
		}
		return encode(toEncode);
	};
	DeltaCodec.prototype.decode = function(vlqString) {
		var data = decode(vlqString);
		for (var i = data.length - 1; i >= 0; --i) {
			data[i] = (this.mostRecentDecodeValue[i] || 0) + data[i];
			this.mostRecentDecodeValue[i] = data[i];
		}
		return data;
	};
	DeltaCodec.prototype.reset = function() {
		this.mostRecentDecodeValue = [];
		this.mostRecentEncodeValue = [];
	};
	DeltaCodec.prototype.resetColumn = function(columnNumber) {
		this.mostRecentDecodeValue[columnNumber] = undefined;
		this.mostRecentEncodeValue[columnNumber] = undefined;
	};

	return {
		decode: decode,
		encode: encode,
		DeltaCodec: DeltaCodec
	};
};



/* -*- Mode: js; js-indent-level: 2; -*- */
/*
 * Copyright 2011 Mozilla Foundation and contributors
 * Licensed under the New BSD license. See LICENSE or:
 * http://opensource.org/licenses/BSD-3-Clause
 */

const intToCharMap =
  "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".split("");

/**
 * Encode an integer in the range of 0 to 63 to a single base 64 digit.
 */
 function base64(number) {
    
  if (0 <= number && number < intToCharMap.length) {
    return intToCharMap[number];
  }
  throw new TypeError("Must be between 0 and 63: " + number);
};

// A single base 64 digit can contain 6 bits of data. For the base 64 variable
// length quantities we use in the source map spec, the first bit is the sign,
// the next four bits are the actual value, and the 6th bit is the
// continuation bit. The continuation bit tells us whether there are more
// digits in this value following this digit.
//
//   Continuation
//   |    Sign
//   |    |
//   V    V
//   101011

const VLQ_BASE_SHIFT = 5;

// binary: 100000
const VLQ_BASE = 1 << VLQ_BASE_SHIFT;

// binary: 011111
const VLQ_BASE_MASK = VLQ_BASE - 1;

// binary: 100000
const VLQ_CONTINUATION_BIT = VLQ_BASE;

/**
 * Converts from a two-complement value to a value where the sign bit is
 * placed in the least significant bit.  For example, as decimals:
 *   1 becomes 2 (10 binary), -1 becomes 3 (11 binary)
 *   2 becomes 4 (100 binary), -2 becomes 5 (101 binary)
 */
function toVLQSigned(aValue) {
  return aValue < 0 ? (-aValue << 1) + 1 : (aValue << 1) + 0;
}

/**
 * Returns the base 64 VLQ encoded value.
 */

function base64VLQ(aValue){



  let encoded = "";
  let digit;

  let vlq = toVLQSigned(aValue);

  do {console.log("--");
    digit = vlq & VLQ_BASE_MASK;
    vlq >>>= VLQ_BASE_SHIFT;
    if (vlq > 0) {
      // There are still more digits in this value, so we must make sure the
      // continuation bit is marked.
      digit |= VLQ_CONTINUATION_BIT;
    }
    encoded += base64(digit);
  } while (vlq > 0);

  return encoded;

}















let styles = [];
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
            /* const str = fs.readFileSync(file, 'utf8')
             this.postcss(str, css => {
                 fs.ensureDirSync(path.dirname(dist_name));
                 fs.writeFileSync(dist_name, css);
             }, 'print.css');*/
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
        let str = '';
        let str1 = '';

        let preeeev = {};

        const sourcemap = {
            version: 3,
            file: "./styles.css",
            mappings: [],
            sources: [],
            sourcesContent: [],
            names: [],
            sourceRoot: ""
        };
        let inc = 0;

        // for (let file of styles) {
        for (let i = 0; i < styles.length; i++) {

            const file = styles[i];

             // str += fs.readFileSync(file, 'utf8');


            
            core.postcss(fs.readFileSync(file, 'utf8'), (css, map, prev) => {
                // console.log("map", map);
                // fs.writeFileSync(`${dist}assets/styles.css`, css, () => true);
                // fs.writeFile(`${dist}assets/styles-${i}.css.map`, map.toString(), () => true)
                str += css;
                str1 += map;
                //   preeeev = prev.toString();
                const obfj = map._mappings._array;
                //console.log(map._mappings._last.generatedLine);
                 sourcemap["sources"].push(file);
                 // sourcemap["sourcesContent"].push(map._sourcesContents.toString());

                   for (popo in map._sourcesContents) {
                   sourcemap["sourcesContent"].push(map._sourcesContents[popo]);
                   }
           
                for (popo of map._mappings._array) {
                    // console.log(popo);
                    const obj = {
                        generatedLine: popo.generatedLine + inc,
                        generatedColumn: popo.generatedColumn,
                        originalLine: popo.originalLine,
                        originalColumn: popo.originalColumn,
                        source: file,
                    }
                   
                    sourcemap["mappings"].push( obj);
                }

                inc = map._mappings._last.generatedLine;


                // obj.source = file;
                // console.log(obj.source); 
                //  sourcemap["mappings"].push(...obj);
                //  console.log(sourcemap["mappings"]);

                //  console.log(sourcemap["mappings"]);

                if (i == styles.length - 1) {
                    console.log("---- ---");

                     console.log(sourcemap["mappings"]);
                 // console.log(toVLQSigned(map["mappings"]));
                   //    console.log(base64VLQ("0"));






                    fs.writeFileSync(`${dist}assets/styles.css`, str, () => true);
                  //   fs.writeFile(`${dist}assets/styles.css.map`, JSON.stringify(sourcemap), () => true)
                   //   fs.writeFile(`${dist}assets/styles.css.map`, btoa(JSON.stringify(sourcemap)), () => true)
                 
                }
                //   console.log(str);
                 //fs.writeFile(`${dist}assets/styles.css.map`, map.toString(), () => true)
                // update && core.console('styles.css', 'update');
                //console.log(css);
            }, 'styles.css', preeeev);
        }
/*
         core.postcss(str, (css, map) => {
             fs.writeFileSync(`${dist}assets/styles.css`, css, () => true);
             // fs.writeFile(`${dist}assets/styles.css.map`, map, () => true)
 
              fs.writeFile(`${dist}assets/styles.css.map`, map.toString(), () => true)
             update && core.console('styles.css', 'update');
             //console.log(map);
         }, 'styles.css');*/
    },
    dirScan(dir) {
        const recursive = dir => {
            fs.readdirSync(dir).forEach(res => {
                const file = path.resolve(dir, res);
                const stat = fs.statSync(file);
                if (stat && stat.isDirectory()) recursive(file);
                else if (!/.DS_Store$/.test(file)) {
                    if (/\/css\//.test(file)) {
                        styles.push(file);
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
    postcss(str, func, name, prev) {

        postcss([cssnested,
            postcssGlobalData({
                files: [`${src}css/styles/customMedias.css`]
            }),
            cssCustomMedia(),
            autoprefixer({ add: true })])
            .process(str, { map: { inline: false , annotation: "./styles.css.map"} })
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
        styles = [];
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
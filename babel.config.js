module.exports = {
    presets: [
        [
            "@babel/preset-env",
            {
                useBuiltIns: "entry",
                corejs: "3.43.0",
                modules: false,
            },
        ],
    ], 
 /*   plugins: [
        [
            'babel-plugin-transform-rewrite-imports',
            {
                appendExtension: '.js?kk=88',
                recognizedExtensions: ['.js', '.jsx', '.mjs', '.cjs', '.json', '.css'],
                replaceExtensions: {
                    // Replacements are evaluated **in order**, stopping on the first match.
                    // That means if the following two keys were listed in reverse order,
                    // .node.js would become .node.mjs instead of .cjs
                    '.js': '.js?kk=87'
                 
                  }
            }
        ]
    ]*/
};

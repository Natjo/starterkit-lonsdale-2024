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
    /*plugins: [
        [
            'babel-plugin-transform-rewrite-imports',
            {
                appendExtension: '.js',
                recognizedExtensions: ['.js', '.jsx', '.mjs', '.cjs', '.json', '.css'],
            }
        ]
    ]*/
};

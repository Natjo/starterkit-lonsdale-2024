module.exports = {
    presets: [
        [
            "@babel/preset-env",
            {
                useBuiltIns: "entry",
                corejs: "3.36.0",
                modules: false,
            },
        ],
    ],
};

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
};

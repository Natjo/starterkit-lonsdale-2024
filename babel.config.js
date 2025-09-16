module.exports = {
    presets: [
        [
            "@babel/preset-env",
            {
                useBuiltIns: "entry",
                corejs: "3.45.1",
                modules: false,
            },
        ],
    ],
};

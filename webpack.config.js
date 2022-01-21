const path = require("path");

config = {
    entry: "./assets/main.js",
    mode: "development",
    watch: true,
    module: {
        rules: [
            {test: /\.css$/, use: ["style-loader", "css-loader"]},
            {test: /\.scss$/, use: ["style-loader", "css-loader", "sass-loader"]}
        ]
    },
    output: {
        path: path.resolve(__dirname, "public/assets"),
        filename: "app.bundle.js",
    },
};



module.exports = config;
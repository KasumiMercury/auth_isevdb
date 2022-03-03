const path = require("path")
const CompressionPlugin = require("compression-webpack-plugin")

module.exports = {
    resolve: {
        alias: {
            "@": path.resolve("resources/js"),
        },
    },
    plugins: [
        new CompressionPlugin({
            test: /\.js$|\.css$|\.html$|\.svg$/,
            compressionOptions: {
                level: 9,
            },
            algorithm: "gzip",
            threshold: 10240,
            minRatio: 0.8,
        }),
    ],
}

/*
const mix = require("laravel-mix")
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin
mix.webpackConfig({
    plugins: [new BundleAnalyzerPlugin()],
})
*/

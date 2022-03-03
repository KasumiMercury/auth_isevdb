const path = require("path")

module.exports = {
    resolve: {
        alias: {
            "@": path.resolve("resources/js"),
        },
    },
}

const mix = require("laravel-mix")
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin
mix.webpackConfig({
    plugins: [new BundleAnalyzerPlugin()],
})

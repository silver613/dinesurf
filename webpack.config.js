const path = require("path");

module.exports = {
    resolve: {
        alias: {
            "@": path.resolve("resources/js"),
        },
    },
    stats: {
        hash: true,
        version: true,
        timings: true,
        children: true,
        errors: true,
        errorDetails: true,
        warnings: true,
        chunks: true,
        modules: false,
        reasons: true,
        source: true,
        publicPath: true,
    },
    // output: {
    //     chunkFilename: "js/chunks/[name].js?id=[chunkhash]",
    // },
};

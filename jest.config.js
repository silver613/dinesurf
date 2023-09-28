module.exports = {
    preset: "ts-jest",
    testPathIgnorePatterns: ["vendor"],
    moduleFileExtensions: [
        "js",
        "json",
        // tell Jest to handle `*.vue` files
        "vue",
    ],
    transform: {
        // process `*.vue` files with `vue-jest`
        ".*\\.(vue)$": "@vue/vue3-jest",
        "^.+\\.(ts|tsx)?$": "ts-jest",
        "^.+\\.(js|jsx)$": "babel-jest",
    },
    roots: ["<rootDir>", "resources/js"],
    modulePaths: ["<rootDir>", "resources/js"],
    moduleDirectories: ["node_modules", "resources/js"],
    moduleNameMapper: {
        "^@/(.*)$": ["node_modules", "<rootDir>/resources/js/$1"],
        // "^ziggy$": "<rootDir>/vendor/tightenco/ziggy/dist/js/route.js",
    },
    testEnvironmentOptions: {
        customExportConditions: ["node", "node-addons"],
    },
};

module.exports = {
    root: true,
    env: {
        node: true,
        browser: true,
        es2021: true,
    },
    extends: ["eslint:recommended", "plugin:vue/essential", "prettier"],
    parserOptions: {
        ecmaVersion: "latest",
    },
    plugins: ["vue"],
    rules: {
        "vue/prop-name-casing": "off",
        indent: ["error", 4],
        quotes: ["error", "double"],
        semi: ["error", "never"],
    },
}

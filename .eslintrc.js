module.exports = {
    env: {
        browser: true,
        es2021: true,
        node: true
    },
    extends: ["eslint:recommended", "plugin:react/recommended", "prettier"],
    parser: "@babel/eslint-parser",
    parserOptions: {
        ecmaFeatures: {
            jsx: true
        },
        ecmaVersion: 12,
        sourceType: "module"
    },
    plugins: ["react", "jsx-a11y"],
    rules: {
        "no-console": "warn",
        "no-unused-vars": "warn",
        "no-undef": "warn",
        "react/react-in-jsx-scope": "off",
        "react/display-name": "off",
        "react/prop-types": "off"
    }
};

const {
    defineConfig,
} = require("eslint/config");

const globals = require("globals");
const js = require("@eslint/js");

const {
    FlatCompat,
} = require("@eslint/eslintrc");

const compat = new FlatCompat({
    baseDirectory: __dirname,
    recommendedConfig: js.configs.recommended,
    allConfig: js.configs.all
});

module.exports = defineConfig([{
    extends: compat.extends("eslint:recommended", "plugin:vue/vue3-recommended"),

    languageOptions: {
        ecmaVersion: 2020,
        sourceType: "module",
        parserOptions: {},

        globals: {
            ...globals.amd,
            ...globals.browser,
        },
    },

    rules: {
        indent: ["error", 2],
        quotes: ["warn", "single"],
        semi: ["warn", "never"],

        "no-unused-vars": ["error", {
            vars: "all",
            args: "after-used",
            ignoreRestSiblings: true,
        }],

        "comma-dangle": ["warn", "always-multiline"],
        "vue/multi-word-component-names": "off",
        "vue/max-attributes-per-line": "off",
        "vue/no-v-html": "off",
        "vue/require-default-prop": "off",
        "vue/singleline-html-element-content-newline": "off",

        "vue/html-self-closing": ["warn", {
            html: {
                void: "always",
                normal: "always",
                component: "always",
            },
        }],

        "vue/no-v-text-v-html-on-component": "off",
    },
}]);
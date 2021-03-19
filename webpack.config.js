const autoprefixer = require("autoprefixer");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
var OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const ESLintPlugin = require("eslint-webpack-plugin");
const path = require("path");

module.exports = (env, argv) => {
    function isDevelopment() {
        argv.mode === "development";
    }
    var config = {
        entry: {
            "js/script": "./src/script.js",
            "js/admin": "./src/admin.js",
            "editor/js/script": "./src/editor/script.js"
        },
        output: {
            path: path.resolve(__dirname, "dist"),
            filename: "[name].js",
            clean: true
        },

        optimization: {
            minimizer: [
                new TerserPlugin({
                    //zzz
                    //sourceMap: true
                }),
                new OptimizeCssAssetsPlugin({
                    cssProcessorOptions: {
                        map: {
                            inline: false,
                            annotation: true
                        }
                    }
                })
            ]
        },
        plugins: [
            new ESLintPlugin(),
            new MiniCssExtractPlugin({
                filename: ({ chunk }) => {
                    return `${chunk.name.replace("script", "style").replace("js", "css")}.css`;
                }
            }),
            new BrowserSyncPlugin({
                browser: "chrome",
                files: "./**/*.php",
                proxy: "http://localhost/"
            })
        ],
        devtool: isDevelopment() ? "cheap-module-source-map" : "source-map",
        //devtool: isDevelopment() ? "inline-source-map" : false,
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: [
                        {
                            loader: "babel-loader",
                            options: {
                                presets: [
                                    "@babel/preset-env",
                                    [
                                        "@babel/preset-react",
                                        {
                                            pragma: "wp.element.createElement",
                                            pragmaFrag: "wp.element.Fragment",
                                            development: isDevelopment()
                                        }
                                    ]
                                ]
                            }
                        }
                    ]
                },
                {
                    //test: /\.s[ac]ss$/i,
                    test: /\.(sa|sc|c)ss$/,
                    use: [
                        {
                            loader: MiniCssExtractPlugin.loader,
                            options: {
                                publicPath: "../"
                            }
                        },
                        "css-loader",
                        {
                            loader: "postcss-loader",
                            options: {
                                postcssOptions: {
                                    plugins: [autoprefixer()]
                                }
                            }
                        },
                        "sass-loader"
                    ]
                },
                {
                    test: /\.(woff|woff2|eot|ttf|otf|svg)$/i,
                    type: "asset/resource",
                    generator: {
                        filename: "fonts/[name][ext]"
                    }
                },
                {
                    test: /\.ya?ml$/,
                    use: [
                        {
                            loader: "file-loader",
                            options: {
                                name: "[name].json",
                                outputPath: "data"
                            }
                        },
                        "yaml-loader"
                    ]
                }
            ]
        },
        externals: {
            jquery: "jQuery",
            underscore: "_",
            lodash: "lodash",
            "@wordpress/block-editor": ["wp", "blockEditor"],
            "@wordpress/rich-text": ["wp", "richText"],
            "@wordpress/components": ["wp", "components"],
            "@wordpress/i18n": ["wp", "i18n"],
            "@wordpress/dom": ["wp", "dom"],
            //"@wordpress/primitives": ["wp", "primitives"],
            "@wordpress/element": ["wp", "element"],
            "@wordpress/data": ["wp", "data"],
            "@wordpress/compose": ["wp", "compose"],
            "@wordpress/dom-ready": ["wp", "domReady"]
        }
    };
    return config;
};

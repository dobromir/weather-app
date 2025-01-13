const path = require('path');

const BUILD_DIR = path.resolve(__dirname, 'public/assets');
const APP_DIR = path.resolve(__dirname, 'src');
const NODE_MODULES = path.resolve(__dirname, 'node_modules');
const BUNDLE = 'bundle-package';

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const config = {
    resolve: {
        extensions: ['.js']
    },
    output: {
        path: BUILD_DIR,
        filename: 'javascript/' + `${BUNDLE}.js`
    },
    module: {
        rules: [
            {
                test: /\.(js)$/,
                include: APP_DIR,
                use: 'babel-loader',
                exclude: NODE_MODULES
            },
            {
                test: /\.(scss|css)$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            hmr: process.env.NODE_ENV === 'development',
                        },
                    },
                    'css-loader',
                    'sass-loader',
                ]
            }
        ],
    },
    plugins: [
        new CleanWebpackPlugin(),
        new MiniCssExtractPlugin({
            filename: 'css/' + `${BUNDLE}.css`,
            chunkFilename: '[id].[hash].css',
        })
    ]
};

module.exports = config;

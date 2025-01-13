const webpack  = require('webpack');
const merge    = require('webpack-merge');
const baseConf = require('./webpack.config');

const config = merge(baseConf, {
    devtool: 'source-map',
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                // This has effect on the react lib size
                'NODE_ENV': JSON.stringify('development')
            }
        })
    ]
});

module.exports = config;

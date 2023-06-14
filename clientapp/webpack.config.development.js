const path = require("path");
const { merge } = require("webpack-merge");
const webpack = require('webpack');

const base = require("./webpack.config.base.js");

module.exports = () => {
  const output = {};

  output.path = path.resolve(__dirname, "../amd/src");
  output.filename = "app-lazy.js";
  output.publicPath = "/dist/";
  output.libraryTarget = "amd";

  return merge(base, {
    mode: "development",
    devtool: "source-map",
    output,
    module: {
      rules: [
        {
          test: /\.scss$/,
          use: [
            "vue-style-loader",
            {
              loader: "css-loader",
              options: {
                sourceMap: true,
                importLoaders: 2,
              },
            },
            {
              loader: "postcss-loader",
              options: {
                sourceMap: true,
              },
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: true,
              },
            },
          ],
        },
      ],
    },
    plugins: [
      new webpack.DefinePlugin({
        __VUE_PROD_DEVTOOLS__: true,
      }),
    ],
  });
};

const { merge } = require("webpack-merge");
const base = require("./webpack.config.base.js");
const path = require("path");

module.exports = () => {
  const output = {};

  output.path = path.resolve(__dirname, "../amd/build");
  output.filename = "app-lazy.min.js";
  output.publicPath = "/dist/";
  output.libraryTarget = "amd";

  return merge(base, {
    mode: "production",
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
                sourceMap: false,
                importLoaders: 2,
              },
            },
            {
              loader: "postcss-loader",
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: false,
              },
            },
          ],
        },
      ],
    },
  });
};

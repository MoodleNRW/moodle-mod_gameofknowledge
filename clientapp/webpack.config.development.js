const path = require("path");
const { merge } = require("webpack-merge");

const base = require("./webpack.config.base.js");

const webpackDevServerPort = 8081;

module.exports = (env) => {
  const platform = env.platform;

  return merge(base, {
    mode: "development",
    devtool: "source-map",
    devServer: {
      compress: true,
      hot: true,
      port: webpackDevServerPort,
      historyApiFallback: true,
      static: {
        directory: path.join(__dirname, "dist"),
      },
      client: {
        progress: true,
      },
      allowedHosts: "auto",
    },
    output: {
      filename: "[name].js",
      path: path.resolve(__dirname, "dist"),
      publicPath: "/",
      libraryTarget: "window",
    },
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
                importLoaders: 1,
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
    plugins: [],
  });
};

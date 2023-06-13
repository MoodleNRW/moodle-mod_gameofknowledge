const path = require("path");
const { VueLoaderPlugin } = require("vue-loader");

module.exports = {
  target: "web",
  entry: { main: "./index.js" },
  resolve: {
    extensions: [".js", ".vue"],
    alias: {
      "@": path.resolve(__dirname),
    },
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
        },
      },
      {
        test: /\.vue$/,
        use: {
          loader: "vue-loader",
        },
      },
      {
        test: /\.(png|jpg|jpeg|gif)$/i,
        type: "asset/resource",
      },
      {
        test: /\.(svg)$/i,
        type: "asset/source",
      },
    ],
  },
  plugins: [new VueLoaderPlugin()],
};

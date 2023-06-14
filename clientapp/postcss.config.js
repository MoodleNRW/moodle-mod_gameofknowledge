module.exports = {
  sourceMap: true,
  stats: { warnings: false },
  plugins: {
    "postcss-preset-env": {
      stage: 4,
    },
    "postcss-normalize": {},
    "postcss-prefix-selector": {
      prefix: ".mod-gameofknowledge",
      exclude: [":root"],
      transform(prefix, selector, prefixedSelector, filepath) {
        if (selector.match(/^(html|body)/)) {
          return selector.replace(/^([^\s]*)/, `$1 ${prefix}`);
        }

        if (filepath.match(/node_modules/)) {
          return selector; // Do not prefix styles imported from node_modules
        }

        return prefixedSelector;
      },
    },
    autoprefixer: {},
  },
};

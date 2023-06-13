const presets = [
    [
      "@babel/preset-env",
      {
        debug: false,
        useBuiltIns: "entry",
        corejs: 3,
      },
    ],
  ];
  
  module.exports = {
    presets,
  };
  
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const CopyPlugin = require('copy-webpack-plugin');

const config = {
  entry: {
    'style': './sass/style.scss', 
    'hardpreview': './sass/visitor/hardpreview.scss'
  },
  plugins: [
    new MiniCssExtractPlugin(),
    new FixStyleOnlyEntriesPlugin()
  ],
  module: {
    rules: [
      {
        test: /\.(scss|css)$/,
        use: [
          MiniCssExtractPlugin.loader, 
          {
            loader: 'css-loader', 
            options: {
              sourceMap: true,
              url: false,
            }
          }, 
          'sass-loader'
        ],
      }
    ]
  },
  devtool: 'source-map',
  devServer: {
    static: path.join(__dirname, "/")
  }
}

module.exports = (env, argv) => {
  if (argv.mode === 'production') {
    config.output = {
      path: path.resolve(__dirname, './_dist/_styles'),
    };

    config.plugins.push(
      new CopyPlugin({
        patterns: [
          { from: "*.php", to: path.resolve(__dirname, "./_dist") },
          { from: "*.txt", to: path.resolve(__dirname, "./_dist") },
          { from: "classes", to: path.resolve(__dirname, "./_dist/classes") },
          { from: "hooks", to: path.resolve(__dirname, "./_dist/hooks") },
          { from: "images", to: path.resolve(__dirname, "./_dist/images") },
          { from: "includes", to: path.resolve(__dirname, "./_dist/includes") },
          { from: "languages", to: path.resolve(__dirname, "./_dist/languages") },
          { from: "scripts", to: path.resolve(__dirname, "./_dist/scripts") }
        ],
      })
    );
  }

  if (argv.mode === 'development') {
    config.output = {
      path: path.resolve(__dirname, './_styles'),
    };
  }

  return config;
}
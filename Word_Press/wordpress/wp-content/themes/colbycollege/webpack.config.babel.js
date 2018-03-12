import ExtractTextPlugin from 'extract-text-webpack-plugin';
import path from 'path';
import CleanWebpackPlugin from 'clean-webpack-plugin';

import packageJson from './package.json';

const main = () => {
  const PROD = process.argv.includes('-p');
  const min = PROD ? '.min' : '';
  const entry = {};
  entry[packageJson.name] = ['./src/js/index.js', './assets/sass/main.scss'];
  const filename = `[name]${min}.js`;
  const plugins = [new ExtractTextPlugin(`colby-college${min}.css`)];

  if (process.argv.includes('--clean')) {
    plugins.push(new CleanWebpackPlugin(path.resolve(__dirname, 'dist')));
  }

  return {
    entry,
    output: {
      filename,
      chunkFilename: `[id].[chunkhash].bundle${min}.js?v=${packageJson.version}`,
      path: path.resolve(__dirname, 'dist'),
      publicPath: '/wp-content/themes/colbycollege/dist/',
    },
    plugins,
    module: {
      rules: [
        {
          test: /\.js$/,
          use: [
            {
              loader: 'babel-loader',
              options: {
                presets: [
                  'react',
                  [
                    'env',
                    {
                      targets: {
                        browsers: ['> 1%', 'last 5 versions'],
                      },
                      debug: true,
                    },
                  ],
                  'stage-0',
                ],
              },
            },
          ],
        },
        {
          test: /\.scss$/,
          use: ExtractTextPlugin.extract({
            fallback: 'style-loader',
            use: [
              {
                loader: 'css-loader',
              },
              { loader: 'postcss-loader' },
              { loader: 'sass-loader' },
            ],
          }),
        },
        {
          test: /\.(png|svg|jpe?g|gif|GIF)$/,
          use: ['file-loader', { loader: 'image-webpack-loader' }],
        },
        {
          test: /\.(woff|woff2|eot|ttf|otf)$/,
          use: { loader: 'file-loader' },
        },
      ],
    },
    target: 'web',
    devtool: PROD ? false : 'source-maps',
    node: {
      fs: 'empty',
    },
  };
};

export default main;

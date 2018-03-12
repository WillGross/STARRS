/* eslint-disable */
const autoprefixer = require('autoprefixer');
const customPropFallbacks = require('postcss-custom-prop-fallbacks');

module.exports = {
  plugins: [autoprefixer, customPropFallbacks],
};

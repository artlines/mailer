var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');
var FaviconsWebpackPlugin = require('favicons-webpack-plugin');
var HtmlWebpackPlugin = require('html-webpack-plugin');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  // .addEntry('app', './public/assets/js/main.js')
  .addStyleEntry('main', './public/assets/scss/main.scss')
  .autoProvidejQuery()
  .enableSassLoader(function(sassOptions) {}, {
      resolveUrlLoader: false
  })
  .addPlugin(new CopyWebpackPlugin([
    // copies to {output}/static
    { from: './public/assets/img', to: 'images' }
  ]))
  .addPlugin(new FaviconsWebpackPlugin(
      // copies to {output}/static
      {
          logo: './public/assets/img/favicon.png',
          // The prefix for all image files (might be a folder or a name)
          prefix: 'favicon-',
          // Emit all stats of the generated icons
          emitStats: false,
          // The name of the json containing all favicon information
          statsFilename: 'favicon.json',
          // Generate a cache file with control hashes and
          // don't rebuild the favicons until those hashes change
          persistentCache: true,
          // Inject the html into the html-webpack-plugin
          inject: true,
          // favicon background color (see https://github.com/haydenbleasel/favicons#usage)
          background: '#fff',
          // favicon app title (see https://github.com/haydenbleasel/favicons#usage)
          title: 'mailer-soa.nag.ru',

          // which icons should be generated (see https://github.com/haydenbleasel/favicons#usage)
          icons: {
              android: true,
              appleIcon: true,
              appleStartup: true,
              coast: false,
              favicons: true,
              firefox: true,
              opengraph: false,
              twitter: false,
              yandex: false,
              windows: true
          }
      }
  ))
  .addPlugin(new HtmlWebpackPlugin())
  .enableSourceMaps(!Encore.isProduction())
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
;

module.exports = Encore.getWebpackConfig();
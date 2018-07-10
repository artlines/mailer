var Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  // .addEntry('app', './public/assets/js/main.js')
  .addStyleEntry('main', './public/assets/scss/main.scss')
  .autoProvidejQuery()
  .enableSassLoader(function(sassOptions) {}, {
      resolveUrlLoader: false
  })
  .enableSourceMaps(!Encore.isProduction())
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
;

module.exports = Encore.getWebpackConfig();
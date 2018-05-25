var Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './assets/js/app.js')
  .addStyleEntry('main', './assets/scss/main.scss')
  .autoProvidejQuery()
  .enableSassLoader(function(sassOptions) {}, {
      resolveUrlLoader: false
  })
  .enableSourceMaps(!Encore.isProduction())
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
;

module.exports = Encore.getWebpackConfig();
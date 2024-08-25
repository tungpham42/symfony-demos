// webpack.config.js
const Encore = require("@symfony/webpack-encore");

Encore.setOutputPath("public/build/")
  .setPublicPath("/build")
  .addEntry("app", "./assets/app.js")
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableSassLoader()
  .enablePostCssLoader()
  .autoProvidejQuery()
  .enableStimulusBridge("./assets/controllers.json")
  .enableTypeScriptLoader()
  .enableReactPreset()
  .addStyleEntry("styles", "./assets/styles/app.scss") // Add this line for Bootstrap
  .enableBuildNotifications();

module.exports = Encore.getWebpackConfig();

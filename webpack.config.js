const Encore = require('@symfony/webpack-encore');
const FosRouting = require('fos-router/webpack/FosRouting');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.scss) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('parcours_index', './assets/parcours/index.js')
    .addEntry('parcours_form', './assets/parcours/form.js')
    .addEntry('parcours_show', './assets/parcours/show.js')
    .addEntry('bloc_ue_form', './assets/bloc_ue/form.js')
    .addEntry('campagne_choix_index', './assets/campagne_choix/index.js')
    .addEntry('campagne_choix_form', './assets/campagne_choix/form.js')
    .addEntry('campagne_choix_show', './assets/campagne_choix/show.js')
    .addEntry('campagne_choix_tab', './assets/campagne_choix/tab.js')
    .addEntry('passage_annee_index', './assets/passage_annee/index.js')
    .addEntry('passage_annee_form', './assets/passage_annee/form.js')
    .addEntry('passage_annee_form_step_2', './assets/passage_annee/form_step_2.js')
    .addEntry('etudiant_index', './assets/etudiant/index.js')
    .addEntry('etudiant_choix_edit', './assets/etudiant_choix/edit.js')
    .addEntry('etudiant_groupe_manuel', './assets/campagne_choix/groupe_manuel.js')
    .addEntry('groupe_form', './assets/groupe/form.js')

    .addStyleEntry('login', './assets/styles/login.css')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    // .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()
    .enableVueLoader()
    // .enableVueLoader(() => {}, { runtimeCompilerBuild: false })

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    .addPlugin(new FosRouting())
;

module.exports = Encore.getWebpackConfig();

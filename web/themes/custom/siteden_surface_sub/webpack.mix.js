const mix = require('laravel-mix');
require('laravel-mix-clean');
require('laravel-mix-ignore');
require('laravel-mix-stylelint');

mix
  .disableNotifications()
  .options({
    processCssUrls: false
  })
  .setPublicPath('assets')
  .sourceMaps(false, 'source-map')
  .webpackConfig({
    stats: {
      warnings: true,
      children: true
    },
    externals: {
      jquery: "jQuery",
      'slick-carousel': 'slick-carousel'
    }
  });

// Compile CSS.
mix
  .sass('src/scss/siteden-styles.scss', 'css')
  .sass('src/scss/siteden-ckeditor.scss', 'css')
  .sass('src/scss/siteden-layout-paragraphs.scss', 'css')
  .clean()
  .stylelint();

// Compile JavaScript.
mix
  .js([
    'src/js/add-to-calendar.js',
    'src/js/content-links.js',
    'src/js/filter-select.js',
    'src/js/header-nav.js',
    'src/js/h1-heading.js',
    'src/js/heading-ribbon.js',
    'src/js/hero-banner-popup-video.js',
    'src/js/icon-link.js',
    'src/js/layout-columns.js',
    'src/js/lazyload-images.js',
    'src/js/panel-mobile-collapse.js',
    'src/js/primary-nav.js',
    'src/js/recent-news-view-title.js',
    'src/js/search-bar.js',
    'src/js/skip-to-main.js',
    'src/js/slideshow.js'
  ], 'assets/js/siteden-scripts.js')
  .js([
    'src/js/content-links.js',
    'src/js/heading-ribbon.js',
    'src/js/icon-link.js',
    'src/js/slideshow.js'
  ], 'assets/js/siteden-layout-paragraphs.js')
  .clean();

// Minify the editoria11y customization file.
mix
  .minify('src/js/editoria11y-custom.js', 'assets/js/editoria11y-custom.js');

// Copy the icon files.
mix
  .copy('src/images', 'assets/images');


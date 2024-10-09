Drupal.behaviors.sfSlider = {
  attach: function (context, settings) {
    once('sfSlider', '.sf_slider', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: true,
        autoplay: false,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 7500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
    });
    once('sfSlider', '.story-slider-mobile', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: true,
        autoplay: false,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 7500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
    });
    if (screen.width > 768) {
      arrowTop = (jQuery(".story-slider .field--name-field-media-image").height() - 30) / 2;
      jQuery(".splide__arrow").css('top', arrowTop);
    } else {
      arrowTop = (jQuery(".story-slider-mobile .field--name-field-media-image").height() - 30) / 2;
      jQuery(".splide__arrow").css('top', arrowTop);
    }
    window.addEventListener('resize', function (event) {
      if (screen.width > 768) {
        arrowTop = (jQuery(".story-slider .field--name-field-media-image").height() - 30) / 2;
        jQuery(".splide__arrow").css('top', arrowTop);
      } else {
        arrowTop = (jQuery(".story-slider-mobile .field--name-field-media-image").height() - 30) / 2;
        jQuery(".splide__arrow").css('top', arrowTop);
      }
    }, true);
  }
}

Drupal.behaviors.sfSlider = {
  attach: function (context, settings) {
    once('sfSlider', '.sf_slider', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: true,
        autoplay: false,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 6500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
    });
    once('sfSlider', '.sf_story_slider', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: false,
        autoplay: true,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 6500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
      jQuery(".sf_story_slider").each(function(){
        splideWidth = jQuery(this).find('.splide__pagination').width() + 40;
        jQuery(this).find(".splide__toggle").css("right", splideWidth+"px");
      })
    });
    once('sfSlider', '.sf_featured_story_slider', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: false,
        autoplay: true,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 6500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
      jQuery(".sf_featured_story_slider").each(function(){
        splideWidth = jQuery(this).find('.splide__pagination').width() + 40;
        jQuery(this).find(".splide__toggle").css("right", splideWidth+"px");
      })
    });
    once('sfSlider', '.sf_events_slider', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: false,
        autoplay: true,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 6500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
      jQuery(".sf_events_slider").each(function(){
        splideWidth = jQuery(this).find('.splide__pagination').width() + 40;
        jQuery(this).find(".splide__toggle").css("right", splideWidth+"px");
      })
    });
    once('sfSlider', '.events-slider-mobile', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: false,
        autoplay: false,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 6500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
      jQuery(".events-slider-mobile").each(function(){
        splideWidth = jQuery(this).find('.splide__pagination').width() + 40;
        jQuery(this).find(".splide__toggle").css("right", splideWidth+"px");
      })
    });
    /*if (screen.width > 768){
      arrowTop = (jQuery(".events-slider .field--name-field-media-image").height() - 30) / 2;
      jQuery(".splide__arrow").css('top', arrowTop);
      jQuery(".block-slider-block").each(function(){
          total_slides = jQuery(this).attr('total_slides');
          per_slider = jQuery(this).attr('per_slider');
          if(total_slides == per_slider){
              jQuery(this).find('.splide__arrows').remove();
          }
      })
    } else {
      arrowTop = (jQuery(".events-slider-mobile .field--name-field-media-image").height() - 30) / 2;
      jQuery(".splide__arrow").css('top', arrowTop);
    }
    window.addEventListener('resize', function(event) {
      if (screen.width > 768){
        arrowTop = (jQuery(".events-slider .field--name-field-media-image").height() - 30) / 2;
        jQuery(".splide__arrow").css('top', arrowTop);
      } else {
        arrowTop = (jQuery(".events-slider-mobile .field--name-field-media-image").height() - 30) / 2;
        jQuery(".splide__arrow").css('top', arrowTop);
      }
    }, true);*/
    once('sfSlider', '.sf_photo_slider', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: false,
        autoplay: true,
        rewind: true,
        pauseOnHover: false,
        pauseOnFocus: false,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 6500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
      jQuery(".splide__toggle__pause").click(function(){
          jQuery(this).hide();
      });
      jQuery(".splide__toggle__play").click(function(){
          jQuery(".sf_photo_slider .splide__toggle__pause").show();
      });
      jQuery(".splide__toggle").keypress(function(e){
          if(e.which == 13){//Enter key pressed
            if(jQuery(this).hasClass('is-active')){
              jQuery("sf_photo_slider .splide__toggle__play").show();
              jQuery("sf_photo_slider .splide__toggle__pause").hide();

            } else {
              jQuery("sf_photo_slider .splide__toggle__play").hide();
              jQuery("sf_photo_slider .splide__toggle__pause").show();
            }
          }
      });
      jQuery(".splide__toggle__play").keypress(function(e){
          if(e.which == 13){//Enter key pressed
          }
      });
      jQuery(".sf_photo_slider").each(function(){
        splideWidth = jQuery(this).find('.splide__pagination').width() + 40;
        jQuery(this).find(".splide__toggle").css("right", splideWidth+"px");
      })
      jQuery(".sf_photo_slider").each(function(){
        var this_slider = jQuery(this);
        var words = jQuery(this).find(".field--name-field-sf-title").text().split(" ");
        let string = "";
        jQuery.each(words, function(i, v) {
            if(v && v!=""){
                string = string + "<span>"+v+"</span>";
            }
        });
        if (string != ''){
          jQuery(this).find(".photo-silder-title").empty();
          jQuery(this).find(".photo-silder-title").append(string);

        }
      })
    });
    jQuery(".sf_story_slider .splide__toggle__pause").trigger( "click" );
    jQuery(".sf_story_slider .splide__toggle__pause").hide();
    jQuery(".sf_story_slider .splide__toggle__pause").click(function(){
        jQuery(this).hide();
        jQuery(this).parent().find('.splide__toggle__play').show();
        jQuery(this).parent().addClass('.slide-paused');
    });
    jQuery(".sf_story_slider .splide__toggle__play").click(function(){
        jQuery(this).parent().find('.splide__toggle__pause').show();
        jQuery(this).parent().find('.splide__toggle__play').hide();
        jQuery(this).parent().addClass('.slide-playing');
    });

    jQuery(".sf_events_slider .splide__toggle__pause").trigger( "click" );
    jQuery(".sf_events_slider .splide__toggle__pause").hide();
    jQuery(".sf_events_slider .splide__toggle__pause").click(function(){
        jQuery(this).hide();
        jQuery(this).parent().find(".splide__toggle__play").show();
    });
    jQuery(".sf_events_slider .splide__toggle__play").click(function(){
        jQuery(this).parent().find(".splide__toggle__pause").show();
        jQuery(this).parent().find(".splide__toggle__play").hide();
    });

    jQuery(".sf_featured_story_slider .splide__toggle__pause").trigger( "click" );
    jQuery(".sf_featured_story_slider .splide__toggle__pause").hide();
    jQuery(".sf_featured_story_slider .splide__toggle__pause").click(function(){
        jQuery(this).hide();
        jQuery(this).parent().find(".splide__toggle__play").show();
    });
    jQuery(".sf_featured_story_slider .splide__toggle__play").click(function(){
        jQuery(this).parent().find(".splide__toggle__pause").show();
        jQuery(this).parent().find(".splide__toggle__play").hide();
    });
  }
}

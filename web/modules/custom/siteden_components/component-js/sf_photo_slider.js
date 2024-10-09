Drupal.behaviors.sfSlider = {
  attach: function (context, settings) {
    once('sfSlider', '.sf_photo_slider', context).forEach(function (element) {
      var splide = new Splide(element, {
        arrows: false,
        autoplay: true,
        rewind: true,
        pauseOnHover: false,
        pauseOnFocus: false,
        easing: 'cubic-bezier(0.12, 0, 0.39, 0)',
        interval: 7500,
        keyboard: true,
        pauseOnFocus: true,
        type: 'loop',
      });
      splide.mount();
      jQuery(".splide__toggle__pause").click(function(){
          jQuery(this).hide();
      });
      jQuery(".splide__toggle__play").click(function(){
          jQuery(".splide__toggle__pause").show();
      });
      jQuery(".splide__toggle").keypress(function(e){
          if(e.which == 13){//Enter key pressed
            if(jQuery(this).hasClass('is-active')){
              jQuery(".splide__toggle__play").show();
              jQuery(".splide__toggle__pause").hide();

            } else {
              jQuery(".splide__toggle__play").hide();
              jQuery(".splide__toggle__pause").show();
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
      });

      jQuery(".slider-item__title").each(function () {
        len = jQuery(this).text().length;
        str = jQuery(this).text().substr(0, 35);
        lastIndexOf = str.lastIndexOf(" ");
        if (len > 35) {
          jQuery(this).text(str.substr(0, lastIndexOf) + '...');
        }
      });

      jQuery(".sf_photo_slider").each(function(){
        var this_slider = jQuery(this);
        var words = jQuery(this).find(".field--name-field-sf-title").text().split(" ");
        let string = "";
        jQuery.each(words, function(i, v) {
          console.log(v);
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
  }
}

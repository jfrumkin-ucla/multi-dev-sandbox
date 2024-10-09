document.addEventListener('DOMContentLoaded', () => {


  function bannerTitles() {
    //let headerOne = document.querySelector("#block-siteden-surface-page-title--2");
    let h1Tag = document.querySelectorAll('h1:first-of-type')[0];
    let boxTitle = document.querySelectorAll('.banner--box__title:first-of-type')[0];
    let articleEmbedTitle = document.querySelectorAll('.node--type-sf-article .story-card__title:first-of-type')[0];
    let articleSecondaryTitle = document.querySelectorAll('.node--type-sf-article .story-card-secondary__title:first-of-type')[0];
    let storyTitle = document.querySelectorAll('.is-active.is-visible .story-card__title:first-of-type')[0];
    let articleBannerTitle = document.querySelectorAll('.node--type-sf-article .story-card-banner__title:first-of-type')[0];
    let featuredStoryTitle = document.querySelectorAll('.is-active.is-visible .story-card-banner__title:first-of-type')[0];
    let sliderTitle = document.querySelectorAll('.is-visible .slider-item__title:first-of-type')[0];
    let photoSliderTitle = document.querySelectorAll('.is-active .photo-silder-title:first-of-type')[0];
    let textBanner = document.querySelectorAll('.banner--text__body:first-of-type h3')[0];
    let pos = 1000000000000;
    if (h1Tag) {
      return;
    }
    if (boxTitle) {
      let first = jQuery(".banner--box__title:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = boxTitle;
        change_inner = 0;
      }
    }
    if (articleEmbedTitle) {
      let first = jQuery(".node--type-sf-article .story-card__title:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = articleEmbedTitle;
        change_inner = 0;
      }
    }
    if (articleSecondaryTitle) {
      let first = jQuery(".node--type-sf-article .story-card-secondary__title").offset().top;
      if (first < pos) {
        pos = first;
        changeel = articleSecondaryTitle;
        change_inner = 0;
      }
    }
    if (storyTitle) {
      let first = jQuery(".is-active.is-visible .story-card__title:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = storyTitle;
        change_inner = 0;
      }
    }
    if (articleBannerTitle) {
      let first = jQuery(".node--type-sf-article .story-card-banner__title:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = articleBannerTitle;
        change_inner = 0;
      }
    }
    if (featuredStoryTitle) {
      let first = jQuery(".is-active.is-visible .story-card-banner__title:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = featuredStoryTitle;
        change_inner = 0;
      }
    }
    if (sliderTitle) {
      let first = jQuery(".is-visible .slider-item__title:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = sliderTitle;
        change_inner = 0;
      }
    }
    if (photoSliderTitle) {
      let first = jQuery(".is-active .photo-silder-title:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = photoSliderTitle;
        change_inner = 0;
      }
    }
    if (textBanner) {
      let first = jQuery(".banner--text__body:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = textBanner;
        change_inner = 1;
      }
    }
    if (changeel) {
      toH1(changeel, change_inner);
    }
    function toH1(el, change_inner) {
      if (change_inner == 0) {
        let holder = document.createElement('h1');
        holder.innerHTML = el.innerHTML;
        holder.className = el.className;
        el.parentNode.replaceChild(holder, el);
      } else {
        el.outerHTML = '<h1>' + el.innerHTML + '</h1>';
      }
    }
  }

  bannerTitles();

});

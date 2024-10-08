document.addEventListener('DOMContentLoaded', () => {


  function bannerTitles() {
    //let headerOne = document.querySelector("#block-siteden-surface-page-title--2");
    let headerOneTitle = document.querySelectorAll('.page-title:first-of-type')[0];
    let ribbonTitle = document.querySelectorAll('.banner--ribbon__title:first-of-type')[0];
    let boxTitle = document.querySelectorAll('.banner--box__title:first-of-type')[0];
    let articleTitle = document.querySelectorAll('.story-card-banner__title:first-of-type')[0];
    let sliderTitle = document.querySelectorAll('.is-visible .slider-item__title:first-of-type')[0];
    let textBanner = document.querySelectorAll('.banner--text__body:first-of-type h3')[0];
    let pos = 1000000000000;
    if (headerOneTitle) {
      return;
    }
    if (ribbonTitle) {
      let first = jQuery(".banner--ribbon__title:first-of-type").offset().top;
      if(first < pos){
        pos = first;
        changeel = ribbonTitle;
        change_inner = 0;
      }
    }
    if (boxTitle) {
      let first = jQuery(".banner--box__title:first-of-type").offset().top;
      if(first < pos){
        pos = first;
        changeel = boxTitle;
        change_inner = 0;
      }
    }
    if (articleTitle) {
      let first = jQuery(".story-card-banner__title:first-of-type").offset().top;
      if(first < pos){
        pos = first;
        changeel = articleTitle;
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
    if (textBanner) {
      let first = jQuery(".banner--text__body:first-of-type").offset().top;
      if (first < pos) {
        pos = first;
        changeel = textBanner;
        change_inner = 1;
      }
    }
    if(changeel){
        toH1(changeel,change_inner);
    }
    function toH1(el,change_inner) {
      if(change_inner==0){
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

// Add classes to images being lazy loaded in.
(() => {

  // Exit if native lazy loading is not supported.
  if (!('loading' in HTMLImageElement.prototype)) {
    return;
  }

  const images = document.querySelectorAll('img[loading="lazy"]');

  images.forEach(img => {
    // Only apply classes to images not already loaded.
    if (!img.complete) {
      img.classList.add('lazyload');
      img.addEventListener('load', event => {
        event.target.classList.add('lazyload--loaded');
      });
    }
  });

})();

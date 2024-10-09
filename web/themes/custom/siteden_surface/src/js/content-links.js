Drupal.behaviors.contentLinks = {
  attach: function (context, settings) {
    once('contentLinks', 'a', context).forEach(function (element) {
      if (element.href.indexOf('mailto:') === 0) {
        element.innerHTML += '<span class="visually-hidden">(link sends email)</span>';
      }
      else if (element.hostname && element.hostname.replace('www.', '') !== location.hostname.replace('www.', '')) {
        element.innerHTML += '<i class="fas fa-external-link-alt" aria-hidden="true"></i><span class="screen-reader-text"> opens a new window</span>';
        element.setAttribute('target', '_blank');
      }
    });

    once('contentExternalLinks', '.text-formatted a, .hero-banner__button-group a, .banner--box__button-group a, .banner--text__button-group a, a.card__button, a.focal-link, .give-now a, a.slider-item__button', context).forEach(function (element) {
      // Add a class to external links.
      if (element.getAttribute('target') === '_blank') {

        // If this is a focal link, add the external class to the focal-link text.
        if (element.classList.contains('focal-link')) {
          element.querySelectorAll('.focal-link__body .focal-link__text').forEach(function (childElement) {
            childElement.classList.add('link--external');
          });
          return;
        }

        // Skip these classes.
        if (element.classList.contains('media-link') || element.classList.contains('icon-link--internal')) {
          return;
        }

        // Skip these HTML elements.
        const skipElements = ['article', 'img', 'video', 'div', 'figure'];
        if (element.firstElementChild && skipElements.includes(element.firstElementChild.tagName.toLowerCase())) {
          return;
        }

        // Add the external link class to anything else.
        element.classList.add('link--external');
      }
    });
  }
}

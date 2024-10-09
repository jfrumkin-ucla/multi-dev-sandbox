Drupal.behaviors.headingRibbon = {
  attach: function (context, settings) {
    once('headingRibbon', '.heading--ribbon-branding, .heading--ribbon-highlight', context).forEach(function (element) {
      let headingHTML = element.innerHTML;
      // Remove any existing spans.
      headingHTML = headingHTML.replace(/<\/?span[^>]*>/g, "");
      // Wrap the text with a single span.
      headingHTML = '<span>' + headingHTML + '</span>';
      // Replace the heading with the new HTML.
      element.innerHTML = headingHTML;
    });
  }
}

Drupal.behaviors.iconLink = {
  attach: function (context, settings) {
    once('iconLink', '.list--link-icon a, .field--name-field-dgc-publications a', context).forEach(function (element) {
      const link = element.href;
      const linkParts = link.split('.');
      const linkExt = linkParts[linkParts.length - 1];
      // const webpageExts = ['html', 'htm', 'jhtml', 'jsp', 'asp', 'aspx', 'php', 'cfm', 'rhtml', 'shtml', 'vbhtml', 'xhtml'];
      const downloadExts = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'odt', 'ods', 'txt'];

      // Check if this is an internal or external link.
      if (location.hostname === element.hostname || !element.hostname.length) {
        // Check if this is a link to a document or internal web page extension.
        if (link.lastIndexOf('.') !== -1 && downloadExts.includes(linkExt))  {
          // If this is a link to a document append the extension to the link name and add the icon class.
          element.append(' (' + linkExt + ')');
          element.classList.add('icon-link', 'icon-link--download');
        }
        // If this is not a document, then it is an internal link and add the internal link icon.
        else {
          element.classList.add('icon-link', 'icon-link--internal');
        }
      }
      else {
        // If this is an external link add class for external icon and set the link to open in a new window.
        element.classList.add('icon-link', 'icon-link--external');
        element.setAttribute('target', '_blank');
      }
    });
  }
}

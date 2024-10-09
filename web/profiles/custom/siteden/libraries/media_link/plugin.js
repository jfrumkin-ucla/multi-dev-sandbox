(function ($) {
  'use strict';

  CKEDITOR.plugins.add('media_link', {
    requires: 'widget',

    // Register the icon used for the toolbar button. It must be the same
    // as the name of the widget.
    icons: 'media_link',
    hidpi: true,

    // Configure CKEditor DTD for custom drupal-entity element.
    // @see https://www.drupal.org/node/2448449#comment-9717735
    beforeInit: function (editor) {
      var dtd = CKEDITOR.dtd;

      dtd['media-link'] = {'div': 1};
      for (var tagName in dtd) {
        if (dtd[tagName].p) {
          dtd[tagName]['media-link'] = 1;
        }
      }
    },

    init: function (editor) {
      // Register the editing dialog.
      CKEDITOR.dialog.add('media_link', this.path + 'dialogs/media_link.js');

      // Add our plugin-specific CSS to style the widget within CKEditor.
      editor.addContentsCss(this.path + 'css/media-link.css');

      // Add toolbar button for this plugin.
      editor.ui.addButton('media_link', {
        label: 'Teaser Link Box',
        command: 'media_link',
        toolbar: 'insert,10',
        icon: this.path + 'icons/' + (CKEDITOR.env.hidpi ? 'hidpi/' : '') + 'media_link.png'
      });

      // Register the widget.
      editor.widgets.add('media_link', {
        // Create the HTML template
        template:
        '<media-link url="#">' +
          '<div slot="image">' +
            '<drupal-media data-entity-type="media" data-entity-uuid="placeholder" />' +
          '</div>' +
          '<div slot="title">Title</div>' +
          '<div slot="content"><p>Content</p></div>' +
        '</media-link>',

        editables: {
          image: {
            selector: '[slot="image"]',
            allowedContent: 'drupal-media[data-entity-type, data-entity-uuid, data-view-mode]'
          },
          title: {
            selector: '[slot="title"]',
            allowedContent: 'span'
          },
          content: {
            selector: '[slot="content"]',
            allowedContent: 'p br strong em'
          }
        },

        // Prevent the editor from removing these elements
        allowedContent: 'media-link[!url]; div(!slot)',

        // The minimum required for this to work
        requiredContent: 'media-link',

        // Convert any media-link element into this widget
        upcast: function (element) {
          return element.name === 'media-link';
        },

        // Set the widget dialog window name. This enables the automatic widget-dialog binding.
        // This dialog window will be opened when creating a new widget or editing an existing one.
        dialog: 'media_link',

        // When a widget is being initialized, we need to read the data ("align")
        // from DOM and set it by using the widget.setData() method.
        // More code which needs to be executed when DOM is available may go here.
        init: function () {
          // Get the URL from the HTML5 data attribute
          var link = this.element.getAttribute('url');
          if (link && link !== '#') {
            this.setData('linkURL', link);
          }
        },

        // Listen on the widget#data event which is fired every time the widget data changes
        // and updates the widget's view.
        // Data may be changed by using the widget.setData() method
        data: function () {
          this.element.setAttribute('url', this.data.linkURL);
        }

      });

    }

  });


})(jQuery);

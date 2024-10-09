import jQuery from 'jquery';

// Panel Mobile Collapse
((($, window) => {

  $(document).ready(() => {

    const bp_medium_up = '(min-width: 1024px)';
    const $panel_title = $('.panel--mobile-collapse .panel__title');
    const $panel_content = $('.panel--mobile-collapse .panel__content');

    const mobileSwitch = (mql) => {
      // Desktop
      if (mql.matches) {
        // Remove click bind from title
        $panel_title.off('click');
        $panel_content.show();
      }

      // Mobile
      else {
        // First: hide content
        $panel_content.hide();

        $panel_title.on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          $(this).siblings('.panel__content').slideToggle(300);
        });
      }
    };

    // get MediaQueryList Interface
    const mql = window.matchMedia(bp_medium_up);

    mql.addListener(mobileSwitch);
    // On Load
    mobileSwitch(mql);
  });

}))(jQuery, window); // end jquery enclosure

import jQuery from 'jquery';
import {setCollapseAccessibility, toggleAriaExpanded} from './accessiblity-functions';

(($ => {

  $(document).ready(() => {

    const $button = $('.list--accordion li:even');
    const $content = $('.list--accordion li:odd');

    // Hide all accordion content
    $content.addClass('visuallyhidden');

    setCollapseAccessibility('.list--accordion li:nth-child(odd)', '.list--accordion li:nth-child(even)', 'list-accordion');

    // Open/close accordion content on click or Return/Enter key.
    $button.on('click keypress', function (e) {
      e.preventDefault();
      e.stopPropagation();

      if (e.keyCode === 13 || e.type === 'click') {
        $(this)
          .toggleClass('active')
          .next('li')
          .toggleClass('visuallyhidden');
        toggleAriaExpanded(this);
      }
    });

  });

}))(jQuery); // end jquery enclosure

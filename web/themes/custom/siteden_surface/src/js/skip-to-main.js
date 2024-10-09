import jQuery from 'jquery';

(($ => {

    $('.skip-to-main-button').on('focus', function() {
        $(this).addClass('view-button');
      });
      $('.skip-to-main-button').on('blur', function() {
        $(this).removeClass('view-button');
      });

}))(jQuery);

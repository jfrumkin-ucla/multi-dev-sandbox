import jQuery from 'jquery';
import {
  setCollapseAccessibility,
  toggleAriaExpanded
} from './accessiblity-functions';

(($ => {

  const contentSelector = '.nav-primary__sublist';
  const buttonSelector = '.nav-primary__toggle';
  const breakpoint = 1024;

  $( document ).ready(function(e) {
      $(".ucla__cover_button-control").click(function(){
          if($(this).hasClass("playing")){
              $('.hero-video-container video').trigger('pause');
              $(this).removeClass("playing");
              $(".ucla__cover_button-control").html('<img src="/themes/custom/siteden_surface/assets/images/icons/denotive/icons8-circled-play-48.png">');
          } else {
              $('.hero-video-container video').trigger('play');
              $(this).addClass("playing");
              $(".ucla__cover_button-control").html('<img src="/themes/custom/siteden_surface/assets/images/icons/denotive/icons8-pause-button-48.png">');
          }
      })
  });  

  // Hide sub items in small device sizes
  $(contentSelector).addClass('is-hidden');

  setCollapseAccessibility(buttonSelector, contentSelector, 'subnav');

  // Show nav children on mouseover of primary nav anchor
  $('li.has-children').each(function () {
    $(this).mouseover(function () {
      if (window.ontouchstart === undefined && $(window).width() >= breakpoint) {  // Prevent the mouseover event from doing anything on mobile.
        $(this).children('.nav-primary__toggle').addClass('is-open').attr('aria-expanded', 'true');
        $(this).children('.nav-primary__sublist').removeClass('is-hidden');
        $(this).children().first().addClass('expanded-parent');
      }
    }).mouseout(function () {
      if (window.ontouchstart === undefined && $(window).width() >= breakpoint) {  // Prevent the mouseout event from doing anything on mobile.
        $(this).children('.nav-primary__toggle').removeClass('is-open').attr('aria-expanded', 'false');
        $(this).children('.nav-primary__sublist').addClass('is-hidden');
        $(this).children().first().removeClass('expanded-parent');
      }
    })
  });

  // Show nav children on click or enter of toggle
  $(".nav-primary__toggle").each(function () {
    $(this).on('click keypress', function (e) {
      if (e.code === 'Tab' || e.type === 'click') {
        if ($(this).siblings('.nav-primary__sublist').hasClass('is-hidden')) {
          $(this).siblings('.nav-primary__sublist').removeClass('is-hidden');
          $(this).addClass('is-open');
          $(this).prev().addClass('expanded-parent');
          toggleAriaExpanded(this);
        }
        else {
          $(this).siblings('.nav-primary__sublist').addClass('is-hidden');
          $(this).removeClass('is-open');
          $(this).prev().removeClass('expanded-parent');
          toggleAriaExpanded(this);
        }
        e.preventDefault();
      }
    })
  });

  // Evaluate mobile sub nav states on page load
  evalNav($(window).width());

  // Run evaluation on page resize
  $(window).resize(function () {
    evalNav($(window).width());
  });

  // Hide open mobile sub navs above browser width 1024px
  function evalNav(windowWidth) {

    if (windowWidth >= breakpoint) {
      $(contentSelector).addClass('is-hidden');
      $(buttonSelector).removeClass('is-open').attr("aria-expanded", "false");

      $('.nav-primary__list > li.has-children').each(function () {
        let offsetL = $(window).width() - $(this).offset().left;
        let menuWidth = 320;
        let desiredMargin = 12;

        // If there isn't enough room for one child menu, make the nav expand
        // to the left.
        if (offsetL <= (menuWidth + desiredMargin)) {
          $(this).addClass('open-left');
          // console.log('not enough room for one menu, open left');
        }
        else {
          // See if any of the children have children. If there isn't enough
          // room for 2 subnav menus, make the nav expand to the left.
          if ($(this).find('.has-children').length) {
            // console.log('has grandchildren')
            if (offsetL <= (menuWidth * 2 + desiredMargin)) {
              $(this).addClass('open-left');
              // console.log('not enough room for two menus, open left');
            }
          }
        }
      });
    }
  }

  let $hamburgerBtn = $('.hamburger');
  let $primaryNav = $('.header__navbar');
  // let $header = $('#header');

  $hamburgerBtn.bind('click', function () {

    //if not active add class active
    if (!$(this).hasClass('is-active')) {

      $(this).addClass('is-active');
      $primaryNav.addClass('is-active');
      $(this).attr('aria-expanded', 'true');

      // mobileNavPosition();
      // enableMenuTab();

    }
    else {

      $(this).removeClass('is-active');
      $primaryNav.removeClass('is-active');
      $(this).attr('aria-expanded', 'false');
      // disableMenuTab();
    }
  });


}))(jQuery);

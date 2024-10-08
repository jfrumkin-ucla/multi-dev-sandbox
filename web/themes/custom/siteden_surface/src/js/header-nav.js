import jQuery from 'jquery';

(($ => {

  const breakpoint = 1024;

  closeMobileMenu($(window).width());

  $(window).resize(function () {
    closeMobileMenu($(window).width());
  });

  if(document.documentElement.clientWidth < 1024){
      $(".secondary-menu-item").appendTo(".nav-primary__list");
      $("#block-siteden-surface-main-menu").addClass("menu-appended");
  }
  window.addEventListener('resize', function(event){
    if(document.documentElement.clientWidth < 1024){
        $(".secondary-menu-item").appendTo(".nav-primary__list");
        $("#block-siteden-surface-main-menu").addClass("menu-appended");
    } else {
        $(".secondary-menu-item").appendTo("#secondary-menu-ul");
        $("#block-siteden-surface-main-menu").removeClass("menu-appended");
    }
  });

  $('.hamburger').on('click keypress', function (e) {
    if (e.key === 'Enter' || e.type === 'click') {
      $('.header__navbar').toggleClass('header__navbar--open');
      $('.hamburger').toggleClass('hamburger--is-active');
    }
  });

  function SetFocusNewControl() {
    if ($("#edit-keys").length) {
      $('#edit-keys').focus();
    } else {
      homelink.focus();
    }
  }

  //Add focus on dynamic search box in Mobile
  $('#primary-ham').on('keydown', function (e) {
    if (e.code === "Tab" && $(this).hasClass('is-active')) {
      e.preventDefault();
      $(':focus').blur();
      setTimeout(function() {
        SetFocusNewControl();
      })
    }
  });

  if(document.getElementById("block-siteden-surface-websitelastupdated")!==null){
    $(".most-recent-update-time").empty();
    $("#block-siteden-surface-websitelastupdated").appendTo(".most-recent-update-time");
  }



  var homelink = $('ul.nav-primary__list li a').first();

  //set focus back to the search button or hamburger button when user hits shit-key from the search input
  $('#edit-keys').on('keydown', function (e) {
    if ($('#search-button').hasClass('is-active')) {
      if (e.code === "Tab" ) {
        if (e.shiftKey) {
          e.preventDefault();
          $('#search-button').focus();
        }
      }
    } else if ($('#primary-ham').hasClass('is-active')) {
      if (e.code === "Tab" ) {
        if (e.shiftKey) {
          e.preventDefault();
          $('#primary-ham').focus();
        } else {
          e.preventDefault();
          homelink.focus();
        }
      }
    }
  });

  //control vertical tabbing in hamburger menu
  homelink.on('keydown', function (e) {
    if (e.code === "Tab") {
      if (e.shiftKey && $('#primary-ham').hasClass('is-active')) {
        if ($("#edit-keys").length) {
          e.preventDefault();
          $('#edit-keys').focus();
        } else {
          e.preventDefault();
          $('#primary-ham').focus();
        }
      }
    }
  });

  Drupal.behaviors.siteden_surface = {

    attach: function (context, settings) {
      $('body').removeClass('no-jscript');
    }
  }

  function closeMobileMenu(windowWidth) {

    if (windowWidth >= breakpoint) {
      $('.header__navbar').removeClass('header__navbar--open');
      //to account for custom Site Name blocks on the Research site. Add other sites as needed. JK 05/08/2024
      $('#block-reositename .field--name-body').removeClass('clearfix');
      $('#block-nagprasitename .field--name-body').removeClass('clearfix');
      $('#block-rcpsitename .field--name-body').removeClass('clearfix');
      $('#block-emssitename .field--name-body').removeClass('clearfix');
      $('#block-csositename .field--name-body').removeClass('clearfix');
      $('#block-bruinvoicesitename .field--name-body').removeClass('clearfix');
      $('#block-firesitename .field--name-body').removeClass('clearfix');
    }
  }

}))(jQuery);

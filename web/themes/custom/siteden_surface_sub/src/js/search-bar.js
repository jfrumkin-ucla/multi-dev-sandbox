import jQuery from 'jquery';

(($ => {

  const breakpoint = 1024;

  const $headerNavBar = $('.header__navbar');
  const $primaryNav = $('.nav-primary');
  const $searchBar = $('.search-bar');
  const $searchButton = $('.search-bar__button');

  // Show and hide the search bar on click
  $searchButton.on('click', function () {
    const $this = $(this);

    $this.parent().toggleClass('search-bar--open');
    $searchButton.toggleClass('is-active');
    $searchBar.toggleClass('is-active');

    if ($this.hasClass('is-active')) {
      $this.attr('aria-expanded', 'true' );
      $('#edit-keys').trigger('focus');
    } else {
      $this.attr('aria-expanded', 'false');
    }

    if ($searchBar.hasClass('is-active')) {
      $('.search-bar__button > svg').replaceWith('<svg role="img" aria-hidden="true" class="close-x" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>Icon Close</title><g id="Icon/Close" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon id="Shape" fill="#ffffff" points="19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12"></polygon></g></svg>');
    } else {
      $('.search-bar__button > svg').replaceWith('<svg role="img" aria-hidden="true" class="nav-primary__search-icon" width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>Search Icon</title><g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="search-nav-icon-primary" transform="translate(-15.000000, -15.000000)"><g id="Nav-Item"><g id="Icon/Search" transform="translate(12.000000, 12.000000)"><polygon class="Path-polygon" points="0 0 24 0 24 24 0 24"></polygon><path d="M15.5,14 L14.71,14 L14.43,13.73 C15.41,12.59 16,11.11 16,9.5 C16,5.91 13.09,3 9.5,3 C5.91,3 3,5.91 3,9.5 C3,13.09 5.91,16 9.5,16 C11.11,16 12.59,15.41 13.73,14.43 L14,14.71 L14,15.5 L19,20.49 L20.49,19 L15.5,14 Z M9.5,14 C7.01,14 5,11.99 5,9.5 C5,7.01 7.01,5 9.5,5 C11.99,5 14,7.01 14,9.5 C14,11.99 11.99,14 9.5,14 Z" id="Shape" fill="#00598C" fill-rule="evenodd"></path></g></g></g></g></svg>');
    }

    setTimeout(function () {
      $primaryNav.toggleClass('search-bar__trigger');
    }, 1000);
  });

  // If the main nav is used close the search
  $headerNavBar.on('mouseover', '.search-bar__trigger', function () {
    closeSearch();
  });

  // Evaluate search bar states on page load
  evalSearch($(window).width());

  // Run evaluation on page resize
  $(window).resize(function () {
    evalSearch($(window).width());
  });

  // Show and hide search bar elements above and below browser width 1024px
  function evalSearch(windowWidth) {
    if (windowWidth >= breakpoint) {
      closeSearch();
    }
    else {
      $searchBar.removeClass('is-active');
    }
  }

  // Toggle the button and form
  function closeSearch() {
    $searchBar.removeClass('is-active');
    $searchButton.removeClass('is-active').attr("aria-expanded","false")
    $primaryNav.removeClass('search-bar__trigger');
    $('.search-bar__button > svg').replaceWith('<svg role="img" aria-hidden="true" class="nav-primary__search-icon" width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>Search Icon</title><g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="search-nav-icon-primary" transform="translate(-15.000000, -15.000000)"><g id="Nav-Item"><g id="Icon/Search" transform="translate(12.000000, 12.000000)"><polygon class="Path-polygon" points="0 0 24 0 24 24 0 24"></polygon><path d="M15.5,14 L14.71,14 L14.43,13.73 C15.41,12.59 16,11.11 16,9.5 C16,5.91 13.09,3 9.5,3 C5.91,3 3,5.91 3,9.5 C3,13.09 5.91,16 9.5,16 C11.11,16 12.59,15.41 13.73,14.43 L14,14.71 L14,15.5 L19,20.49 L20.49,19 L15.5,14 Z M9.5,14 C7.01,14 5,11.99 5,9.5 C5,7.01 7.01,5 9.5,5 C11.99,5 14,7.01 14,9.5 C14,11.99 11.99,14 9.5,14 Z" id="Shape" fill="#00598C" fill-rule="evenodd"></path></g></g></g></g></svg>');
  }
}))(jQuery);

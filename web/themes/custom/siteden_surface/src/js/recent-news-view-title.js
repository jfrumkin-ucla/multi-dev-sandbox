document.addEventListener('DOMContentLoaded', () => {

  function updateTitle() {
    let blockTitle = document.querySelectorAll('.block-page-title-block .page-title:first-of-type')[0];
    let viewTitle = document.querySelectorAll('.recent-articles-view-title:first-of-type')[0];

    if (!(blockTitle) || !(viewTitle)) {
      return;
    }

    let string2 = "Recent Articles";
    let blockText = blockTitle.textContent;
    let viewText = viewTitle.textContent;
    let pageCrumb = document.querySelector('.breadcrumb li:nth-child(2) a');

    if (viewText) {
      if (!(viewText === string2) || !(viewText === blockText)) {
        blockTitle.innerHTML = viewText;
        if (pageCrumb) {
          pageCrumb.innerHTML = viewText;
        }
      }
    }
  }
  updateTitle();
});

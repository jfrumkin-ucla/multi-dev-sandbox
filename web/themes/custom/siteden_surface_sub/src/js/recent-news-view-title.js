document.addEventListener('DOMContentLoaded', () => {

  function updateTitle() {
    let blockTitle = document.querySelectorAll('.page-title:first-of-type')[0];
    let viewTitle = document.querySelectorAll('.recent-articles-view-title:first-of-type')[0];
    let blockText = blockTitle.textContent.replace(/\W+/g, ' ');
    let viewText = viewTitle.textContent.replace(/\W+/g, ' ');
    let pageCrumb = document.querySelector('.breadcrumb li:nth-child(2) a');

    if (blockText.toLowerCase() == viewText.toLowerCase()) {
      return;
    } else {
      blockTitle.innerHTML = viewText;
      pageCrumb.innerHTML = viewText;
    }
  }
  updateTitle();
 });

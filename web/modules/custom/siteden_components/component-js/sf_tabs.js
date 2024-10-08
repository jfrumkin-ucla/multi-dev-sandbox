Drupal.behaviors.sfTabs = {
  attach: function (context, settings) {
    once('sfTabs', '.sf_tabs', context).forEach(function (element) {
      const tabButtons = element.querySelectorAll('.tabs__control');
      const tabPanels = element.querySelectorAll('.tabs__panel');
      let handleTabClick = (e) => {
        tabButtons.forEach((button) => {
          button.classList.remove('is-active');
        });
        tabPanels.forEach((panel) => {
          panel.hidden = true;
        });
        tabButtons.forEach((tab) => {
          tab.setAttribute('aria-selected', false);
        });
        e.currentTarget.setAttribute('aria-selected', true);
        e.currentTarget.classList.add('is-active');
        const {id} = e.currentTarget;
        const tabPanel = element.querySelector(`#${id}-tab`);
        tabPanel.hidden = false;
      };
      tabButtons.forEach(
        (button) => button.addEventListener('click', handleTabClick)
      );

    });
  }
}

// Programmatically modify the Editoria11y options.
const editoria11yOptionsOverride = true;
const editoria11yOptions = function (options) {
  if (!options['hiddenHandlers']) {
    // Replace with default value.
    options['hiddenHandlers'] = `.js-tabs__panels`;
  }
  else {
    // Append default value with comma separator.
    options['hiddenHandlers'] = `${options['hiddenHandlers']}, .js-tabs__panels`;
  }
  options['allowHide'] = false;
  options['allowOK'] = false;

  return options;
};

Drupal.behaviors.editoria11yCustom = {
  attach: function (context, settings) {
    once('ed11yCustom-init', 'body', context).forEach(function (element) {

      // Actions when the Ed11y panel is opened.
      document.addEventListener("ed11yPanelOpened", function (event) {
        let resultItems = document.querySelectorAll('[id^="ed11y-result-"]');

        resultItems.forEach((resultElement) => {
          // Open collapsed blocks.
          if (resultElement.closest('.accordion__content.visuallyhidden')) {
            resultElement.closest('.accordion__content.visuallyhidden').previousElementSibling.click();
          }
          // Open hidden accordions.
          if (resultElement.closest('.list--accordion li.visuallyhidden')) {
            resultElement.closest('.list--accordion li.visuallyhidden').previousElementSibling.click();
          }
        });
      });

      // Actions when a hidden element is shown.
      document.addEventListener('ed11yShowHidden', function (e) {
        let currentResultId = "ed11y-result-" + e.detail.result;
        let currentResultElement = document.getElementById(currentResultId);

        // Open hidden tabs.
        if (currentResultElement.closest('.js-tabs__panel.visuallyhidden')) {
          let panelId = currentResultElement.closest('.js-tabs__panel.visuallyhidden').id;
          let controlId = 'tab-' + panelId;
          document.getElementById(controlId).click();
        }
      });
    });
  }
}

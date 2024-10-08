document.addEventListener('DOMContentLoaded', () => {
  const $accordionSections = document.querySelectorAll('.sf_accordion');
  if ($accordionSections.length > 0) {
    let i = 0;
    $accordionSections.forEach(($el) => {
      i++;
      let newAccordionSection = 'accordion_section' + i;
      $el.classList.add(newAccordionSection);
    });
  }

  const $section1Buttons = document.querySelectorAll('.accordion_section1 .accordion__label');
  const $section2Buttons = document.querySelectorAll('.accordion_section2 .accordion__label');
  const $section3Buttons = document.querySelectorAll('.accordion_section3 .accordion__label');
  const $section4Buttons = document.querySelectorAll('.accordion_section4 .accordion__label');
  const $section5Buttons = document.querySelectorAll('.accordion_section5 .accordion__label');
  const array1 = Array.from($section1Buttons);
  const array2 = Array.from($section2Buttons);
  const array3 = Array.from($section3Buttons);
  const array4 = Array.from($section4Buttons);


  if ($section1Buttons.length > 0) {
    var i = 0;
    $section1Buttons.forEach(($label) => {
      i++;
      var newId = 'accordion' + i;
      $label.setAttribute('id', newId);
    });
  }

  if ($section2Buttons.length > 0) {
    var i = array1.length;
    $section2Buttons.forEach(($label) => {
      i++;
      var newId = 'accordion' + i;
      $label.setAttribute('id', newId);
    });
  }

  if ($section3Buttons.length > 0) {
    var i = array1.length + array2.length;
    $section3Buttons.forEach(($label) => {
      i++;
      var newId = 'accordion' + i;
      $label.setAttribute('id', newId);
    });
  }

  if ($section4Buttons.length > 0) {
    var i = array1.length + array2.length + array3.length;
    $section4Buttons.forEach(($label) => {
      i++;
      var newId = 'accordion' + i;
      $label.setAttribute('id', newId);
    });
  }

  if ($section5Buttons.length > 0) {
    var i = array1.length + array2.length + array3.length + array4.length;
    $section5Buttons.forEach(($label) => {
      i++;
      var newId = 'accordion' + i;
      $label.setAttribute('id', newId);
    });
  }

  if (window.location.hash) {
    var hash = window.location.hash.replace(/[=%;,\/]/g, "");
    //Puts hash in variable, and removes the # character
    var hashID = hash.substring(1);
    document.getElementById(hashID).click();
  }

  window.addEventListener('hashchange', (e) => {
    var hash = e.target.location.hash.replace(/^.*?(#|$)/, '');
    document.getElementById(hash).click();
  },
    //false,
  );
});


Drupal.behaviors.sfAccordion = {
  attach: function (context, settings) {
    once('sfAccordion', '.sf_accordion', context).forEach(function (element) {
      const $accordionButtons = element.querySelectorAll('.accordion__label');
      const $accordionItems = element.querySelectorAll('.accordion');

      if ($accordionButtons.length > 0) {
        $accordionButtons.forEach(($el) => {
          $el.addEventListener('click', (e) => {
            e.stopPropagation();
            let $currentAccordion = e.currentTarget.closest('.accordion');
            if ($currentAccordion.classList.contains('is-open')) {
              $currentAccordion.classList.toggle('is-open');
              e.currentTarget.setAttribute('aria-expanded', 'false');
              return;
            }
            else {
              e.currentTarget.setAttribute('aria-expanded', 'true');
            }
            if (!$el.classList.contains('is-multiselect')) {
              $accordionItems.forEach((item) => {
                if (item.classList.contains('is-open')) {
                  item.classList.remove('is-open');
                  item.querySelector(
                    '.accordion__label[aria-expanded]'
                  ).setAttribute('aria-expanded', 'false');
                }
              });
            }
            $currentAccordion.classList.toggle('is-open');
          });
        });
      }
    });
  }
}

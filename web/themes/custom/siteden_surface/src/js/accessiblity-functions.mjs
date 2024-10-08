/**
 * Set up the initial aria tags needed to make a collapsible section accessible.
 *
 * @param {string} buttonSelector
 * @param {string} contentSelector
 * @param {string} uniqueIdentifier
 * @param {string} ariaExpandedStart
 *   Although this is a string, the string value should be a boolean.
 */
export function setCollapseAccessibility(buttonSelector, contentSelector, uniqueIdentifier, ariaExpandedStart = 'false') {
  const buttons = document.querySelectorAll(buttonSelector);
  const content = document.querySelectorAll(contentSelector);

  for (let i = 0; i < buttons.length; ++i) {
    buttons[i].setAttribute('aria-controls', uniqueIdentifier + '-' + i);
    buttons[i].setAttribute('aria-expanded', ariaExpandedStart);
    buttons[i].setAttribute('tabindex', 0);
  }

  for (let i = 0; i < content.length; ++i) {
    content[i].setAttribute('id', uniqueIdentifier + '-' + i);
  }
}

/**
 * Toggle the Aria Expanded attribute on a button
 *
 * @param {HTMLElement} button
 */
export function toggleAriaExpanded(button) {
  if (button.getAttribute('aria-expanded') === 'false') {
    button.setAttribute('aria-expanded', 'true');
  }
  else {
    button.setAttribute('aria-expanded', 'false');
  }
}

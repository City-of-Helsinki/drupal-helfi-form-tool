(function ($, Drupal, drupalSettings) {
  Drupal.theme.formTooldialog = function (options) {
    var element = $('<div class="dialog__container">' +
      '<div class="dialog__content">' +
        '<div class="dialog__header">' +
          '<button type="button" class="hds-button dialog__close-button" aria-label="Close dialog"><span class="hel-icon hel-icon--cross" aria-label="Jaa Facebook-palvelussa"></span></button>' +
          '<h2 tabindex="-1"><span aria-hidden="true" class="hel-icon hel-icon--info-circle hel-icon--size-l"></span> '+ options.headerText +'</h2></div>' +
        '<div class="dialog__body"><p>' + options.bodyText + '</p></div>' +
        '<div class="dialog__actions">' +
          '<a href="' + options.logoutLink + '" class="hds-button hds-button--primary">' +
            '<span class="hds-button__label">'+options.logoutBtnText+'</span>' +
          '</a>' +
          '<button type="button" class="hds-button hds-button--secondary dialog__close-button">' +
            '<span class="hds-button__label">' + options.continueBtnText + '</span>' +
          '</button>' +
        '</div>' +
      '</div>' +
    '</div>').appendTo('body');

  var focusableElements = $(element).find('a, button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select');
  var firstFocusableElement = focusableElements[0];
  var lastFocusableElement = focusableElements[focusableElements.length - 1];

  // Capture tab focus to modal while open
  document.addEventListener('keydown', function(e) {
    let isTab = e.key === 'Tab' || e.keyCode === 9;

    if (!isTab) {
      return;
    }

    if (e.shiftKey) {
      if (document.activeElement === firstFocusableElement) {
        lastFocusableElement.focus();
        e.preventDefault();
      }
    } else {
      if (document.activeElement === lastFocusableElement) {
        firstFocusableElement.focus();
        e.preventDefault();
      }
    }
  })

  // Focus on header text.
  $('.dialog__header h2').focus();

  $('.dialog__close-button').on('click', function() {
    $(element).remove();
    options.iniatorElement.focus();
  });

  return element;
  }
})(jQuery, Drupal, drupalSettings);




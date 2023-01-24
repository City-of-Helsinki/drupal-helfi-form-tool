(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.outbound_links = {
    attach: function (context, settings) {
      $('a').click(function (event) {

        var element;

        // External link icon might fire the event in some cases.
        if (event.target.tagName !== 'A') {
          element = event.target.parentNode;
        } else {
          element = event.target;
        }

        var classList = ['confirm-dialog'];
        var elementClassList = [].slice.call(element.classList, 0);
        var intersectedClasses = $(elementClassList).filter(classList);

        var external = !element.href.includes(drupalSettings.form_tool_profile.basePath);

        if (external || intersectedClasses.length) {
          event.preventDefault();
          Drupal.theme.formTooldialog({
            iniatorElement: element,
            bodyText: Drupal.t('You are about to exit the form and log out of the service.'),
            headerText: Drupal.t('You are about to exit the form.'),
            continueBtnText: Drupal.t('Continue on the form'),
            logoutBtnText: Drupal.t('Log out and exit')
          });
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);

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

        // Skip anchor and relative links.
        if (/^(#|\/)/.test(element.href)) {
          return;
        }

        var confirmClassList = ['confirm-dialog'];
        var skipClassList = ['skip-confirm-logout'];
        var elementClassList = [].slice.call(element.classList, 0);

        var intersectedConfirmClasses = $(elementClassList).filter(confirmClassList);
        var intersectedSkipClasses = $(elementClassList).filter(skipClassList);

        // We wan't to skip this due the skip dialog class.
        if (intersectedSkipClasses.length) {
          return;
        }

        // Ignore links that open up in new window / tab.
        if (element.hasAttribute('target') && (element.getAttribute('target') === '_blank')) {
          return;
        }

        var internal = drupalSettings.form_tool_profile.basePaths.some(function (url) {
          return element.href.includes(url);
        });

        if (!internal || intersectedConfirmClasses.length) {
          event.preventDefault();
          Drupal.theme.formTooldialog({
            iniatorElement: element,
            bodyText: Drupal.t('You are about to exit the form and log out of the service.'),
            headerText: Drupal.t('You are about to exit the form.'),
            continueBtnText: Drupal.t('Continue on the form'),
            logoutBtnText: Drupal.t('Log out and exit'),
            logoutLink: drupalSettings.form_tool_profile.logout
          });
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);

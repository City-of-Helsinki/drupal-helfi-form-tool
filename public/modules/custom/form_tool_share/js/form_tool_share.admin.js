/**
 * @file
 * JavaScript behaviors for form-tool share admin.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Webform share admin copy.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.formToolShareAdminCopy = {
    attach: function (context) {
      $(context).find('.js-form-tool-share-admin-copy').once('form-tool-share-admin-copy').each(function () {
        var $container = $(this);
        var $textarea = $container.find('textarea');
        var $button = $container.find(':submit, :button');
        var $message = $container.find('.form-tool-share-admin-copy-message');
        // Copy code from textarea to the clipboard.
        // @see https://stackoverflow.com/questions/47879184/document-execcommandcopy-not-working-on-chrome/47880284
        $button.on('click', function () {
          if (window.navigator.clipboard) {
            window.navigator.clipboard.writeText($textarea.val());
          }
          $message.show().delay(1500).fadeOut('slow');
          Drupal.announce(Drupal.t('Code copied to clipboard…'));
          return false;
        });
      });
    }
  };

})(jQuery, Drupal);

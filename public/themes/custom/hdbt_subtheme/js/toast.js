(function ($, Drupal) {
  Drupal.behaviors.hdbt_toast = {
    attach: function (context, settings) {
      once('toast-notifications', 'body').forEach(function (element) {
        $(document).on("click", '.hds-notification__close-button', function() {
          var closest = $(this).closest('section');
          if (closest) {
            closest.remove();
          }
        })
      })
    }
  }
}(jQuery, Drupal));

(function ($, Drupal) {
  Drupal.behaviors.form_tool_profile_data_refresh = {
    attach: function (context, settings) {
      once('profile-data-refresh', '.profile-data__refresh-link').forEach(function (element) {
        element.addEventListener('click', function(event) {
          event.preventDefault();
          $('.profile-refresh-data-button').trigger('mousedown');
        })
      })
    }
  }
}(jQuery, Drupal));

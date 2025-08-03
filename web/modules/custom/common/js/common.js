(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.customCommon = {
    attach: function (context, settings) {
      $(once('customCommon', '.nav-link', context)).on('click', function () {
        alert();
        alert();
      });

      // Corrected the selector from ".nav-lin" to ".nav-link"
      $(".nav-link", context).on("click", function () {
        // Remove active class from all nav links and tab panes
        $('.nav-link', context).removeClass('active');
        $('.tab-pane', context).removeClass('show active');

        // Add active class to the clicked nav link
        $(this).addClass('active');

        // Get the target tab content ID from the data attribute
        var target = $(this).data('bs-target');

        // Show the corresponding tab content
        $(target, context).addClass('show active');
      });
    }
  };

})(jQuery, Drupal, drupalSettings);

(function ($, window, Drupal, drupalSettings) {
  // var loadingCompleted = 0;
  

}(jQuery, this, Drupal, drupalSettings));

(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.googleMapBlock = {
    attach: function (context, settings) {
      var apiKey = drupalSettings.google_map_block.apiKey;
      var location = drupalSettings.google_map_block.location;

      // Initialize the Google Map.
      var map = new google.maps.Map(document.getElementById('google-map'), {
        zoom: 10,
        center: { lat: 40.7128, lng: -74.0060 }, // Default to New York
      });

      // Add a marker for the specified location.
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ 'address': location }, function (results, status) {
        if (status === 'OK') {
          map.setCenter(results[0].geometry.location);
          new google.maps.Marker({
            map: map,
            position: results[0].geometry.location,
          });
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);

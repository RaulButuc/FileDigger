var fileDiggerMapOptions = {
  center: { lat: 53.466860, lng: -2.233873},
  zoom: 15,
  disableDefaultUI: true,
  disableDoubleClickZoom: true,
  draggable: false,
  scrollwheel: false,
  streetViewControl: false
};

var fileDiggerMap = new google.maps.Map(document.getElementById('mapCarousel'), fileDiggerMapOptions);
var navGeolocation = navigator.geolocation;

google.maps.event.addDomListener(window, 'load', initialize);

function initialize() {
  if (navGeolocation) {
    navGeolocation.getCurrentPosition(function (position) {
      var userLat = position.coords.latitude;
      var userLong = position.coords.longitude;
      var userLocation = new google.maps.LatLng(userLat, userLong);
      var userLocationInfoWndContents = '<h5> FileDigger - Location Based File Sharing </h5>' + 
                                        '<div> FileDigger is a site which allows you to store your files and pin them to positions on a map for sharing. <a href="" data-toggle="modal" data-target="#registerModal">Register today</a> to begin uploading! </div>';

      var userLocationMarker = new google.maps.Marker({
        map: fileDiggerMap,
        position: userLocation
      });

      var userLocationInfoWnd = new google.maps.InfoWindow({
        content: userLocationInfoWndContents,
        maxWidth: 400
      });
      
      fileDiggerMap.setCenter(userLocation);
      fileDiggerMap.panBy(0, -150);
      hideUnwantedPopups(fileDiggerMap);
      userLocationInfoWnd.open(fileDiggerMap, userLocationMarker);
      
      google.maps.event.addListener(userLocationMarker, 'click', function() {
        userLocationInfoWnd.open(fileDiggerMap, userLocationMarker);
      });
    });
  }
  else {
    handleGeolocationUnavailable(false, fileDiggerMap);
  }
}
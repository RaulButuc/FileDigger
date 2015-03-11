function handleGeolocationUnavailable(errorFlag, map) {
  if (errorFlag) {
    var content = 'Error: The geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t supoort geolocation.';
  }

  var noLocation = new google.maps.LatLng(20, -10);

  var noLocationMapOptions = {
    center: noLocation,
    map: map,
    position: noLocation
  };

  var noLocationMarker = new google.maps.Marker({
    map: map,
    position: noLocation,
    title: content
  });

  var userLocationInfoWnd = new google.maps.InfoWindow({
        content: content,
        maxWidth: 400
  });
}

function hideUnwantedPopups(map) {
  var mapOverlay = new google.maps.Rectangle({
    strokeColor: '#EEEEE1',
    strokeOpacity: 0.1,
    strokeWeight: 1,
    fillColor: '#EEEEE0',
    fillOpacity: 0.1,
    map: map,
    bounds: map.getBounds()
  });
}
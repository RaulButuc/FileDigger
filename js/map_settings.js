var userLocation;

function handleGeolocationUnavailable(errorFlag, map) {
  if (errorFlag) {
    var content = 'Error: The geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t supoort geolocation.';
  }

  var noLocation = new google.maps.LatLng(20, -10);zz

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

function update(theValue) {
  radiusSlider.value = theValue;
  radius.value = theValue;

  $(function() {
    fileRadius.setRadius(parseFloat(radius.value));
  });
}

// Create a marker on the map.
function placeMarkerForUpload(map, location) {
  uploadBoxHTML = 
    '<!DOCTYPE html>' +
    '<html lang="en">' +
    '<head>' +
      '<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css/sun" />' +
      '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>' +
      '<script type="text/javascript" src="map_settings.js"></script>' +
      '<script type="text/javascript" src="main_map.js"></script>' +
      cssstyle +
    '</head>' +
    '<body>' +
      '<div>' +
        '<form id="uploadFile" method="post" action="upload.php" name="upload" enctype="multipart/form-data"' +
         'oninput="update(rangeInput.value)" onchange="update(rangeInput.value)">' +
         '<input type="file" name="file" accept="*" value="Choose file">' + '<br/>' +
         '<input type="hidden" name="latitude" value="' + location.lat() + '">' +
         '<input type="hidden" name="longitude" value="' + location.lng() + '">' +
         '<input type="range" id="rangeInput" name="rangeInput" min="0" max="500" step="25" value="150">' + '<br/>' +
         '<input type="hidden" id="radius" name="radius" value="150">' +
         '<div id="text-left"> Restrict downloading to radius (0 for download from anywhere): </div>' +
         '<output id="radiusSlider" name="radiusSlider" for="rangeInput">150</output>' +
         '<div id="text-right">  meters</div>' + '<br/>' +
         '<input id="submit-button" class="btn btn-default" type="submit" name="submit" value="Submit">' +
        '</form>' +
      '</div>' +
    '</body>' +
    '</html>';

  var defaultRadiusSize = 150;

  fileMarker.setMap(map);
  fileMarker.setIcon('http://filedigger.me/img/spade-marker.png');
  fileMarker.setPosition(location);

  infoWindowBox.setContent(uploadBoxHTML);
  infoWindowBox.open(map, fileMarker);

  fileRadius.setOptions(radiusOptions);
  fileRadius.setCenter(location);
  fileRadius.setRadius(parseFloat(defaultRadiusSize));
  fileRadius.bindTo('center', fileMarker, 'position');

  google.maps.event.addListener(infoWindowBox, 'closeclick', function() {
    infoWindowBox.close();
    fileMarker.setMap(null);
    fileRadius.setMap(null);
  });

}

function placeMarkerForDownload(fileID, lat, lng, name, radius, map) {
  var markerLocation = new google.maps.LatLng(lat, lng);

  var existingMarker = new google.maps.Marker({
    map: map,
    position: markerLocation,
    icon: 'http://filedigger.me/img/spade-marker.png'
  });

  google.maps.event.addListener(existingMarker, 'click', function() {
    var distance;

    var Radius = 6378137; // Earth’s mean radius in meters
    var dLat = (userLocation.lat() - markerLocation.lat()) * Math.PI / 180;
    var dLong = (userLocation.lng() - markerLocation.lng()) * Math.PI / 180;
    var angle = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos((markerLocation.lat()) * Math.PI / 180) * Math.cos((userLocation.lat()) * Math.PI / 180) *
                Math.sin(dLong / 2) * Math.sin(dLong / 2);
    var c = 2 * Math.atan2(Math.sqrt(angle), Math.sqrt(1 - angle));
    distance = Radius * c; // Meters
    console.log(distance);

    if (distance < radius || radius == 0) {
      var downloadBoxHTMLPart1 = 
        '<!DOCTYPE html>' +
        '<html lang="en">' +
        '<head>' +
          '<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css/sun" />' +
          '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>' +
          '<script type="text/javascript" src="main_map.js"></script>' +
          cssstyle +
        '</head>' +
        '<body>' +
          '<div>' +
            '<form id="downloadFile" method="post" action="download.php" name="download" enctype="multipart/form-data">' +
              '<h2>'; 
      var downloadBoxHTMLPart2 = 
              '</h2>' +
              '<input type="hidden" name="fileID" value="' + fileID + '">' + '<br/>' +
              '<input id="submit-button" class="btn btn-default" type="submit" name="submit" value="Download">' +
            '</form>' +
          '</div>' +
        '</body>' +
        '</html>';
    }
    else {
      var downloadBoxHTMLPart1 = 
        '<!DOCTYPE html>' +
        '<html lang="en">' +
        '<head>' +
          '<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css/sun" />' +
          '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>' +
          '<script type="text/javascript" src="main_map.js"></script>' +
          cssstyle +
        '</head>' +
        '<body>' +
          '<div>' +
            '<form id="downloadFile" method="pose" action="download.php" name="download" enctype="multipart/form-data" onSubmit="return false;">' +
              '<h2>';
      var downloadBoxHTMLPart2 = 
              '</h2>' +
              '<input type="hidden" name="fileID" value="' + fileID + '">' + '<br/>' +
              '<input id="submit-button" class="btn btn-default" type="submit" name="submit" value="Download Unavailable">' +
            '</form>' +
          '</div>' +
        '</body>' +
        '</html>';
    }

    fileMarker.setMap(null);
    infoWindowBox.setContent(downloadBoxHTMLPart1 + name + downloadBoxHTMLPart2);
    infoWindowBox.open(map, existingMarker);
    fileRadius.setOptions(radiusOptions);
    fileRadius.setCenter(markerLocation);
    fileRadius.setRadius(parseFloat(radius));
  });

  google.maps.event.addListener(infoWindowBox, 'closeclick', function() {
    infoWindowBox.close();
    fileRadius.setMap(null);
  });
}

$(document).ready(function() {
  if (navGeolocation) {
    navGeolocation.getCurrentPosition(function (position) {
      var userLat = position.coords.latitude;
      var userLong = position.coords.longitude;
      userLocation = new google.maps.LatLng(userLat, userLong);
    });
  }
  else {
    handleGeolocationUnavailable(false, fileDiggerMap);
  }
});
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

// Create a marker on the map.
function placeMarkerForUpload(map, location) {
  uploadBoxHTML = 
    '<!DOCTYPE html>' +
    '<html lang="en">' +
    '<head>' +
      '<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css/sun" />' +
      // Doesn't work
      //'<link rel="stylesheet" href="range.css" type="text/css"/>' +
      '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>' +
      '<script type="text/javascript" src="main_map.js"></script>' +
      cssstyle +
    '</head>' +
    '<body>' +
      '<div>' +
        '<form id="uploadFile" method="post" action="upload.php" name="upload" enctype="multipart/form-data" oninput="amount.value=rangeInput.value">' +
         '<input type="file" name="file" accept="*" value="Choose file">' + '<br/>' +
         '<input type="hidden" name="latitude" value="' + location.lat() + '">' +
         '<input type="hidden" name="longitude" value="' + location.lng() + '">' +
         '<input type="range" id="rangeInput" name="rangeInput" min="25" max="350">' + '<br/>' +
         '<div id="text-left"> Radius Value: </div>' +
         '<output id="radius-slider" name="amount" for="rangeInput">185</output>' +
         '<div id="text-right">  meters</div>' + '<br/>' +
         '<input id="submit-button" class="btn btn-default" type="submit" name="submit" value="Submit">' +
        '</form>' +
      '</div>' +
    '</body>' +
    '</html>';

  var radiusSize = 185;

  fileMarker.setMap(map);
  fileMarker.setPosition(location);

  infoWindowBox.setContent(uploadBoxHTML);
  infoWindowBox.open(map, fileMarker);

  fileRadius.setOptions(radiusOptions);
  fileRadius.setCenter(location);
  fileRadius.setRadius(parseFloat(radiusSize));

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

  var downloadBoxHTMLPart1 = 
    '<!DOCTYPE html>' +
    '<html lang="en">' +
    '<head>' +
      '<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css/sun" />' +
      // Doesn't work
      //'<link rel="stylesheet" href="range.css" type="text/css"/>' +
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

  google.maps.event.addListener(existingMarker, 'click', function() {
    fileMarker.setMap(null);
    infoWindowBox.setContent(downloadBoxHTMLPart1 + name + downloadBoxHTMLPart2);
    infoWindowBox.open(map, existingMarker);
    fileRadius.setOptions(radiusOptions);
    fileRadius.setCenter(markerLocation);
    fileRadius.setRadius(parseFloat(185)); // use 'radius' instead (after updating the SQL queries)
  });

  google.maps.event.addListener(infoWindowBox, 'closeclick', function() {
    infoWindowBox.close();
    fileRadius.setMap(null);
  });
}
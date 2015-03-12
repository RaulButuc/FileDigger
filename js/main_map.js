// Options for the main map .
var fileDiggerMapOptions = {
  center: { lat: 53.466860, lng: -2.233873},
  zoom: 15,
  minZoom: 7,
  disableDefaultUI: true,
  disableDoubleClickZoom: false,
  draggable: true,
  scrollwheel: true,
  streetViewControl: false
};

// Create the main map.
var fileDiggerMap = new google.maps.Map(document.getElementById('fullScreenMap'), fileDiggerMapOptions);

// Use geolocation.
var navGeolocation = navigator.geolocation;

// A marker on the map representing a file stored.
var fileMarker = new google.maps.Marker();

// An info window box.
var infoWindowBox = new google.maps.InfoWindow();

// Options for radius circles.
var radiusOptions = {
    strokeColor: '#66CD00',
    strokeOpacity: 1,
    strokeWeight: 5,
    fillColor: '#7FFF22',
    fillOpacity: 0.5,
    map: fileDiggerMap
};

// A radius on map.
var fileRadius = new google.maps.Circle();

// Initialize the map on load.
google.maps.event.addDomListener(window, 'load', initialize);

var cssstyle;
var uploadBoxHTML;

// Initialize method
function initialize() {
  if (navGeolocation) {
    navGeolocation.getCurrentPosition(function (position) {
      var userLat = position.coords.latitude;
      var userLong = position.coords.longitude;
      var userLocation = new google.maps.LatLng(userLat, userLong);
      
      fileDiggerMap.setCenter(userLocation);

      google.maps.event.addListener(fileDiggerMap, 'click', function(event) {
        placeMarkerForUpload(fileDiggerMap, event.latLng);
      });
    });
  }
  else {
    handleGeolocationUnavailable(false, fileDiggerMap);
  }
}

$(document).ready(function() {
  // Variable to hold request
  var request;

  // Bind to the submit event of our form
  $("#uploadFile").submit(function(event){

      // Abort any pending request
      if (request) {
          request.abort();
      }
      // setup some local variables
      var $form = $(this);

      // Let's select and cache all the fields
      var $inputs = $form.find("input");

      // Serialize the data in the form
      var serializedData = $form.serialize();

      // Let's disable the inputs for the duration of the Ajax request.
      // Note: we disable elements AFTER the form data has been serialized.
      // Disabled form elements will not be serialized.
      $inputs.prop("disabled", true);

      // Fire off the request to /form.php
      request = $.ajax({
          url: "./../upload.php",
          type: "post",
          data: serializedData
      });

      // Callback handler that will be called on success
      request.done(function (response, textStatus, jqXHR){
          // Log a message to the console
          console.log("Hooray, it worked!");
      });

      // Callback handler that will be called on failure
      request.fail(function (jqXHR, textStatus, errorThrown){
          // Log the error to the console
          console.error("The following error occurred: " + textStatus, errorThrown);
      });

      // Callback handler that will be called regardless
      // if the request failed or succeeded
      request.always(function () {
          // Reenable the inputs
          $inputs.prop("disabled", false);
      });

      // Prevent default posting of form
      event.preventDefault();
  });
  
  /*$("#fullScreenMap").click(function() {
    var htmlCode = $.parseHTML(uploadBoxHTML);
    $("#htmlParsingPlaceholder").append($(htmlCode));
    alert($("#htmlParsingPlaceholder").find("#radius-slider").text());
  });*/

  $.getJSON('mapData.php', function(data) {
    $.each(data, function(key, value) {
      placeMarkerForDownload(value.ID, value.Latitude, value.Longitude, value.Name, value.Radius, fileDiggerMap);
    });
  });
});

// Variable holding the CSS file. Apparently loading dynamically doesn't work,
// so it has to be hardcoded into a string for the InfoWindow to work.
var cssstyle = "<style type='text/css'>" +
  "input[type=range] {" +
   " /*removes default webkit styles*/" +
    "-webkit-appearance: none;" +   
    "/*fix for FF unable to apply focus style bug */" +
    "border: 1px solid white;" +
    "/*required for proper track sizing in FF*/" +
    "width: 300px;" +
"}" +
"input[type=range]::-webkit-slider-runnable-track {" +
 "   width: 300px;" +
  "  height: 10px;" +
  "  background: #ddd;" +
  "  border: none;" +
  "  border-radius: 3px;" +
"}" +
"input[type=range]::-webkit-slider-thumb {" +
"    -webkit-appearance: none;" +
"    border: none;" +
"    height: 21px;" +
"    width: 21px;" +
"    border-radius: 50%;" +
"    background: goldenrod;" +
"    margin-top: -4px;" +
"}" +
"input[type=range]:focus {" +
"    outline: none;" +
"}" +
"input[type=range]:focus::-webkit-slider-runnable-track {" +
"    background: #ccc;" +
"}" +
"input[type=range]::-moz-range-track {" +
"    width: 300px;" +
"    height: 10px;" +
"    background: #ddd;" +
"    border: none;" +
"    border-radius: 3px;" +
"}" +
"input[type=range]::-moz-range-thumb {" +
"    border: none;" +
"    height: 21px;" +
"    width: 21px;" +
"    border-radius: 50%;" +
"    background: goldenrod;" +
"}" +
"/*hide the outline behind the border*/" +
"input[type=range]:-moz-focusring{" +
"    outline: 1px solid white;" +
"    outline-offset: -1px;" +
"}" +
"input[type=range]::-ms-track {" +
"    width: 300px;" +
"    height: 10px;" +
"    /*remove bg colour from the track, we'll use ms-fill-lower and ms-fill-upper instead */" +
"    background: transparent;" +
"    /*leave room for the larger thumb to overflow with a transparent border */" +
"    border-color: transparent;" +
"    border-width: 6px 0;" +
"    /*remove default tick marks*/" +
"    color: transparent;" +
"}" +
"input[type=range]::-ms-fill-lower {" +
"    background: #777;" +
"    border-radius: 10px;" +
"}" +
"input[type=range]::-ms-fill-upper {" +
"    background: #ddd;" +
"    border-radius: 10px;" +
"}" +
"input[type=range]::-ms-thumb {" +
"    border: none;" +
"    height: 21px;" +
"    width: 21px;" +
"    border-radius: 50%;" +
"    background: goldenrod;" +
"}" +
"input[type=range]:focus::-ms-fill-lower {" +
"    background: #888;" +
"}" +
"input[type=range]:focus::-ms-fill-upper {" +
"    background: #ccc;" +
"}" +
"#text-left {" +
    "display: inline;" +
"}" +
"#text-right {" +
    "display: inline;" +
"}" +
"#radius-slider {" +
"    display: inline;" +
"}" +
"#submit-button {" +
"    display: block;" +
"    float: right;" +
"    right: 0px;" +
"    margin-top: 10px" +
"}" +
"</style>";
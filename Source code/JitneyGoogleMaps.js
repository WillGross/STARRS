var map;

var defaultLat = 44.556;
var defaultLang = -69.646;
var marker;

function initMap() {
    var userLocation = new google.maps.LatLng(44.556, -69.646);

    var map = new google.maps.Map(document.getElementById('map'), {
        center: userLocation,
        zoom: 14
    });

    // Missing a function that gets the actual location of the Jitney.
    var jitneyLocation = new google.maps.LatLng(defaultLat, defaultLang);

    var marker = new google.maps.Marker({
        position: jitneyLocation,
        map: map,
        icon: 'taxi-stand.svg'
    });

    setInterval(function () {
        // Call some function that gets the new Jitney position and returns a LatLng object here
        var newLocation = newJitneyLocation();
        // Change the position of the marker
        marker.setPosition(newLocation);
    }, 100);
}

// (Supposed to) get the new Jitney location from the GPS device in Jitney,
// and then return a new google.maps.LatLng object to relocate the marker.
function newJitneyLocation() {
    defaultLat -= 0.00005;
    defaultLang += 0.0001;
    return new google.maps.LatLng(defaultLat, defaultLang);
}
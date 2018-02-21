
var lat = 44.556;
var lon = -69.646;

function initMap() {
    var userLocation = new google.maps.LatLng(lat, lon);

    var map = new google.maps.Map(document.getElementById('map'), {
        center: userLocation,
        zoom: 14
    });


//global variables for jitney location

    var jitneyLocation = new google.maps.LatLng(lat, lon);

// Add a marker with the taxi-stand.svg as the icon.
// Source: http://map-icons.com/
    var marker = new google.maps.Marker({
        position: jitneyLocation,
        map: map,
        icon: 'taxi-stand.svg'
    });

    newJitneyLocation();
    setInterval(newJitneyLocation, 5000);

    function newJitneyLocation() {
        var newLocation = new google.maps.LatLng(lat, lon);
        marker.setPosition(newLocation);
    }
}
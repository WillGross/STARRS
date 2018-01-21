
var map;
function initMap() {
    var userLocation = new google.maps.LatLng(userLat, userLng);

    var map = new google.maps.Map(document.getElementById('map'), {
        center: userLocation,
        zoom: 14
    });

    // The followings: One pop-up window about the name and location of the stop
    // for each of them.
    var mainStWindow = new google.maps.InfoWindow({
        content: mainStString
    });

    var gilmanStWindow = new google.maps.InfoWindow({
        content: gilmanStString
    });

    var diamondWindow = new google.maps.InfoWindow({
        content: diamondString
    });

    var davisWindow = new google.maps.InfoWindow({
        content: davisString
    });

    // Sets he icon as the shuttle stop markers
    var markerImageAnchorPt = new google.maps.Point(10, 10);

    var markerIcon = {
        url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
        labelOrigin: markerImageAnchorPt
    };

    var mainStMarker = new google.maps.Marker({
        position: mainStLatLng,
        map: map,
        icon: markerIcon,
        label: {
            text: 'M',
            color: '#0000FF',
            fontWeight: 'bold'
        }
    });

    var gilmanStMarker = new google.maps.Marker({
        position: gilmanStLatLng,
        map: map,
        icon: markerIcon,
        label: {
            text: 'G',
            color: '#0000FF',
            fontWeight: 'bold'
        }
    });

    var diamondStMarker = new google.maps.Marker({
        position: diamondLatLng,
        map: map,
        icon: markerIcon,
        label: {
            text: 'D',
            color: '#0000FF',
            fontWeight: 'bold'
        }
    });

    var davisStMarker = new google.maps.Marker({
        position: davisLatLng,
        map: map,
        icon: markerIcon,
        label: {
            text: 'D',
            color: '#0000FF',
            fontWeight: 'bold'
        }
    });

    // The following: Adds event listeners to each marker, so that they can
    // respond to user clicking.
    mainStMarker.addListener('click', function() {
        mainStWindow.open(map, mainStMarker);
    });

    gilmanStMarker.addListener('click', function() {
        gilmanStWindow.open(map, gilmanStMarker);
    });

    diamondStMarker.addListener('click', function() {
        diamondWindow.open(map, diamondStMarker);
    });

    davisStMarker.addListener('click', function() {
        davisWindow.open(map, davisStMarker);
    });

    // Draw an arrow symbol to indicate the shuttle location
    var shuttleSymbol = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        scale: 4,
        fillColor: '#5555FF',
        fillOpacity: 0.7,
        strokeWeight: 2,
        strokeColor: '#000000',
        strokeOpacity: 1
    };

    // Draw the route of the shuttle from the list of locations in GoogleMapsVariables.js
    var shuttlePath = new google.maps.Polyline({
        path: shuttleCoords,
        icons: [{
            icon: shuttleSymbol,
            offset: '100%'
        }],
        geodesic: true,
        strokeColor: '#00FF00',
        strokeOpacity: 0.5,
        strokeWeight: 5
    });
    shuttlePath.setMap(map);

    /*
     * Animates the shuttle marker along the path, but only runs if it's a
     * weekday, and it's during operation time (7:45 am -- 6:00 pm).
     * Test code: Substitute line with "var  d = new Date()" with the following:
            var d = new Date(2017, 10, 22, 7, 44, 59, 0);
     * Notice: The following functions have to be defined within the initialization
     * method of the map, otherwise bugs would occur. We blame Google for this.
     */
    function animateShuttlePath() {
        // var d = new Date(); // Obtain the current date and time
        // if (isOperationHour(d)) {
        //     // If it's not the operation time right now, double-check after 10 seconds.
        //     setTimeout(animateShuttlePath, 10000);
        // } else {
        //     animateCircle(shuttlePath);
        // }
        if (isRunning) {
            animateCircle(shuttlePath);
        } else {
            // If it's not the operation time right now, double-check after 10 seconds.
            setTimeout(animateShuttlePath, 10000);
        }
    }

    // This method checks if the user has allowed the location query,
    // and if so, displays the user location on the map.
    function addUserLocationMarker() {
        if (geoLocationFunctionOn === 1) {
            // If the user allows access to location, update the userLocation,
            // and add a marker for the location on the map.
            userLocation = new google.maps.LatLng(userLat, userLng);
            var userLocationMarker = new google.maps.Marker({
                position: userLocation,
                map: map
            });
            userLocationMarker.setMap(map);
        } else if (geoLocationFunctionOn === 0) {
            // Keep calling this method until the user allows or blocks the
            // the access to the current location
            setTimeout(addUserLocationMarker, 500);
        }
    }

    animateShuttlePath();
    addUserLocationMarker();
}

// Animates the marker for the shuttle along the predefined route.
// To change speed: adjust speedOfMarker and markerUpdateIntervalMS
// Source: https://developers.google.com/maps/documentation/javascript/examples/overlay-symbol-animate
function animateCircle(line) {
    var updateTimeCount = 0;
    var speedOfMarker = 30;
    var markerUpdateIntervalMS = 200;
    window.setInterval(function() {
        updateTimeCount = (updateTimeCount+1)%(speedOfMarker*100);

        var icons = line.get('icons');
        icons[0].offset = (updateTimeCount/speedOfMarker) + '%';
        line.set('icons', icons);
    }, markerUpdateIntervalMS);
}
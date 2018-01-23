var geoLocationFunctionOn = 0;


// Try to obtain user location & time once the page starts loading
window.onload = function () {
    getPosition();
    updateEstimation();
    setInterval( updateEstimation, 10000 );
};

// Tries to obtain geolocation of user.
// Stores the location in userLat and userLng;
// Stores if it obtained the location successfully in geoLocationFunctionOn
// (0 if no response, 1 if successfully, -1 if failed).
// Code source: http://www.spiceforms.com/blog/use-html5-geolocation-api-detailed-tutorial/
function getPosition() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, function(error) {
            if (error.code === error.PERMISSION_DENIED) {
                geoLocationFunctionOn = -1;
                $("#getLocation").val("Location service unavailable.");
            }
        });
    } else {
        $("#getLocation").val("Location service unavailable.");
        geoLocationFunctionOn = -1;
    }
}
// Records the position into the global variables.
function showPosition(position) {
    userLat = position.coords.latitude;
    userLng = position.coords.longitude;
    $("#getLocation").text("You are at " + userLat + ", " + userLng);
    geoLocationFunctionOn = 1;
}

// The following method first decides if the shuttle is operating.
// If it's operating, then it calls findNextStop() with the current time, so that
// the called function can return the estimated position of the shuttle.
var isRunning = false;
function updateEstimation() {
    // Test code: (Test the test case suite 2 by modifying the date)
    // var day = new Date(2017, 11, 10, 16, 47, 30, 0);
    var time = new Date();
    var day = time.getDay();
    var hour = time.getHours();
    if (day === 0 || day === 6) {
        $("#LocationEstimation").text("Shuttle is not operating today.");
        isRunning = false;
    } else if ( hour < 7 || (hour === 7 && time.getMinutes() < 45) ) {
        $("#LocationEstimation").text("Shuttle hasn't started operating right now.");
        isRunning = false;
    } else if (hour >= 18) {
        $("#LocationEstimation").text("Shuttle has finished operating today.");
        isRunning = false;
    } else {
        isRunning = true;
        $("#LocationEstimation").text(findNextStop(time));
    }
}

function findNextStop(time) {
    var minutes_lapsed = time.getMinutes() % 30;
    switch(true) {
        case minutes_lapsed === 0:
            return "Shuttle has arrived at Davis\
						and wil depart for 173 Main and Appleton St. soon.";
        case minutes_lapsed === 15:
            return "Shuttle has arrived at 173 Main and Appleton St.\
						and wil depart for 21 Gilman St. soon.";
        case minutes_lapsed === 22:
            return "Shuttle has arrived at 21 Gilman St.\
						and wil depart for Diamond soon.";
        case minutes_lapsed === 27:
            return "Shuttle has arrived at Diamond\
						and wil depart for Davis soon.";
        case minutes_lapsed < 15:
            return "Shuttle is heading for 173 Main and Appleton St.\
						and will arrive in "+ (15-minutes_lapsed) +" minutes.";
        case minutes_lapsed < 22:
            return "Shuttle is heading for 21 Gilman St.\
						and will arrive in "+ (22-minutes_lapsed) +" minutes.";
        case minutes_lapsed < 27:
            return "Shuttle is heading for Diamond\
						and will arrive in "+ (27-minutes_lapsed) +" minutes.";
        case minutes_lapsed < 30:
            return "Shuttle is heading for Davis\
						and will arrive in "+ (30-minutes_lapsed) +" minutes.";
    }
}

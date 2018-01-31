"use strict";
/*
* logLocation.js
* Script for the jitney driver page (JitneyDriverPage.php)
* scrapes device location data and updates location table in STARRS db
* Author: Will Gross
* Date: 1/30/18
* */

$(document).ready(function () {
    //calls logLocation every 10 seconds
    pullLocation();
    var cycle=setInterval(pullLocation,5000);


    function pullLocation() {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(sendLocation, function(error) {
                if (error.code === error.PERMISSION_DENIED) {
                    clearInterval(cycle);
                    alert("Location not found, permission denied.");
                }
            });
        } else {
            clearInterval(cycle);
            alert("Location service unavailable.");
        }
    }

    function sendLocation(position) {

        var vehicleIdentifier='T';
        $.ajax("https://starrs.colby.edu/logLocation.php",
            {
                type: "GET",
                data: {vehicleType: vehicleIdentifier},
                dataType: "JSON",
                success: updateLocation,
                failure: showError

            }
        );

    }

    function updateLocation(data) {
        alert("data:" +data);
        lat=data['latitude'];
        lon=data['longitude'];
    }

    function showError() {
        clearInterval(cycle);
        alert("Error with sending location.")
    }
});
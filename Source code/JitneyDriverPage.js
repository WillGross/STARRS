"use strict";
/*
* JitneyDriverPage.js
* Script for the jitney driver page (JitneyDriverPage.php)
* scrapes device location data and updates location table in STARRS db
* Author: Will Gross
* Date: 1/30/18
* */

$(document).ready(function () {
    //calls logLocation every 10 seconds
    logLocation();
    var cycle=setInterval(logLocation,10000);


    function logLocation() {

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

        var latitude=position.coords.latitude;
        var longitude=position.coords.longitude;
        var curTime=position.timestamp;
        //get vehicle name from global
        var vehicleName='TST';
        $.ajax("https://starrs.colby.edu/logLocation.php",
            {
                type: "GET",
                data: {vehicle: vehicleName, lat: latitude, lon: longitude, deviceTime: curTime},
                //for testing
                dataType: "text",
                failure: showError

            }
        );

    }

    function showError() {
        clearInterval(cycle);
        alert("Error with sending location.")
    }
});
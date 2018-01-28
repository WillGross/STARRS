"use strict";

$(document).ready( function () {

    // Implement a generic character counter for the textareas
    $(".requestText").on('input', function () {
        // Get the keyword of the id of the changing textarea, and pass it on
        var idKeyword = this.id.slice(0, -4);
        detectWordChange("#"+idKeyword);
    });

    // Given the start of the id of the changing textarea, find its character amount.
    function detectWordChange(id) {
        var len = $(id+"Text").val().length;
        $(id+"CharLimit").text(len);
    }


    // Check to see that the user hasn't just put blank spaces as the locations
    $("#requestForm").submit(function () {
        var pickup = $("#pickupText").val();
        var dropoff = $("#dropoffText").val();
        // var comment = $("#commentText").val();

        // Function reference: https://stackoverflow.com/a/6603043
        if (/ *[^ ]+ */.test(pickup) && / *[^ ]+ */.test(dropoff)) {
            alert("Jitney pickup request successfully submitted.");
            return true;
        } else {
            alert("Your pickup and dropoff locations cannot be blank!");
            return false;
        }
    });


});

function validateForm() {
    // store inputs to variables
    var name = document.forms["myForm"]["nickname"].value;
    var people = document.forms["myForm"]["people"].value;
    var address = document.forms["myForm"]["address"].value;

    // test for empty blank
    if (name === "" || people === "" || address === ""){
        alert("You must fill all information");
        return false;
    }

    //test for special characters in Nickname
    if (testSpecialCharacter(name)){
        alert("Invalid character in Name");
        return false;
    }

    //test for numerical value in #People
    if (isNaN(people)){
        alert("Please enter a valid number for #People");
        return false;
    }

    //test for special characters in Address
    if (testSpecialCharacter(address)){
        alert("Invalid character in Address");
        return false;
    }


    //confirm/cancel information
    return confirmPickUp(name, address);
}


// function for testing special characters
function testSpecialCharacter(string){
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,<>\/?]+/;
    return format.test(string);
}

//create ok-cancel alert to confirm/cancel pick up request
function confirmPickUp(name,address){
    var message = name + " is going to be picked up at " + address;
    return confirm(message) === true;
}
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

    // If the submit comes from the dispatcher, check to see if the user who called the
    // dispatcher has already had a request already.
    $("#submitRequest").click(function () {
        // alert("?????");
        var names = $(".appearedUser");
        var foundSameName = false;
        $.each(names, function (index, value) {
            if ($(value).text() === $("#usernameText").val()) {
                alert("This user already has a request!");
                foundSameName = true;
                return false;
            }
        });
        if (foundSameName) {
            return false;
        } else {
            return onSubmitRequest();
        }
    });

    // Check to see that the user hasn't just put blank spaces as the locations
    function onSubmitRequest() {
        var pickup = $("#pickupText").val();
        var dropoff = $("#dropoffText").val();
        // var comment = $("#commentText").val();

        // Function reference: https://stackoverflow.com/a/6603043
        if (/ *[^ ]+ */.test(pickup) && / *[^ ]+ */.test(dropoff)) {
            var confirmed = confirm("Please confirm the request:"+
                " from "+pickup+" to "+dropoff+"?");
            if (confirmed) {
                alert("Jitney pickup request successfully submitted.");
            }
            return confirmed;
        } else {
            alert("Your pickup and dropoff locations cannot be blank!");
            return false;
        }
    }

    // Pop up a confirmation message when the user presses "Cancel" to cancel a request
    $(".requestCancel").submit(function () {
        var id = this.id.slice(0, -6);
        var confirmed = confirm("Are you sure you want to cancel the request:"+
                " from "+$("#"+id+"pickup").text()+" to "+$("#"+id+"dropoff").text()+"?");
        if (confirmed) {
            alert("Request is cancelled.");
        }
        return confirmed;
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
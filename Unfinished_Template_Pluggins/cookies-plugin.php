<?php
/**
 * Plugin Name: STARRS Cookies
 * Description: Plugin sets up cookies for use in other plugins
 * Version: 1.0
 * Author: Ryan Sellar
 */

add_action( 'init', 'set-up-cookies' );

function set_up_cookies() {
  var authentication = //however you get the users authentication
  var shift-id = //however you get the current shift-id
  setcookie( $v_username, authentication, DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
  setcookie( 'current-shift', shift-id, DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
}

//to access a cookie use $_COOKIE[<cookie name>]
//for example: $_COOKIE[$v_username] gets the cookie assigned to the current username
// $_COOKIE['current-shift'] gets the cookie assigned to the current shift

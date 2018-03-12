<?php
	// Directory header.
	// Handles title changes that need to be performed outisde the loop.
	
	$title = 'xyz';
	add_filter('wp_title', 'assignPageTitle',10,2);

// Set the page title to the event title...
function assignPageTitle(){
  global $title;
  echo $title;
  return $title;
}

	
?>
<?php
global $title;
global $event;

$events = new displayColbyEvents();
$eventArray = '';

if(isset($_GET['rID']) && isset($_GET['bID'])){
	$eventArray = $events->getEventDetails( $_GET['rID'], $_GET['bID'] );
}

if( count( $eventArray )){
	$event = $events->normalizeEMSEvent( $eventArray );
	
	$title = $event->title;
	
	// Check if view set. If so, loading event as iCal
	if(isset($_GET['view'])){
		switch($_GET['view']){
			
			case 'ics':
				// Loading ICS version of event...
				$events->generateICS( $title, $event );					
				exit();
				break;
		}
	}

	
	$postTemplate = true;
}	




?>
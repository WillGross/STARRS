<?php

add_filter(
	'show_admin_bar', function( $bool ) {
		if ( isset( $_GET['print'] ) ) {
			return false;
		}

		return $bool;
	}
);

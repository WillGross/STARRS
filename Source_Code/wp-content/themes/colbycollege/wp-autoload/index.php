<?php

foreach ( glob( __DIR__ . '/hooks/*.php', GLOB_NOSORT ) as $file ) {
	require_once( $file );
}

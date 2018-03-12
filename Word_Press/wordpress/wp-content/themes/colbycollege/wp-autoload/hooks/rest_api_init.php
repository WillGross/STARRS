<?php

function colby_handle_template_rest_request( $request ) {
	$params = $request->get_params();

	if ( empty( $params['template'] ) ) {
		return '';
	}

	$file_path = __DIR__ . "/../../{$params['template']}";

	if ( ! is_readable( $file_path ) ) {
		return '';
	}

	ob_start();

	include $file_path;

	return [
		'content' => ob_get_clean(),
	];
}

function colby_register_template_route() {
	register_rest_route(
		'colby', 'templates', [
			'methods' => 'GET',
			'callback' => 'colby_handle_template_rest_request',
		]
	);
}

add_action( 'rest_api_init', 'colby_register_template_route' );

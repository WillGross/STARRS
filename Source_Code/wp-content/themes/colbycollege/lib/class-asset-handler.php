<?php
/**
 * Modify handle loading of JS/CSS and localization.
 *
 * @package colby-downtown
 */

namespace Colby_College;

/** Add actions and filters and provide their functions. */
class Asset_Handler {
	/**
	 * The array of scripts enqueued by this plugin.
	 *
	 * @var $scripts array
	 */
	public $scripts = [];

	/**
	 * The array of stylesheets enqueued by this plugin.
	 *
	 * @var $stylesheets array
	 */
	public $stylesheets = [];

	/**
	 * Add actions and filters.
	 *
	 * @param object $theme The theme object.
	 */
	public function __construct( &$theme ) {
		$this->theme = $theme;

		add_action( 'init', [ $this, 'disable_emoji' ] );
		add_filter( 'tiny_mce_plugins', [ $this, 'disable_emojicons' ] );
	}

	/** Remove unused feature. */
	public function disable_emoji() {
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	}

	public function disable_emojicons( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
}

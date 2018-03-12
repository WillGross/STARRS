<?php
/**
 * Configuration class
 *
 * @package colby-college
 */

namespace Colby_College;

/**
 * Set variables used throughout the plugin.
 */
class Colby_College {

	/**
	 * The protocol-free URL.
	 *
	 * @var $assets_url string
	 */
	public $assets_url = '';

	/**
	 * Run the plugin in debug mode.
	 *
	 * @var $debug bool
	 */
	public $debug = false;

	/**
	 * The system path to the main directory file.
	 *
	 * @var $main_file string
	 */
	public $main_file = '';

	/**
	 * The string to add to minified asset URLs when not debugging.
	 *
	 * @var $min string
	 */
	public $min = '';

	/**
	 * The system path to the plugin root.
	 *
	 * @var $path string
	 */
	public $path = '';

	/**
	 * The array of post types registered by this plugin.
	 *
	 * @var $post_types array
	 */
	public $post_types = [];

	/**
	 * The array of scripts enqueued by this plugin.
	 *
	 * @var $scripts array
	 */
	public $scripts = [];

	/**
	 * The array of shortcodes added by this plugin.
	 *
	 * @var $shortcodes array.
	 */
	public $shortcodes = [];

	/**
	 * The array of stylesheets enqueued by this plugin.
	 *
	 * @var $stylesheets array
	 */
	public $stylesheets = [];

	/**
	 * The array of taxonomies added by this plugin.
	 *
	 * @var $taxonomies array
	 */
	public $taxonomoies = [];

	/**
	 * The plugin's text domain.
	 *
	 * @var $text_domain string
	 */
	public $text_domain = '';

	/**
	 * The text domain with an underscore instead of a hyphen.
	 *
	 * @var $text_domain_underscore string
	 */
	public $text_domain_underscore = '';

	/**
	 * The plugin directory's root URL.
	 *
	 * @var $url string
	 */
	public $url = '';

	/**
	 * The plugin's version number.
	 *
	 * @var $version string
	 */
	public $version = '0.1';

	/**
	 * Populate the object's variables with real values.
	 *
	 * @param string $main_file The system path of the main plugin file.
	 * @param array  $theme_data The plugin data set in the main file.
	 */
	public function __construct( $main_file, $theme_data ) {
		$this->main_file = $main_file;
		$this->debug = isset( $_GET['debug'] ) ? true : false;
		$this->path = trailingslashit( dirname( $main_file ) );
		$this->url = trailingslashit( get_template_directory_uri() );
		$this->assets_url = substr( $this->url, ( strpos( $this->url, '//' ) ) ) . 'assets/';
		$this->text_domain = $theme_data['Text Domain'];
		$this->text_domain_underscore = str_replace( '-', '_', $this->text_domain );
		$this->version = $theme_data['Version'];
		$this->min = true === $this->debug ? '' : '.min';

		$this->set_theme_supports();
		$this->set_post_types();
		$this->set_taxonomies();
		$this->set_shortcodes();
        $this->set_image_sizes();
	}

	/**
	 * Set an associative array of this plugin's post types -- name => settings.
	 * Example:
	 * $this->post_types = [
	 *		'type' => [
	 *			'label' => 'Types',
	 *			'labels' => [
	 *				'singular_name' => 'Type',
	 *			],
	 *			'public' => true,
	 *			'supports' => [ 'title', 'editor' ],
	 *			'hierarchical' => false,
	 *			'taxonomies' => [ 'type-categories' ],
	 *		],
	 *	];
	 */
	public function set_post_types() {
		$this->post_types = [];

        foreach ( $this->post_types as $name => $settings ) {
            if ( ! post_type_exists( $name ) ) {
    			register_post_type( $name, $settings );
            }
		}
	}

	/**
	 * Set an array of namespaces corresponding to this plugin's shortcode classes.
	 */
	public function set_shortcodes() {
		$this->shortcodes = [];

        foreach ( $this->shortcodes as $class ) {
			$shortcode = new $class();

            if ( ! shortcode_exists( $shortcode->shortcode ) ) {
    			add_shortcode( $shortcode->shortcode, [ $shortcode, 'run' ] );
            }
		}
	}

	/**
	 * Set an array of non-default features to support. Each array corresponds
	 * to the parameters of WordPress's add_theme_support function.
	 */
	public function set_theme_supports() {
		$this->theme_supports = [
			[ 'post-thumbnails', [ 'post', 'page' ] ],
		];

        foreach ( $this->theme_supports as $parameter_array ) {
			call_user_func_array( 'add_theme_support', $parameter_array );
		}
	}

	/** Set an associative array of this plugin's taxonomies -- name => settings
	 * Example:
	 * $this->taxonomies = [
	 *		'type-categories' => [
	 *			'type' => 'type',
	 *			'args' => [
	 *				'label' => 'Type Categories',
	 *				'labels' => [
	 *					'singular_name' => 'Type Category',
	 *				],
	 *				'hierarchical' => true,
	 *			],
	 *		],
	 *	];
	 */
	public function set_taxonomies() {
		$this->taxonomies = [];

        foreach ( $this->taxonomies as $name => $settings ) {
            if ( ! taxonomy_exists() ) {
    			register_taxonomy( $name, $settings['type'], $settings['args'] );
            }
		}
	}

    public function set_image_sizes() {
        $image_sizes = [
            [ 'wpbs-featured', 638, 300, true ],
            [ 'wpbs-featured-home', 970, 311, true ],
            [ 'wpbs-featured-carousel', 970, 400, true ],
            [ 'slideshow-rectangle', 600, 400, true ],
            [ 'slideshow-rectangle-portrait', 153, 230, true ],
            [ 'featured-rectangle-medium', 638, 260, true ],
            [ 'small-rectangle', 165, 110, true ],
        ];

        foreach ( $image_sizes as $image_size ) {
            if ( ! has_image_size( $image_size[0] ) ) {
                call_user_func_array( 'add_image_size', $image_size );
            }
        }
    }
}

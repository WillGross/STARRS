<?php
/**
 * Control code tied to action hooks, or action hooks that point to functions
 *
 * @package ColbyCollege
 */


add_action( 'init', function() {
	include_once( 'shortcodes/class-commencement-top-shortcode.php' );
	include_once( 'shortcodes/class-commencement-left-shortcode.php' );
	include_once( 'shortcodes/class-current-month-shortcode.php' );
	new Commencement_Top_Shortcode();
	new Commencement_Left_Shortcode();
	new Current_Month_Shortcode();
} );



function colby_iframe_shortcode( $atts ) {
	if ( empty( $atts['src'] ) ) {
		return '';
	}
	$src = $atts['src'];
	$width = $atts['width'] ?: '640';
	$height = $atts['height'] ?: '480';
	return "<iframe class=noresize src=$src style=border:0 frameborder=0 width=$width height=$height></iframe>";
}

function colby_html5_audio( $atts ) {
    if ( empty( $atts['src'] ) ) {
		return '';
	}

    return "
        <audio controls=controls>
            <source src={$atts['src']} type=audio/mpeg />
        </audio>";
}

add_action( 'init', function() {
	add_shortcode( 'iframe-shortcode', 'colby_iframe_shortcode' );
    add_shortcode( 'html5-audio', 'colby_html5_audio' );
}, 1 );

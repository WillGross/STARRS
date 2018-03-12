<?php


class Current_Month_Shortcode
{
    public function __construct()
    {
        add_shortcode( 'current-month', [$this, 'draw'] );
    }

    public function draw( $atts, $content )
    {
		$output = strtolower( date( 'F' ) );
		if ( $atts && isset( $atts['year'] ) && '1' === $atts['year'] ) {
			$output .= date( 'Y' );
		}
		return $output;
    }


}
<?php


final class Commencement_Top_Shortcode
{
    public function __construct()
    {
        add_shortcode( 'commencement-top', [$this, 'draw'] );
    }

    public function draw( $atts, $content )
    { ?>

<section class="commencement-top">
    <?php echo wp_kses_post( $content ); ?>

</section><?php
        
    }


}

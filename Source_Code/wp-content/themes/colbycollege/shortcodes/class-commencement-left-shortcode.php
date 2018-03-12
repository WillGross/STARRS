<?php


final class Commencement_Left_Shortcode
{
    public function __construct()
    {
        add_shortcode( 'commencement-left', [$this, 'draw'] );
    }

    public function draw( $atts, $content )
    { ?>

<section class="commencement-left">
   <?php echo apply_filters( 'the_content', $content ); ?>

</section><?php
        
    }


}
<?php



    // Full-width slideshow carousel...
    $show_posts = (of_get_option('slider_options') + 1);

    $catobj = get_category_by_slug('frontpage-slide');

    if(isset($catobj)) {
        $args = array( 'posts_per_page' => absint($show_posts), 'cat'=>$catobj->term_id );
    }
    else {
        $args = array( 'posts_per_page' => absint($show_posts) );
    }

    // Create a new filtering function that will add our where clause to the query
    function filter_where( $where = '' ) {

        if(isset($_GET['passedDate'])){

            $passedDate = date('Y-m-d G:i',strtotime($_GET['passedDate']));

            if(date('G:i',strtotime($passedDate)) == '0:00') {
                $passedDate = date('Y-m-d',strtotime($_GET['passedDate'])) . ' 23:59:59';
            }
        }
        else {
            $passedDate = date('Y-m-d G:i',strtotime("now"));
        }

        $where .= " AND post_date <= '" . $passedDate . "'";

        return $where;
    }

    add_filter( 'posts_where', 'filter_where' );

    $slideQuery = new WP_Query( $args );

    if( !($slideQuery->have_posts()) && isset($_GET['passedDate'])){
        // Date passed, and no posts retrieved. default back to all posts without where.
        $slideQuery = new WP_Query( $args );
    }

    remove_filter( 'posts_where', 'filter_where' );

    $post_num = 0;

    if( $slideQuery->have_posts() ){
?>
<!--[if lte IE 8 ]><style>
#sectionMenu.navbar.container-fluid{
    margin-top:68px!important;
}
</style><![endif]-->
    <div id="myCarousel" class="carousel slide <?php

        if( $slideQuery->post_count == 1 ) {
            echo 'single-slide';
        } ?>">
        <div class="carousel-inner">
            <?php
            $caption_number = 0;
            while ( $slideQuery->have_posts() ) : $slideQuery->the_post();

                $post_num++;
                $post_thumbnail_id = get_post_thumbnail_id();
                $featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
                $readMoreText = 'Read more';

                if(strlen(get_field('more_text'))) {
                    $readMoreText = trim(get_field('more_text'));
                }

                $linkurl = get_field('link_url');

                if(!$linkurl) {
                    $linkurl = get_permalink();
                }

                if(strpos($linkurl,'://')===false && substr( $linkurl, 0, 1 ) != '#' ) {
                    $linkurl = 'http://'.trim($linkurl);
                }

                if(strpos($linkurl,'///')!==false) {
                    $linkurl = str_ireplace('http://','',$linkurl);
                }

                $slidetype = get_field( 'slide_type' );
            ?>
            <div class="<?php
                echo ($post_num == 1) ? 'active ': '';

                echo ( ($slidetype == 'video' && !wp_is_mobile()) ? 'video ' : '');
                ?> item"><?php
                if (get_field('extra_css')) { ?>
                    <style scoped>
                    <?php echo get_field('extra_css'); ?>
                    </style><?php
                } ?>

                <a href="<?php echo $linkurl; ?>" rel="bookmark"><?php

                if( $slidetype == 'image' || $slidetype == '' || wp_is_mobile() ) {

                $thumbnail_src = str_ireplace('http://','//', wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ));
                ?><img src="<?php echo $thumbnail_src[0];?>" alt="<?php the_title_attribute();?>" width="1600" height="850" /><?php
                }
                else {

                    // Video background...
                    $video_webm = get_field( 'header_video_webm' );
                    $video_mp4 = get_field( 'header_video_mp4' );
                    $video_ogv = get_field( 'header_video_ogv' );

                    if( is_array( $video_webm ) &&
                        is_array( $video_mp4 ) &&
                        is_array( $video_ogv ) ) {

                    ?>
                    <video autoplay="true" loop id="bgvid">
                        <source src="<?php echo $video_webm['url']; ?>" type="video/webm">
                        <source src="<?php echo $video_mp4['url']; ?>" type="video/mp4">
                        <source src="<?php echo $video_ogv['url']; ?>" type="video/ogg">
                    </video>
                    <?php

                    }
                }
                ?></a>
            <div class="container">
                <div class="carousel-caption<?php echo (get_field('leftright')) ? ' carousel-' . get_field('leftright') : ''; ?>" id="<?php echo str_replace(' ', '-', strtolower(trim(get_the_title()))); ?>">
                    <h4><a href="<?php echo $linkurl; ?>" rel="bookmark"><?php echo trim(get_the_title()); ?></a></h4>
                    <p><a href="<?php echo $linkurl; ?>" rel="bookmark">
                        <?php
                            $excerpt_length = 142; // length of excerpt to show (in characters)
                            $the_excerpt = nl2br(strip_tags(get_the_content(),'<i>,<b>,<strong>,<em>'));

                            if($the_excerpt != "") {
                                $the_excerpt = substr( $the_excerpt, 0, $excerpt_length );
                                echo trim($the_excerpt);
                        ?></a>
                    </p>
                    <a href="<?php echo $linkurl; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="btn-small btn-primary"><?php echo $readMoreText;?></a>
                        <?php
                            }
                            else {
                                echo '</a>';
                        } ?>
                </div>
            </div>
            </div>

            <?php endwhile; ?>
            <?php
                wp_reset_postdata(); ?>
        </div>

        <a class="carousel-control left" href="javascript:void(0)" data-slide="prev">
            <svg class=chevron>
                <use xlink:href="#chevron-left"></use>
            </svg>
        </a>
        <a class="carousel-control right" href="javascript:void(0)" data-slide="next">
            <svg class=chevron>
                <use xlink:href="#chevron-right"></use>
            </svg>
        </a>
    </div>
<?php
}?>

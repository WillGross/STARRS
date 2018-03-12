<?php

global $post;

?>

<?php
$total = 0;     // Total number of classrooms displayed

$classrooms = '';

while ( have_posts() ) {
        the_post();

	$classrooms .= '<div><a href="/acits/classroom/' . $post->post_name . '/">' . $post->post_title . '</a></div>';
	$total++;
}

if ( $total == 0 ) {
        $classrooms .= 'No classrooms were found.';
}

$this->lcp_output = $classrooms;
?>

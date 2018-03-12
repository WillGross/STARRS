<?php
/*
Template Name: Student Organization Archives
*/
get_header();

wp_register_style( 'studentactivitiescss', get_template_directory_uri().'/library/css/studentactivities.css' );
wp_enqueue_style('studentactivitiescss');
wp_register_script( 'studentactivitiesjs', get_template_directory_uri().'/library/js/studentactivities.js' );
wp_enqueue_script('studentactivitiesjs');
?>
<header><div id="content" class="page-header"><h1 class="page-title">Student Clubs and Organizations</h1></div></header>

<div class=row>
<div class=span2>
<?php
// Get category list
$categories = get_all_category_ids();

$curcategory = isset( $_GET['category'] ) ? $_GET['category'] : '';
$curname = "";

$to_output.= '<div><ul class="nav nav-tabs nav-stacked"><li class="' .
							( $curcategory == '0' || $curcategory == '' ? 'active' : '' ) .
							'" id=category_0><a href=?category=0>All</a></li>';

foreach ( $categories as $cat_id ) { // Output categories as links
	if ( $cat_id == 1 || $cat_id == 14 || $cat_id == 13 )
		continue;
	$cat_name = get_cat_name( $cat_id );
	$to_output .= "<li id=category_$cat_id class=\"" .
								($curcategory==$cat_id?'active':'') .
								"\"><a href=\"?category=$cat_id\">$cat_name</a></li>";

	if ( $curcategory == $cat_id )
		$curname = $cat_name;
}
$to_output .= '</ul></div>';

echo $to_output;

$category=0;

?>
</div>

<div class=span8>
<?php
if ( $curcategory != "" && $curcategory != "0" ) {
	echo "<h3>$curname</h3>";
}
?>
<table class="table table-striped clublinks" style=border-collapse:separate>
<?php

// Begin loop
$args = array( 'post_type' => 'student-organization',
			'orderby' => 'title',
			'order' => 'asc',
			'posts_per_page' => '-1',
			'cat' => $curcategory );
		$clubs = new WP_Query( $args );


		if ( $clubs->have_posts() ) {
			while ( $clubs->have_posts() ) {
				$clubs->the_post();
				$website = get_post_meta( get_the_ID(), 'website',TRUE );
				$faculty = get_post_meta( get_the_ID(), 'faculty_advisor', TRUE );
				$faculty_email = get_post_meta( get_the_ID(), 'faculty_advisor_email', TRUE );
				$student = get_post_meta( get_the_ID(), 'student_advisor', TRUE );
				$student_email = get_post_meta( get_the_ID(), 'student_advisor_email', TRUE );
				$phone = get_post_meta( get_the_ID(), 'office_phone', TRUE );
				?>
					<tr><td>
					<a href=javascript:void(0); class=club-title>
						<?php the_title();?>
					</a>

					<?php
					// wrap content in div
					print( '<div class=content>' );


					// Show faculty advisor
					if ( $faculty != '' ) {
						echo( '<div class=faculty-advisor><span class=label>Faculty Advisor: </span>' );
						// Mailto link for faculty advisor
						if ( $faculty_email != '' ) {
							$to_output = "<a href=mailto:$faculty_email >$faculty </a>";
							echo( $to_output );
						}
						else{
							echo( "$faculty</div>" );
							}
					}


					// Show student contact(s)
					if ( $student != '' ) {
						// Store comma positions for students and emails
						$student_comma = strpos( $student, ',' );
						$email_comma = strpos( $student_email, ',' );
						if ( $student_comma != FALSE ) {
							// If multiple students, store separately
							$student1 = substr( $student, 0, $student_comma );
							$student2 = substr( $student, $student_comma+1 );
							print( '<div class=student-contact><span class=label>Student Contacts:</span>' );
							// Mailto link for student contacts
							if ( $student_email != '' ) {
								if ( $email_comma != FALSE ) {
									// Store emails separately
									$email1 = substr( $student_email, 0, $email_comma );
									$email2 = substr( $student_email, $email_comma+1 );
								} else {
									$email1 = $student_email;
									$email2 = $student_email;
								}
								$to_output="<a href=mailto:$email1>$student1 </a>, <a href=mailto:$email2>$student2</a></div>";
								print( $to_output );
							}
						} else { // If single student
							if ( $student_email != '' ) {
								$to_output = "<div class=student-contact><span class=label>Student Contact:</span><a href=mailto:$student_email>" .
															trim( $student ) .
															'</a></div>';
								print( $to_output );
							}
						}
					}
				 	?>
					 	<div class=club-description>
						<?php the_content(); ?>
					 	</div>
					<?php
					if ( $phone != '' && $phone != '0' ) {
						print( "<div class=office-phone><span class=label>Office Phone:</span> 207-859-$phone</div>" );
					}
					if ( $website != '' ) {
						print( "<div class=office-website><a class=highlight href=$website>Visit website ></a></div>" );
					}
					?>
					</div></td></tr>
				<?php
			}
		}
		?>
		</table>
		</div>
		</div>
<?php get_footer(); ?>

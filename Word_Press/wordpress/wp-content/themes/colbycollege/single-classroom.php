<?php get_header(); ?>

<style>
.cc-label {
	float: left;
	margin-right: 6px;
	font-weight: 500;
}

.cc-accordion {
	margin-top: 22px;
	display: none;
}

.cc-room-info {
	margin-bottom: 34px;
}

#cc-booking-info {
	display: none;
}

#cc-booking-info ul li {
	margin: 6px 0px;
}

#cc-360-image {
	float: right;
	width: 60%;
}

#cc-360-image-caption {
	font-size: small;
	margin-bottom: 34px;
}
</style>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php while ( have_posts() ) : the_post(); ?>
	<h1><?php echo $post->post_title ?></h2>

	<div class="cc-room-info">
		<div id="cc-360-image">
			<?php 
				$sc =  do_shortcode( get_field('photo_of_room') );
				echo str_replace( "http:", "https:", $sc );
			?>
			
			<div id="cc-360-image-caption">
				Click and drag your mouse over the image to see a 360 degree view of this classroom
			</div>
		</div>
	
		<div>
			<div class="cc-label">
				Room Type:
			</div>
			<div>
				<?php echo get_field('room_type'); ?> (<?php echo get_field('department'); ?>) <a style="font-size: small;" href="" onclick="jQuery( '#cc-booking-info' ).dialog( { width: '70%' } );return false;" title="info"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
			</div>
		</div>

		<div>
			<div class="cc-label">Seating:</div>
			<div><?php echo get_field('seating'); ?></div>
		</div>

		<div>
			<div class="cc-label">Technology:</div>
			<div><?php echo str_replace( "Extron", "Touch screen controller", get_field('technologyequipment') ); if ( '' != get_field('disk_drive') ) { echo ', ' . get_field('disk_drive'); } ?></div>
		</div>

		<div>
			<div class="cc-label">Computer System:</div>
			<div><?php if ( get_field('system') ) { echo get_field('system') . ' <a style="font-size: small;" href="" onclick="jQuery( \'.cc-accordion\' ).toggle(500);return false;">details</a>'; } else { echo "None"; } ?></div>
		</div>
	
		<div>
			<div class="cc-label">Phone in room:</div>
			<div><?php if ( get_field('phone_in_room')) { echo get_field('phone_in_room'); } else { echo 'No'; } ?></div>
		</div>

		<div class="cc-accordion">
			<div>
				<div class="cc-label">Computer system CCID</div>
				<div><?php echo get_field('ccid'); ?></div>
			</div>
	
			<div>
				<div class="cc-label">Computer system name</div>
				<div><?php echo get_field('system_name'); ?></div>
			</div>

			<div>
				<div class="cc-label">Computer operating system</div>
				<div><?php echo get_field('operating_system'); ?></div>
			</div>
		</div>
		
		<div id="cc-booking-info" title="Classroom Information">
			<ul>
			<li>Seminar rooms support small, discussion-based courses and are typically used by the nearby department. Seminar classrooms are not usually reservable.</li>
			<li>Technology-Enabled Active Learning (TEAL) classrooms include breakout areas with large monitors for students to work together in groups. Students may connect to the monitors wirelessly to share what is on their computer screens or mobile devices. The classroom furniture features tables on casters that may be easily moved to adjust the amount of available space at various breakout stations.</li>
			<li>Computer Classroom/Labs offer computers loaded with specific software for student academic use.</li>
			<li>Classrooms are equipped with a touchscreen controller and projection system.</li>
			<li>Auditoriums are lecture hall styled rooms with a larger capacity. They frequently have a tiered layout or a pitched floor. All offer a larger projection screen and touchscreen controllers.</li>
			</ul>
		</div>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>

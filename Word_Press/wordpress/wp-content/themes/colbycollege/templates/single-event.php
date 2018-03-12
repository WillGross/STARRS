<?php
	/*
		Post template for events...
	*/

	// Vars...
	global $title;
	global $post;

	$repeats = 0;

	$events = new displayColbyEvents();

if ( isset( $_GET['rID'] ) && isset( $_GET['bID'] ) ) {
	$eventArray = $events->getEventDetails( $_GET['rID'], $_GET['bID'] );
}

if ( count( $eventArray ) ) {
	$event = $events->normalizeEMSEvent( $eventArray );
}

if ( ! isset( $event ) ) {

	// Event isn't set. Check to see if there is an rID, bID combo associated with this event.
	if ( get_field( 'event_information_source' ) == 'EMS' ) {

		if ( get_field( 'ems_event' ) != '' ) {

			$fieldvals = explode( ',', get_field( 'ems_event' ) );

			$event = $events->getEventDetails( $fieldvals[0], $fieldvals[1] );

			$event = $events->normalizeEMSEvent( $event );

		}
	}

	if ( get_field( 'event_information_source' ) == 'Manual' ) {

		$event = $events->normalizeWPEvent( $post );

	}
}

if ( isset( $event ) ) {
	// EMS record...set variables based on returned data.
	$status = trim( $event->status );

	$postPrivacy = 'Public event';

	if ( $status == '2' || $status == '4' || $status == '13' || $status == '18' || $status == 'Private event' ) {
		$postPrivacy = 'Private event';
	}

	if ( $status == '27' || $status == 'Invitation only' ) {
		$postPrivacy = 'Event is by invitation only.';
	}

	if ( $status == '11' || $status == 'Ticketed event' ) {
		$postPrivacy = 'Open to the Colby community only';
	}

	$wpid = '0';

	// Check if WordPress ID set in EMS. If so, grab the post and display that information...
	if ( isset( $eventTemp ) && intval( $eventTemp->wpid ) > 0 && ( $eventTemp->wpid ) != '' && is_numeric( intval( $eventTemp->wpid ) ) ) {

		$post = get_post( intval( $eventTemp->wpid ) );

		if ( $post->post_status != 'trash' && $post->post_status != 'draft' ) {

			$wpid = $eventTemp->wpid;

			$temptitle = get_the_title( $wpid );

			if ( strlen( trim( $temptitle ) ) ) {
				$title = $temptitle;
			}
		} else {
			$wpid = 0;
		}
	}

	// WPID may not be set in WordPress, but may be set from back-end...
	// Check DB for ems_event field that contains RID, BID combo
	$eventOverride = $events->featuredOverrideCheck( $event->rID , $event->bID );

	if ( $eventOverride !== false ) {

		if ( count( $eventOverride ) ) {

			$eventOverride = $eventOverride[0];

			if ( $title != get_the_title() && strlen( $title ) ) {
				$title = $eventOverride->post_title;            // Title is probably already output...
			}

			if ( is_single() || isset( $eventOverride->ID ) ) {

				// Only override if we're viewing the actual featured event - not the EMS post (11/13/2014)
				$wpid = $eventOverride->ID;

				if ( strlen( trim( $eventOverride->post_content ) ) ) {
					$postDescription = $eventOverride->post_content;
					$postDescription = apply_filters( 'the_content', $postDescription );
					$postDescription = str_replace( ']]>', ']]>', $postDescription );
				}
			}
		}
	}
} else {
	// Event field (EMS) not set. Pull the fields for manual WP events...
	if ( is_single() && get_field( 'event_information_source' ) == 'Manual' ) {
		$eventTemp = $events->normalizeWPEvent( $post );
	}
}

if ( is_single() && $wpid <= 0 ) {
	$wpid = $post->ID;
}
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
		<header>
			<div id="eventArea">
			<?php
			if ( strlen( $event->title ) && ! is_single() ) {
			?>
			<div class="page-header">
					<h1 class="single-title" itemprop="headline"><?php echo $event->title; ?></h1>
			</div>
			<?php
			}
				$postTemplate = false;

			?>
			</div>
		</header>
	<div id="eventArea">
	<?php

	if ( has_post_thumbnail( $wpid ) /*&& (is_single() || ( !is_single() && $event->wpid > 0))*/ ) {

		$fullImageURL = wp_get_attachment_image_src( $wpid, 'full' );
		$medImageURL = wp_get_attachment_image_src( $wpid, 'medium' );
		// $thumbCaption = nl2br(get_the_post_thumbnail_caption( get_the_id() ));
		/*
			if(strlen(trim($thumbCaption))) {
			echo '<div class="alignright wp-caption" style="width:'.$medImageURL[1].'px">';
		}*/

		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $wpid ), 'original' );

		echo '<a title="' . $event->title . '" href="' . $large_image_url[0] . '">' . get_the_post_thumbnail(
			$wpid, 'medium',array(
				'class' => 'alignright',
			)
		) . '</a>';

	}
		?>

		<p class="meta<?php echo ($status == 3 || $status == 4) ? ' canceled' : ''; ?>">
									<?php

									if ( is_single() ) {
										// Check for dupes...
										$args = array(
											'cat' => '12',
											'orderby' => 'meta_value_num',
											'meta_key' => 'start_date',
											'order' => 'ASC',
											'posts_per_page' => 150,
											'meta_query' => array(
												'relation' => 'AND',
												array(
													'key' => 'start_date',
													'value' => strtotime( date( 'Y-m-d',strtotime( 'now' ) ) ),
													'type' => 'NUMERIC',
													'compare' => '>=',
												),
												array(
													'key' => 'start_date',
													'value' => array( '' ),
													'compare' => 'NOT IN',
												),
											),
										);

										$eventQuery = new WP_Query( $args );

										$loop = $eventQuery->get_posts();

										$repeats = $events->find_duplicate_events( $loop, $post->post_title );
									}

									if ( strlen( $event->start_date ) && count( $repeats ) <= 1 ) {
								?>
									 <date class=""><?php echo date( 'l, F j, Y', $event->start_date ); ?></date>, <time><?php echo $event->starttime; ?></time>
			<?php
									} else {
										if ( count( $repeats ) == 0 ) {
											header( 'HTTP/1.0 404 Not Found' );
											echo '<date><em>No date/location information could be found for this event. It may need to be added to EMS.</em></date>';
										}
									}

									if ( $status == 3 || $status == 4 ) {
										echo '<div class="error">Event canceled</div>';
									} else {
									?>
								<?php
								 $curID = get_the_ID();

								if ( count( $repeats ) > 1 ) {
									// Set array to store values...
									$sortArray = array();

									foreach ( $repeats as $repeat ) {
										$sortArray[ strtotime( strip_tags( $events->getEventStartDate( $repeat ) ) ) ] = $repeat;
									}

									ksort( $sortArray );  // Sort by date/time
									$curDate = '';

									foreach ( $sortArray as $repeats ) {
										$eventDate = $events->getEventStartDate( $repeats );

										if ( $curDate != date( 'm/d/Y', strtotime( strip_tags( $eventDate ) ) ) ) {

											if ( $curDate != '' ) {
												echo '<br />';
											}

											$curDate = date( 'm/d/Y', strtotime( strip_tags( $eventDate ) ) );
											echo $eventDate;
										} else {
											// Same date. just output the time...
											echo ', ' . $events->formatColbyTime( date( 'g:i a',strtotime( strip_tags( $eventDate ) ) ) );
										}
									}
								}

								echo '<br />';

								 echo $event->location;
								 wp_reset_postdata();
									}
	?>
</p>

<section class="post_content clearfix" itemprop="articleBody">
	<div class="event-description">
	<?php

	// Display content. If there isn't any content, output the description from EMS...
	if ( strlen( trim( strip_tags( $post->post_content ) ) ) || $event->featured == 'yes' && ! isset( $postDescription ) ) {

		if ( isset( $post ) && ! isset( $eventOverride ) ) {
			echo apply_filters( 'the_content', $event->description );
		} else {
			if ( isset( $eventOverride ) && gettype( $eventOverride ) != 'boolean' ) {
				echo apply_filters( 'the_content', $eventOverride->post_content );
			} else {
					echo apply_filters( 'the_content', $event->description );
			}
		}
	} else {
		echo $event->description;

	}
	?>
	</div>
	<hr />
	<?php

	if ( $title == '' ) {
		$title = strip_tags( get_the_title() );
	}
	?>
	<div class="event-privacy"><?php echo $postPrivacy; ?></div>
	<?php
	if ( strlen( $event->start_date ) ) {
		if ( ! strlen( $event->end_date ) ) {
			$event->end_date = $event->start_date + (60 * 60);
		}
	?>
	<div class='event-subscribe'><span>Add event to:</span> <a class="gCal" href="http://www.google.com/calendar/event?action=TEMPLATE&amp;text=<?php echo urlencode( $title ); ?>&amp;dates=<?php echo date( 'Ymd\THi00',strtotime( date( 'm/d/Y',($event->start_date) ) . ' ' . $event->starttime ) ); ?>/<?php echo date( 'Ymd\THi00',strtotime( date( 'm/d/Y g:i a',($event->end_date) ) ) ); ?>&amp;details=<?php echo urlencode( strlen( $event->description ) ? $event->description : 'Colby College' ); ?>&amp;location=<?php echo urlencode( $event->location ); ?>&amp;trp=false&amp;sprop=http%3A%2F%2Fwww.colby.edu%2Fevents&amp;sprop=name:Colby%20College%20Events" target="_blank"><i class="icon-calendar"></i> Google Calendar</a>
																																							<?php
																																							if ( isset( $event ) ) {
																																							?>
																																						 <a class="iCal" href="/events/viewevent/?rID=<?php echo $event->rID; ?>&amp;bID=<?php echo $event->bID; ?>&amp;view=ics"><i class="icon-calendar"></i> iCal</a><?php } ?></div>
	<?php
	}
	if ( ! $events->isAjax() ) {
		?>
		<?php $eventuri = "/events/viewevent/?rID=$event->rID&bID=$event->bID"; ?>
		<div id="shareBottom" class="addthis_toolbox addthis_default_style ">
			<?php
			/*
			?>
			<a href="http://www.addthis.com/bookmark.php" class="addthis_button" style="text-decoration:none;">
			<img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
			width="16" height="16" border="0" alt="Share" data-url="<?php echo "http://".$_SERVER['HTTP_HOST'].str_ireplace('&print=1&ajax=1','',$_SERVER['REQUEST_URI']); ?>"/> Share</a>
			<a class="addthis_button_facebook_like" fb:like:layout="button_count"  data-url="<?php echo "http://".$_SERVER['HTTP_HOST'].str_ireplace('&print=1&ajax=1','',$_SERVER['REQUEST_URI']); ?>"></a>
			<a class="addthis_button_tweet" data-url="<?php echo "http://".$_SERVER['HTTP_HOST'].str_ireplace('&print=1&ajax=1','',$_SERVER['REQUEST_URI']); ?>"></a>
			<?php */
?>
			<a href="http://www.addthis.com/bookmark.php" class="addthis_button" style="text-decoration:none;">
				<img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
				width="16" height="16" border="0" alt="Share" data-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . str_ireplace( '&print=1&ajax=1','',$eventuri ); ?>"/> Share</a>
				<a class="addthis_button_facebook_like" fb:like:layout="button_count"  data-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . str_ireplace( '&print=1&ajax=1','',$eventuri ); ?>"></a>
				<a class="addthis_button_tweet" data-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . str_ireplace( '&print=1&ajax=1','',$eventuri ); ?>"></a>

		</div>
		<!-- eventuri is <?php echo $eventuri; ?> -->
		 <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
		 <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52274afd3385fbef"></script>
	<?php
	} elseif ( is_page() ) {
			echo '</div>';
	}

	?>

	<?php wp_link_pages(); ?>
	</section> <!-- end article section -->

	<footer>

		<?php the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags','bonestheme' ) . ':</span> ', ' ', '</p>' ); ?>

	</footer> <!-- end article footer -->

</article> <!-- end article -->

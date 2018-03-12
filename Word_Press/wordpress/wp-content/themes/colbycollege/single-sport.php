<?php 
	
	global $colbyAthleticsPlugin;
				
	$tag = wp_get_post_tags($post->ID);
	
	$view = 'home';
	
	$pagename = $post->post_name;
	
	if(isset($_GET['view'])) {
		$view = esc_html($_GET['view']);
	}
	
	get_header(); ?>
		
			<div id="content" class="clearfix row-fluid teampage">
			
				<div id="main" class="span8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<?php
						switch( esc_attr( $_GET['view'] ) ){
							case 'coaches':
								// Coaches page...
								$colbyAthleticsPlugin->sportObject->outputCoachInformation();
								
								break;
							
							case 'venue': 
								// Venue pages...
							?>
								<header>
								<section class="post_content clearfix" itemprop="articleBody">
									<?php 

									// Loop through the venues and output...
									
									foreach( $colbyAthleticsPlugin->sportObject->venues as $cat ) {
										
										?><h2><?php echo $cat->post_title;?></h2><?php
										
										echo $cat->post_content;
										echo '<br /><br />';
										echo get_the_post_thumbnail( $cat->ID, 'large',array('class'=>'aligncenter'));	
										
									}
									
									
									?>
								</section><?php
								break;
								
							case 'schedulesandscores':
							?>
							<header>
								
								<div class="page-header">
									<h1 class="page-title">
										Schedules and Scores
									</h1>
								</div>
								<section class="post_content clearfix" itemprop="articleBody">
								<?php
									$season = '';
									
									if(isset($_GET['season'])) {
										$season = $_GET['season'];
									}
									else {
										$season = get_field('year');
									}
									
									$colbyAthleticsPlugin->sportObject->seasonCheck($season);

									echo '<h3>Scoreboard '. $season . '</h3>';
									echo do_shortcode('[athletics-results view="table"]');
									
									echo '<h3>Schedule '. $season . '</h3>';
									echo do_shortcode('[athletics-schedule view="table"]');

								?>	
								</section><?php
								break;
								
							case 'roster':
								// Roster page...
							?>
							<header>
								
								<div class="page-header">
									<h1 class="page-title">Roster</h1>
								</div>
								
								<section class="post_content clearfix" itemprop="articleBody">
									<?php
										$teamPhoto = get_field('team_photo');
										
										if(!empty($teamPhoto)){ ?>
										<img class="team-image" src="<?php echo $teamPhoto['url']; ?>" alt="<?php echo $teamPhoto['alt']; ?>" /><?php
										}
										
										// Grab this sport's seasons...
										$seasons = get_field('season');
										$seasoncode = $colbyAthleticsPlugin->sportObject->getcurrentseasoncode( $seasons );
										
									?>
									<div class="span12">
										<?php $season = get_field('year'); ?>	
											<div><h3><?php echo $season . ' ' . $seasoncode; ?> Roster</h3></div><?php
											
											// Display roster table for the sport...
											echo $colbyAthleticsPlugin->sportObject->outputRoster( $colbyAthleticsPlugin->sportObject->sportslug, $season, $seasoncode);
											?>
										</div>
									<?php ?>
									
								</section><?php
								break;
								
							
							case 'programoverview':
							// Program Overview page... ?>
							
							<header>
								<div class="page-header">
									<h1 class="page-title">
										Program Overview
									</h1>
								</div>
								<section class="post_content clearfix" itemprop="articleBody">
									<?php echo get_field('program_description'); ?>
									
								</section><?php
								break;
							case 'seasonoverview':
							// Season Overview Page...
							?>
								<header>
								
								<div class="page-header">
									<h1 class="page-title">
										Season Overview
									</h1>
								</div>
								<section class="post_content clearfix" itemprop="articleBody">
									<?php
										echo str_replace(array('<div>', '</div>'), array('<p>', '</p>'), get_field('season_overview'));
									?>						
								</section><?php
								break;
						
							case 'recruitinginformation':
							// Recruiting form...
							?>
								<header>
								
								<div class="page-header">
									<h1 class="page-title">
										Recruit Information Form
									</h1>
								</div>
								<section class="post_content clearfix" itemprop="articleBody">
									<?php
										// Output any recruiting form...
										$recruitURL = get_field('recruiting_url');
										if($recruitURL != ''){

											if(stripos($recruitURL,'[gravityform')!==false) {
												echo do_shortcode($recruitURL);
											}
											else {
												echo '<iframe width="100%" frameborder="0" height="1000" src="'.get_field('recruiting_url').'"></iframe>';
											}
											
										}
									?>								
								</section><?php
								break;
							
							case 'statistics':
							
								// Past statistics...
							
							?>
								<header>
								
								<div class="page-header">
									<h1 class="page-title">
										Statistics
									</h1>
								</div>
								<section class="post_content clearfix" itemprop="articleBody">
									<?php
											// Output the stats for the current year for the teams that use stats. Otherwise, output the page from a custom URL if set...
											$season = get_field('year');
											
											// For the following sports that archive their statistics, generate the statics URL and output...
											if( stripos($post->post_name, 'baseball')!==false || 
												stripos($post->post_name, 'football')!==false ||
												stripos($post->post_name, 'basketball')!==false || 
												//stripos($post->post_name, 'hockey')!==false || 
												stripos($post->post_name, 'lacrosse')!==false || 
												stripos($post->post_name, 'softball')!==false ||
												stripos($post->post_name, 'squash')!==false || 
												stripos($post->post_name, 'soccer')!==false || 
												stripos($post->post_name, 'volleyball')!==false) {
												
												// Determine the URL for the folder containing statistics...
												$seasonURL = substr($season,2,2).substr($season,7,2);
												$teamURL = str_replace('womens-','',$post->post_name);
												$teamURL = str_replace('mens-','',$teamURL);
												
												if($post->post_name != 'softball') {
													$teamURL .= '_'.str_replace("'","",strtolower(get_field('gender')[0]));
												}
												
												$teamURL = str_replace("field-hockey_womens","field_hockey",$teamURL);
												$teamURL = str_replace("ice-hockey","hockey",$teamURL);
												
												if( $post->post_name == 'baseball' || $post->post_name == 'football' ) {
													$teamURL = $post->post_name; 
												}
												
												$statsURL = 'http://www.colby.edu/athletics/teams/'.$teamURL.'/'.$seasonURL.'/stats/index.html';
												$statsArray = wp_remote_get($statsURL);
												
												if(stripos($statsArray['body'],'Page not found') !== false) {
													// Page not found. Pull for last year and hope for the best.
													$seasonURL = (substr($seasonURL,0,2)-1) . (substr($seasonURL,2,2)-1);
													$statsURL = 'http://www.colby.edu/athletics/teams/'.$teamURL.'/'.$seasonURL.'/stats/index.html';
													
													$statsArray = wp_remote_get($statsURL);													
													
												}

												echo str_replace('folder.gif','FOLDER.GIF',
																	str_replace('page.gif','PAGE.GIF',
																	str_ireplace('<a href="', ('<a href="http://www.colby.edu/athletics/teams/'.$teamURL.'/'.$seasonURL.'/stats/'), 
																	str_ireplace('<img src="','<img src="http://www.colby.edu/athletics/teams/'.$teamURL.'/'.$seasonURL.'/stats/',$statsArray['body']))));
											}
											else {											
												// Custom statistics page...
												$statsURL = get_field('statistics_page_url');
												if(!empty($statsURL)) {
													echo '<iframe src="'.$statsURL.'" width="100%" height="100%"></iframe>';
												}
											}
									?>
								</section><?php
								break;
							
							case 'records':
							?>
								<header>
								
								<div class="page-header">
									<h1 class="page-title">
									Records
									</h1>
								</div>
								<section class="post_content clearfix" itemprop="articleBody">
									<?php
									$post_object = get_field('records');
									
									if ( count($post_object) > 1 ) {
										?><ul><?php
										foreach ($post_object as $post) {
											echo '<li><a href="?view=records&page='.$post->post_title.'">' . $post->post_title . '</a> ';
											edit_post_link('Edit Record','[',']',$post->ID);
											echo '</li>';	
										}
										?></ul><hr /><?php
										if (isset($_GET['page'])) {
											foreach ($post_object as $post) {
												if ($_GET['page'] === $post->post_title) {
													echo '<h2>'.$post->post_title.'</h2>';
													echo '<div><p>'. $post->post_content .'</p></div>';
												}
											}
										}
									}									
									else if( $post_object ) {
										
										if(count($post_object)) {
											$recordContent = $post_object[0]->post_content;
										}
										
										edit_post_link('Edit Page/Post','[',']',$post_object[0]->ID);
										?>
									    <div>
									    	<p><?php echo $recordContent ?></p>
									    	
									    </div>
									    <?php
									}    
									    ?>			
								</section><?php
								break;
								
							case 'history':
							?>
								<header>
								
								<div class="page-header">
									<h1 class="page-title">
										History
									</h1>
								</div>
								<section class="post_content clearfix" itemprop="articleBody"><?php

									$post_object = get_field('history');
									 
									if( $post_object ): 
									 	// override $post

										edit_post_link('Edit Page/Post','[',']',$post_object->ID);
										
										$historyContent = $post_object->post_content;
									 
										?>
									    <div>
									    	<p><?php echo $historyContent ?></p>
									    </div>
									<?php endif; ?>
									
									
									
															
								</section><?php
								break;
								
							case 'archives':
							?>
								<header>
								
								<div class="page-header">
									<?php
										if(!isset($_GET['season'])){?>
									<h1 class="page-title">
										Archives
									</h1><?php
									}?>									
								</div>
								<section class="post_content clearfix" itemprop="articleBody"><?php	
								
								// If there isn't a year passed, output all past seasons

								if ( isset($_GET['season'])===false ){

									if(strtotime('08/1/'.date('Y',strtotime('now'))) > strtotime('now')){
										// We are past 8/1 for current year. Output next set of years (x-x+1)
										$endYear = date('Y',strtotime('now'));
									}
									else{
										// We are before 8/1 for current year ((x-1)-x
										$endYear = date('Y',strtotime('now'));
									}
									
									echo '<ul>';
									for($i = $endYear;$i > 2000;$i--){
										echo '<li><a href="?view=archives&season='.(($i-1).'-'.$i).'">'.($i-1).'-'.$i.'</a></li>';
									}	
									echo '</ul>';
								} 
								else if(isset($_GET['season'])) {
									// Season was passed in...check to see if this is valid.
									$season = $_GET['season'];
									$colbyAthleticsPlugin->sportObject->seasonCheck($season);
									
									$selecYear = (int)substr($_GET['season'], 0, 4);

									if((!is_numeric($selecYear) || empty($selecYear) || $selecYear < 2000 || $selecYear > 2080 || strlen($season) < 9) && $season !='all') {
										exit();
									}
									
									if($season != 'all') {
										if(isset($_GET['type']))
											echo '<div class="breadcrumb"><a href="?view=archives&season='.$season.'">&laquo; ' . $season . ' Archives</a></div><hr>';
										else
											echo '<div class="breadcrumb"><a href="?view=archives">&laquo; Return to '. $colbyAthleticsPlugin->sportObject->sportname .' Archives</a></div>';
										
										$pagetitle = $season;

										if(isset($_GET['type'])) {
											switch($_GET['type']) {
												case 'news':
													$pagetitle .= ' News Archives ';
													break;
												case 'roster':
													$pagetitle .= ' Roster ';
													break;
												case 'schedulesandscores':
													$pagetitle .= ' Schedule and Scores ';
													break;
												default:
													$pagetitle .= ' Archives ';
											}
										}
										else{										
											$pagetitle .= " Archives ";
										}
									}
									else {
										if($season == 'all') {
											$pagetitle .= " Recent News ";
										}
									}
									
									echo '<h2>'.$pagetitle.'</h2>';
									
									if(!isset($_GET['type']))
										$_GET['type'] = '';
									
									switch($_GET['type']) {
										case 'news':
											if ($selecYear > 2005 || $season=='all') {

												//pull posts with: athletics news category, sport tag												
												$sportSeason = get_field('season');
												
												// sports that are multiple seasons (tennis and crew)
												if (count($sportSeason) > 1 ) {
													$startMonth = 7;
													$startDay = 1;
													$endMonth = 6;
													$endDay = 30;
													$startYear = $selecYear; 
													$endYear = $selecYear+1;
												}
												
												// Fall sport $startDate = 7/1/YEAR $endDate = 12/31/YEAR
												elseif ($sportSeason[0] === 'Fall') {
													$startMonth = 7;
													$startDay = 1;
													$endMonth = 12;
													$endDay = 31;
													$startYear = $selecYear;
													$endYear = $selecYear;
												}
												
												//Winter sport $startDate = 9/1/YEAR $endDate = 6/30/(YEAR+1)
												elseif ($sportSeason[0] === 'Winter') {
													$startMonth = 9;
													$startDay = 1;
													$endMonth = 6;
													$endDay = 30;
													$startYear = $selecYear; 
													$endYear = $selecYear+1;
												}
												
												// Spring sport $startDate = 1/1/(YEAR+1) $endDate 6/30/(YEAR+1)
												else {
													$startMonth = 1;
													$startDay = 1;
													$endMonth = 6;
													$endDay = 30;
													$startYear = $selecYear+1;
													$endYear = $selecYear+1;
												}
																								
												// Use only categories
													if($season != 'all') {
														$str = array(
															"posts_per_page" =>"2000",
															"category__and" => array(get_cat_ID("Athletics News"), get_cat_ID( $colbyAthleticsPlugin->sportObject->sportname )),
															'date_query' => array(
															array(
																'after'    => array(
																	'year'  => $startYear,
																	'month' => $startMonth,
																	'day'   => $startDay,
																),
																'before'    => array(
																	'year'  => $endYear,
																	'month' => $endMonth,
																	'day'   => $endDay,
																),
																'inclusive' => true,
															),
														));
														
														$queryPosts = new WP_Query($str);
													}
													else {
														
														// Clean up the tags and slugs
														/*
														if($tag[0]->slug == 'alpine-skiing') {
															$tag[0]->slug = 'womens-skiing';
														}
														
														if($tag[0]->slug == 'football') {
															$tag[0]->slug = 'mens-football';
															$tag[0]->name = "Men's Football";
														}
														*/

														$sportCategory = get_cat_ID( $colbyAthleticsPlugin->sportObject->sportname );

														if(empty($sportCategory)) {
															$sportCategory = get_category_by_slug( $colbyAthleticsPlugin->sportObject->sportslug.'-athletics-teams' );	
															$sportCategory = $sportCategory->term_id;
														}

														$str = array(
															"posts_per_page" =>"50",
															"category__and" => array(get_cat_ID('Athletics News'), $sportCategory )
														);
													}

												$queryPosts = new WP_Query($str);
												
												if($queryPosts->post_count == 0) {
													if($season != 'all') {

														$str = array(
															"posts_per_page" =>"2000",
															"category_name" => "athletics-news",
															"tag" => $tag[0]->slug,
															'date_query' => array(
															array(
																'after'    => array(
																	'year'  => $startYear,
																	'month' => $startMonth,
																	'day'   => $startDay,
																),
																'before'    => array(
																	'year'  => $endYear,
																	'month' => $endMonth,
																	'day'   => $endDay,
																),
																'inclusive' => true,
															),
														));
													}
													else {
														$sportCategory = get_category_by_slug( $colbyAthleticsPlugin->sportObject->sportslug );
														
														if(empty($sportCategory)) {
															$sportCategory = get_category_by_slug( $colbyAthleticsPlugin->sportObject->sportslug.'-athletics-teams' );	
														}
			
	
														
														$str = array( 'posts_per_page' => '50',"category_name" => "athletics-news", "tag" => $colbyAthleticsPlugin->sportObject->sportslug );
	
													}
												}

												if($queryPosts->post_count > 0) {
													echo '<ul>';
													while ( $queryPosts->have_posts() ) {
														$queryPosts->the_post();
														echo "<li><h3><a href='" . get_permalink() . "'>" . get_the_title() . '</a> </h3><time>'.get_the_date().'</time><br />';
														echo wp_trim_words(get_the_excerpt(),35);
														echo '<hr /></li>';
													}
													echo '</ul>';
	
													if($season=='all')
														echo '<a href="/athletics/sport/'.$pagename.'/?view=archives">View full archives ></a>';
												}
												else {
													echo '<em>No news was found for this team/season. Please <a href="/athletics/sport/'.$pagename.'/?view=archives">browse the archives for older news</a>.</em>';
												}
												/* Restore original Post Data */
												wp_reset_postdata();
										
											}
											break;	
										case 'roster':
										
										$seasoncode = $colbyAthleticsPlugin->sportObject->getcurrentseasoncode( $seasons );

											// Display roster table for the sport...
											echo $colbyAthleticsPlugin->sportObject->outputRoster( $colbyAthleticsPlugin->sportObject->sportslug, $season, $seasoncode);
											break;
											
										case 'schedulesandscores':
											
											echo '<h3>Scoreboard '. $season . '</h3>';
											echo do_shortcode('[athletics-results view="table" season="'.$season.'"]');
									
											echo '<h3>Schedule '. $season . '</h3>';
											echo do_shortcode('[athletics-schedule view="table" season="'.$season.'"]');
											
											break;
										default:
											//Output the types of archives.
											if ($selecYear > 2005 || $season=='all') {
											?>
											<a href="?view=archives&amp;season=<?php echo $season; ?>&amp;type=news">News</a><br />
											<a href="?view=archives&amp;season=<?php echo $season; ?>&amp;type=schedulesandscores">Schedule/Scores</a><br />
											<?php 
											// Pull in the outlooks...											
											$outlookCats = array(get_cat_ID(($selecYear . "-" . ($selecYear + 1))),get_cat_ID($tag[0]->name));
											$outlookArray = get_posts(array(
																			'category__and' => $outlookCats));
											
											if(count($outlookArray)) {
												foreach($outlookArray as $outlook) {
													echo '<a href="'.get_post_permalink($outlook->ID).'">Season Recap/Outlook</a><br />';
													break;
												}
											}
											
											?>
											<a href="?view=archives&amp;season=<?php echo $season; ?>&amp;type=roster">Roster</a><br /><?php
											// Stats pages are not available for all teams. Output link to stats if exists
											if( $post->post_name == 'baseball' || 
												$post->post_name == 'football' ||
												stripos($post->post_name, 'basketball')!==false || 
												stripos($post->post_name, 'hockey')!==false || 
												stripos($post->post_name, 'lacrosse')!==false || 
												stripos($post->post_name, 'squash')!==false || 
												stripos($post->post_name, 'soccer')!==false || 
												stripos($post->post_name, 'volleyball')!==false) {
												$seasonURL = substr($season,2,2).substr($season,7,2);
												$teamURL = str_replace('womens-','',$post->post_name);
												$teamURL = str_replace('mens-','',$teamURL);
												$teamURL .= '_'.str_replace("'","",strtolower(get_field('gender')[0]));
												
												if( $post->post_name == 'baseball' || $post->post_name == 'football' ) {
													$teamURL = $post->post_name; 
												}
												
												if($teamURL == 'football' && $seasonURL == '0708') {
													$seasonURL = '0708a';
												}
												
												echo '<a href="http://www.colby.edu/athletics/teams/'.$teamURL.'/'.$seasonURL.'/stats/index.html">Stats</a>';
											}
										}
											break;
										
									}
									
									if($selecYear <= 2005) {
										// Pull posts with: athletics archives category, sport tag, year tag}
										
										$archivesCategory = get_category_by_slug('athletics-archives');
										$season = '';
										
										if(isset($_GET['season'])) {
											$season = $_GET['season'];
										}
										
										$colbyAthleticsPlugin->sportObject->seasonCheck($season);
										
										//if have athletics archives category and sport tag
										$str = "posts_per_page=1000&category_name=athletics-archives&tag=" . $tag[0]->slug . "'";
																				
										$archive_posts = get_posts( $str );
										$season_tags = array($season, substr($season, 0, 4));
										
										echo '<ul>';
										foreach( $archive_posts as $archive ){
											
											//displays if has tag of the selected year
											if (has_tag($season_tags, $archive->ID) === true ) {
											
												echo "<li><a href='" . get_permalink($archive->ID) . "'>" . get_the_title($archive->ID) . '</a></li>';
												
											}
										}
										echo '</ul>';
									}		
								}?>
								</section> <?php
								break;
							
							case 'home';
							default:
								//Landing/home page for sport
								?>
								<header>
								
								<section class="post_content clearfix" itemprop="articleBody">

									<div class="row">
										<div>
										<?php
											echo do_shortcode('[athleticsSlideshow abstract = "true" sport="'.$colbyAthleticsPlugin->sportObject->sportname.'"]');
											
											// Get the post ID's that are in the slideshow...
											$args = array( 'post_type' => 'post','category__and' => array(get_cat_ID('Athletics News'),get_cat_ID('Athletics Frontpage Slide'),get_cat_ID($tag[0]->name)), 'posts_per_page' => '3','orderby' => 'date','order' => 'DESC' );
											$slideshowResults = get_posts($args);
											
											$omitArray = array();
											
											foreach($slideshowResults as $slideshowResult) {
												$omitArray[] = $slideshowResult->ID;
											}
										?>
										<div class="span11">
											<h3>Recent Headlines</h3>
											<table class="table table-striped">
											<?php
												
												if ($tag[0]->slug == 'baseball' ) {
													$tag[0]->slug = 'mens-baseball';
													$tag[0]->name = "Men's Baseball";
												}
												
												//finds posts in athletics news category with this sport's tag
												$str = array(
															'category__and' => array(get_cat_ID('Athletics News'),get_cat_ID($tag[0]->name=='Football'?"Men's Football":$tag[0]->name)),
															'posts_per_page' => '3');
												if(count($omitArray)) {
													$str['exclude'] = $omitArray;
												}
												
												$recent_posts = get_posts($str);
												
												if(count($recent_posts) == 0) {
													// Check the tags...
													$str = array(
															"tag" => $tag[0]->slug,
															'posts_per_page' => '3');
													
													$recent_posts = get_posts($str);
												}
												
												
												
												foreach($recent_posts as $post) {
													setup_postdata( $post );
													echo "<tr><td><h3><a href='" . get_permalink(get_the_ID()) . "'>" . get_the_title(get_the_ID()) . '</a></h3>';
													echo "<span class='date'>".date('F j, Y',strtotime(get_the_date()))."</span> ";
													if(get_the_excerpt()) {
														echo ' | '. wp_trim_words(get_the_excerpt(),35) . '</td></tr>';
													}

													
												}
												
												
												wp_reset_query();
											?>
											</table>
											<a href="/athletics/sport/<?php echo $pagename;?>/?view=archives&season=all&type=news">More news ></a>

											<?php
												//finds posts in athletics news category with this sport's tag
												$recentCategory = get_category_by_slug( 'recent-stories' );
												
												$str = array('posts_per_page' => '4',
													  		 'category__and' => array($recentCategory->term_id, get_cat_ID($colbyAthleticsPlugin->sportObject->sportname ) ));
												
												$recent_posts = new WP_Query($str);
												
												if($recent_posts->post_count > 0) {
													?>
													<br /><br />
													<h3>Related Stories</h3>
														<table class="table table-striped"><?php
													while ( $recent_posts->have_posts() ) {
														$recent_posts->the_post();
														echo "<tr><td><h3><a href='" . get_field('url',get_the_ID()) . "' target='_new' >" . get_the_title(get_the_ID()) . '</a></h3>';
														if(get_the_excerpt()) {
															echo ' | '. get_the_excerpt() . '</td></tr>';
														}
													}
													?>
														</table>
													<?php
													
												}
												wp_reset_query();
											
												if(strlen(get_field('featured_video_url'))) {
													echo '<hr /><h3>Featured Video</h3>';
													echo wp_oembed_get( get_field('featured_video_url') );
													if(strlen(get_field('team_youtube_channel_url'))) {
														echo '<a href="'.get_field('team_youtube_channel_url').'">More videos ></a>';	
													}
												}?>
										</div>
											
										</div>									
									</div>							
								</section><?php
						}
						?>						
						<footer>			
							<?php //the_tags('<p class="tags"><span class="tags-title">' . __("Tags","bonestheme") . ':</span> ', ' ', '</p>'); ?>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php comments_template('',true); ?>
					
					<?php endwhile; ?>			
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "bonestheme"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "bonestheme"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
    
				<div id="sidebar1" class="fluid-sidebar sidebar span4" role="complementary">
					<?php
						if(isset($_GET['view']) && $_GET['view'] == 'recruitinginformation') {
							echo '									<a style="max-width: 100%; padding: 2%" href="recruiting/" id="athRecruitingButton"><span class="buttontextsm">Resources for Prospective Athletes&nbsp;></span></a>';
						}	
					?>
					<!-- <h4 class="sportTitle"></h4> -->
					<div class="widget">
						<ul id="team-navigation">
		    				<li class="<?php echo ($view == 'home' ? 'selected': ''); ?>"><a href="/athletics/sport/<?php echo $tag[0]->slug; ?>/"><?php echo $sport_title ?> Home</a></li>	    				
		    				<li class= "<?php echo ($view == 'schedulesandscores' ? 'selected': ''); ?>"><a href="<?php echo get_permalink(); ?>?view=schedulesandscores">Schedules and Scores</a></li>
		    				<li class= "<?php echo ($view == 'coaches' ? 'selected': ''); ?>""><a href="<?php echo get_permalink(); ?>?view=coaches">Coaches</a></li>
		    				<li class= "<?php echo ($view == 'roster' ? 'selected': ''); ?>"><a href="<?php echo get_permalink(); ?>?view=roster">Roster</a></li>
							<?php
		    				if(strlen(get_field('team_youtube_channel_url'))) {
								echo '<li><a href="'.get_field('team_youtube_channel_url').'">Video</a></li>';	
							}
	
		    				$found = false;
							
							if( count( $colbyAthleticsPlugin->sportObject->venues ) ) {
							
								foreach( $colbyAthleticsPlugin->sportObject->venues as $curvenue ) {
									
									$sportcat = $curvenue->post_title;
									$sportcatID = $curvenue->ID;
									break;
								}
							}
							
							wp_reset_postdata();
							

							if(strlen($sportcat)) {
							?>
							<li class= <?php echo ($view == 'venue' ? 'selected': '') ?>><a href="<?php echo get_permalink( $sportcatID ); ?>"><?php echo $sportcat; ?></a></li>
							<?php						 
							}
							
							//href link for these pages will be ?view= and then page name all lower case without spaces
							//example: Program Overview --> programoverview
							$optionalpages = get_field('optional_pages');
							
							foreach ($optionalpages as $page) {
								$trimstring = str_replace(' ', '', strtolower($page));
								$linkURL = get_permalink().'?view='.$trimstring;
								
								if($trimstring=='summercamp') {
									if(strlen(get_field('summer_camps_url')))
										$linkURL = get_field('summer_camps_url');
									else
										continue;
								}
								
								if($page=='Recruiting Information') {
									$page = 'Recruiting Form';
								}
								
								if($page=='Resources') {
									$linkURL = get_field('resources');
								}
								
								if($page=='Additional Menu Item 1') {
									$page = get_field('additional_menu_item_1_text');
									$linkURL = get_field('additional_menu_item_1_url');
								}
								if($page=='Additional Menu Item 2') {
									$page = get_field('additional_menu_item_2_text');
									$linkURL = get_field('additional_menu_item_2_url');
								}
								
								if($page=='Additional Menu Item 3') {
									$page = get_field('additional_menu_item_3_text');
									$linkURL = get_field('additional_menu_item_3_url');
								}
								
								?><li class= <?php echo ($view == $trimstring ? 'selected': '') ?>><a href="<?php echo $linkURL; ?>"><?php echo $page; ?></a></li><?php
								
							} 	
		    				?>
	    				</ul>
					</div>
    				<hr />
    				<?php 
    				if($view=='home') {
	    				outputResultsTabs($sport_title,('/athletics/sport/'. $tag[0]->slug.'/'));
	    			}?>
    			</div>
    			
    			<?php //get_sidebar(); // sidebar 1 ?>
    			
			</div> <!-- end #content -->

<?php get_footer(); 
	
	function outputResultsTabs($sport_title,$sport_url='') {

	?>
	<div class="widget">
		<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	        <li class="active"><a href="#results" data-toggle="tab">Latest Results</a></li>
	        <li><a href="#schedule" data-toggle="tab">Upcoming Events</a></li>
	       
	    </ul>
	    <div id="my-tab-content" class="tab-content">
	        <div class="tab-pane active" id="results">
	           <div class="tab-pane-inner">
	            <?php echo do_shortcode('[athletics-results]'); ?>
	            </div>
				   <a class="full" href="<?php echo $sport_url.'?view=schedulesandscores';?>">More Results</a>
				        </div>
				        <div class="tab-pane" id="schedule">
				  <div class="tab-pane-inner">
				<?php echo do_shortcode('[athletics-schedule count="50" allseasons="true"]');?>
				</div>
				<a class="full" href="<?php echo $sport_url.'?view=schedulesandscores';?>">Full Schedule</a>
	        </div>
	    </div>
	</div>
		<?php
		return false;
	}
	
	
	
	
	
?>
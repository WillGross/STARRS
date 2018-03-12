<?php
	require_once( ABSPATH . '/wp-load.php' ); // Needed for calling WordPress functions...

?>
<div class="topSlideDown" style="display:none;height:auto;">
	<div class="container-fluid well text-center">
		<div id="top-close">
		<a class="close" href="javascript:void(0)" onclick="jQuery('.topSlideDown').slideUp(600);">Close</a>
		</div>
		<div class="tabbable">
			<ul class="nav nav-tabs">
				   <li id="top-search" class="<?php echo isset( $_GET['activeTab'] ) && $_GET['activeTab'] == '1' ? ' active' : ''; ?>"><a href="#tab1" data-toggle="tab">Search Colby.edu</a></li>
				   <li id="top-directory" class="<?php echo isset( $_GET['activeTab'] ) && $_GET['activeTab'] == '2' ? ' active' : ''; ?>"><a href="#tab2" data-toggle="tab">College Directory</a></li>
				   <li id="top-offices" class="<?php echo isset( $_GET['activeTab'] ) && $_GET['activeTab'] == '3' ? ' active' : ''; ?>"><a href="#tab3" data-toggle="tab">Offices and Resources</a></li>
			  </ul>
			<div class="tab-content">
				  <div class="tab-pane<?php echo isset( $_GET['activeTab'] ) && $_GET['activeTab'] == '1' ? ' active in' : ''; ?>" id="tab1">
					<h2 id="search-title" class="page-title">Search</h1>
				  <form class="form-search" action="/search/" method="GET">
					<?php
					  // -- Autocomplete Code --
					  // Create JSON object for typeahead values, pulled from the 'About' site RSS feed for a_z_terms for the site
					  $aboutBlog = $blog_details = get_blog_details( 'about' );

					if ( $aboutBlog ) {
						switch_to_blog( $aboutBlog->blog_id );
					}

					  $jsonValue = '[';

					if ( false === ( $aboutquery = get_transient( 'aboutautocomplete' ) ) ) {
						// Cache to speed up future queries...
						$args = array(
							'post_type' => 'a_z_terms',
							'posts_per_page' => '1000',
							'no_found_rows' => true,
						);

						$aboutquery = new WP_Query( $args );

						set_transient( 'aboutautocomplete', $aboutquery, .25 * HOUR_IN_SECONDS );

					}

					if ( $aboutquery->have_posts() ) {
						while ( $aboutquery->have_posts() ) {
							$aboutquery->the_post();

							if ( $jsonValue != '[' ) {
								$jsonValue .= ',';
							}

							$jsonValue .= "{'TERMURL' : '" . addslashes( get_permalink() ) . "','TERMTEXT' : '" . addslashes( get_the_title() ) . "'}";
						}
					}
					  $jsonValue .= ']';

					restore_current_blog();
					  wp_reset_postdata();
						?>
						<script>
							var searchTerms = <?php echo $jsonValue; ?>;
							var objs = [];
							jQuery(document).ready(function(){
							jQuery('.topSlideDown #tab1 #searchBox').typeahead({
								source: function (query, process) {
									var termarray = [];
									var data = searchTerms;
									jQuery.each(data, function (i, searchTerm) {
										termarray.push(searchTerm.TERMTEXT);
										objs.push(searchTerm);
									});
									process(termarray);
								},
								matcher: function (item) {
									query = this.query;
									for(x=0;x<searchTerms.length;x++){
										if(item == searchTerms[x].TERMTEXT){
											if (searchTerms[x].TERMTEXT.toLowerCase().indexOf(query.toLowerCase()) != -1) {
												return true;
											}
										}
									}
								},
								updater: function (item) {
									for(i=0;i<searchTerms.length;i++){
										if(item == searchTerms[i].TERMTEXT){
											selectedURL = searchTerms[i].TERMURL;

											if(selectedURL.length){
												window.location = selectedURL;
												return item;
											}
											else
												jQuery("#tab1 form").submit();

										}
									}
								}
							})
						});

						</script>

					  <div class="input-append">
						<?php
						if ( preg_match( '/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'] ) ) {
							echo 'Search for: ';
						}
						?>
							<script>
						  </script>
						  <input id="searchBox" type="text" class="input-large span2" placeholder="Search for..." autofocus="autofocus" autocomplete = "off" name="q">
						  <button class="btn" type="submit"><i class="icon-search"></i></button>
							</div>
							<div id="directoryLinks" class="clearfix" style="border-top:0;">
							<a href="/about/a_z_terms/">A-Z Index</a> | <a href="/search/">Advanced Search</a> |  <a href="/academics/majors-minors/">Areas of Study</a>
						</div>
					</form>
				</div>
				<div class="tab-pane<?php echo isset( $_GET['activeTab'] ) && $_GET['activeTab'] == '2' ? ' active in' : ''; ?>" id="tab2">
					<h2>College Directory</h2>
					<form class="form-search" action="/directory/">
					  <div class="input-append">
						  <input id="searchDirectoryBox" type="text" class="input-large span2" placeholder="Enter name, department or title" autofocus="autofocus" name="sq">
						  <button class="btn" type="submit"><i class="icon-search"></i></button>
						</div>
						 <div id="directoryDivisions">
						  <ul>
								<li id="admin"><a href="/directory/?division=ADM">Administration</a></li>
								<li id="human"><a href="/directory/?division=HUM">Humanities</a></li>
								<li id="studies"><a href="/directory/?division=INT">Interdisciplinary Studies</a></li>
								<li id="natural"><a href="/directory/?division=NAT">Natural Sciences</a></li>
								<li id="social"><a href="/directory/?division=SOC">Social Sciences</a></li>
							  </ul>
						</div>
						<div id="directoryLinks" class="clearfix">
							<a href="http://alumni.colby.edu/s/1470/index.aspx?sid=1470&gid=1&pgid=6&cid=41">Alumni Directory</a> | <a href="/campus_cs/security/emergency/">Emergency Contacts</a> | <a href="/contact">Contact Colby College</a>
						</div>
					</form>

				</div>
				<div class="tab-pane<?php echo isset( $_GET['activeTab'] ) && $_GET['activeTab'] == '3' ? ' active in' : ''; ?>" id="tab3">
					<h2>Offices and Resources</h2>
					<div id="Offices-Resources-wrapper">
							<div id="Offices-Resources">
							<div id="Resources-wrapper">
							<div id="Resources">
								<ul id="resources-ul" class="">
									<li><a href="http://my.colby.edu">myColby</a></li>
									<li><a href="http://www.colby.edu/libraries/">Libraries</a></li>
									<li><a href="http://www.colby.edu/map">Campus Map</a></li>
									<li><a href="http://www.colby.edu/diningservices/">Dining Menus</a></li>
									<li><a href="http://email.colby.edu">Webmail</a></li>
									<li><a href="http://www.colby.edu/bookstore">Bookstore</a></li>
									<li><a href="http://www.colby.edu/about/a_z_terms/">A-Z Index</a></li>
								</ul>
								<ul id="offices-ul" class="">
									<?php
									if ( wp_is_mobile() ) {
									?>
									<li>
										<a href="/offices/">All Offices ></a>
									</li>
									<?php
									} else {
									?>

									<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="/offices/">Offices <b class="caret"></b></a>
									<!-- dropdown menu links -->
									<ul class="dropdown-menu">
									  <ul class="dropdown-menucol">
										<!-- col 1 -->
										<li><a href="/administration_cs/">Administrative Offices</a></li>
										<li><a href="http://www.colby.edu/admission/">Admissions</a></li>
										<li><a href="http://www.colby.edu/campuslife/">Campus Life</a></li>
										<li><a href="http://www.colby.edu/careercenter/">Career Center</a></li>
										<li><a href="/administration_cs/dos/index.cfm">Dean of Students</a></li>
									</ul>
									<ul class="dropdown-menucol">
										<!-- col 2 -->
										<li><a href="http://www.colby.edu/provost/">Provost and Dean of Faculty</a></li>
										<li><a href="/administration_cs/healthservices/">Health Services</a></li>
										<li><a href="/administration_cs/humanresources/">Human Resources</a></li>
										<li><a href="/its">Information Technology Services (ITS)</a></li>
										<li><a href="http://www.colby.edu/administration_cs/ir/">Institutional Research</a></li>
										<li><a href="http://www.colby.edu/offcampus/">Off-Campus Study</a></li>
									</ul>
									<ul class="dropdown-menucol">
										<!-- col 3 -->
										<li><a href="http://www.colby.edu/president/">President's Office</a></li>
										<li><a href="http://www.colby.edu/registrar/">Registrar</a></li>
										<li><a href="/administration_cs/scheduling/">Scheduling and Facilities</a></li>
										<li class="divider"></li>
										<li><a href="/offices/">All Offices <span class="more-link">></span></a></li>
									  </ul>
									  </li>
									  </ul>
									<?php } ?>
								  <li><a href="/offices/">Administrative Offices</a></li>
								</ul>
							</div>
							</div>
							<div id="Initiatives-wrapper">
								<div id="Initiatives">
								<h4 id="">Colby Areas of Distinction</h4>
								<ul id="initiatives-col1" class="">
									<li><a href="http://www.colby.edu/centerartshumanities/">Center for Arts and Humanities</a></li>
									<li><a href="http://www.colby.edu/green">Green Colby</a></li>
									<li><a href="http://www.colby.edu/goldfarb/">Goldfarb Center</a></li>
									<li><a href="http://www.colby.edu/januaryprogram/">January Program</a></li>
									<li><a href="http://www.colby.edu/globalengagement/">Global Engagement</a></li>
									<li><a href="http://www.colby.edu/clas/">Liberal Arts Symposium</a></li>
								</ul>

								<ul id="initiatives-col2" class="">
									<li><a href="http://www.colby.edu/museum/">Museum of Art</a></li>
									<li><a href="http://www.colby.edu/research/">Research</a></li>
									<li><a href="/administration_cs/special_programs/">Summer Programs</a></li>
								</ul>
								</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>

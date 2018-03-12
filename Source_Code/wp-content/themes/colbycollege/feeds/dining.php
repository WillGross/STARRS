<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */


header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; 
include (plugins_url('colbyDining').'/colbyDining-plugin.php');
?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_ns' );
	?>
>

<?php 
	$potentialHall = wp_kses( $_GET['menu'], null );
	if( $potentialHall == "dana" || $potentialHall == "Dana" )
		$hall = "dana";
	elseif( $potentialHall == "roberts" || $potentialHall == "Roberts" )
		$hall = "roberts";
	elseif( $potentialHall == "foss" || $potentialHall == "Foss" )
		$hall = "foss";
	elseif( $potentialHall == "spa" || $potentialHall == "Spa" )
		$hall = "spa";
	else
		$hall = "none";
	
	$date = date('m/d/Y',strtotime('now'));

	$whenArray = date2dayCycle($date);
	
	// echo do_shortcode('[menus]');
			
	if( $hall == "none" ){
		$content = "Invalid Dining hall";
	}
	else{
		ob_start();
		
		echo do_shortcode('[menus hall="'.$hall.'" view="standard"]');
		$content = ob_get_contents();
		
		ob_end_clean();		
	}
	
	if($hall == 'spa')
		$hall = 'The Spa';
?>

<channel>
	<title>Colby College Dining Services Daily Menu for <?php echo ucfirst($hall);?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url'); ?></link>
	<description><?php echo $day." Menu for ".ucfirst($hall);?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<?php
	$duration = 'hourly';
	/**
	 * Filter how often to update the RSS feed.
	 *
	 * @since 2.1.0
	 *
	 * @param string $duration The update period.
	 *                         Default 'hourly'. Accepts 'hourly', 'daily', 'weekly', 'monthly', 'yearly'.
	 */
	?>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', $duration ); ?></sy:updatePeriod>
	<?php
	$frequency = '1';
	/**
	 * Filter the RSS update frequency.
	 *
	 * @since 2.1.0
	 *
	 * @param string $frequency An integer passed as a string representing the frequency
	 *                          of RSS updates within the update period. Default '1'.
	 */
	?>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', $frequency ); ?></sy:updateFrequency>
	<?php
	/**
	 * Fires at the end of the RSS2 Feed Header.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_head');

	?>
	<item>
		<title><?php echo "Menu for ". ucfirst($hall); ?></title>
		<link>http://www.colby.edu/diningservices/menus/</link>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', date('Y-m-d H:i:s'), false); ?></pubDate>
		<dc:creator><![CDATA[ Colby College ]]></dc:creator>
		<guid isPermaLink="false">http://www.colby.edu/diningservices/</guid>
		<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
		<wfw:commentRss>http://author.colby.edu/diningservices/menus/feed/</wfw:commentRss>
		<slash:comments>0</slash:comments>
<?php rss_enclosure(); ?>
	<?php
	/**
	 * Fires at the end of each RSS2 feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );
	?>
	</item>
</channel>
</rss>

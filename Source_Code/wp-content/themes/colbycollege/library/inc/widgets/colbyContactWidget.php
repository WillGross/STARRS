<?php
/*
	Colby Contact Information Widget
	---------------------------------
	Description: Displays contact information on the side of a site. Information is pulled from the theme options.
	Author: Ben Greeley (bgreeley@colby.edu)
	
*/
class ColbyContact_Widget extends WP_Widget {

	// Constructor...
	function __construct() {
		parent::__construct(
			'ColbyContact_widget', // Base ID
			'Colby Contact Information', // Name
			array( 'description' => __( 'Displays Colby contact information for site. Customize in "Theme Options".', 'text_domain' ), ) // Args
		);
	}
	
	// Front-end Widget display...
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$hideaddress = $instance['hideaddress'];
		
		echo $args['before_widget'];
		if(empty($title))
			$title = "Contact";
		
		echo $args['before_title'] . $title . $args['after_title'];
		
		// kses allowed HTML
		$ksesargs = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
		    'br'      => array(),
		    
		
		    //links
		    'a'     => array(
		        'href' => array()
		    )
		);
		
		// Grab values form the theme options...
		$contact_mailbox = wp_kses(of_get_option('contact_mailbox'),$ksesargs);
		$contact_office_hours = wp_kses(of_get_option('contact_office_hours'),$ksesargs);
		if(!strlen($contact_mailbox))
			$contact_mailbox = '4000';
			
		$contact_email = wp_kses(of_get_option('contact_email'),$ksesargs);
		$contact_floor = wp_kses(of_get_option('contact_floor'),$ksesargs);
		$contact_phone_extension = wp_kses(of_get_option('contact_phone_extension'),$ksesargs);
		if(!strlen($contact_phone_extension))
			$contact_phone_extension = '4000';
		$contact_fax_extension = wp_kses(of_get_option('contact_fax_extension'),$ksesargs);
		if(!strlen($hideaddress)){
			if(strlen($contact_office_hours))
				echo 'Office Hours: '.trim($contact_office_hours).'<br />';
			if(strlen($contact_floor))
				echo trim($contact_floor).'<br />';

			echo trim($contact_mailbox).' Mayflower Hill<br />Waterville, Maine 04901<br />';
		}
		if(strlen(trim($contact_phone_extension))==4)
			echo 'P: 207-859-'.trim($contact_phone_extension).'<br />';
		else
			echo 'P: '.trim($contact_phone_extension).'<br />';		// Full phone #
		if(strlen($contact_fax_extension))
			echo 'F: 207-859-'.trim($contact_fax_extension).'<br />';	

		echo '<a href="mailto:'.trim($contact_email).'">'.trim($contact_email).'</a><br />';
		
		echo $args['after_widget'];
	}

	// Back-end Widget form...
	public function form( $instance ) {
		$defaults = array('title' =>'Contact','hideaddress'=>false);
		$instance = wp_parse_args((array) $instance,$defaults);

		if (isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'text_domain'  ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		
		<input class="checkbox" type="checkbox" <?php checked($instance['hideaddress'], true) ?> id="<?php echo $this->get_field_id('hideaddress'); ?>" name="<?php echo $this->get_field_name('hideaddress'); ?>" />
		<label for="<?php echo $this->get_field_id('hideaddress'); ?>"><?php _e(' Hide Address', 'text_domain' ); ?></label>
		
		</p>
		<?php 
	}

	// Widget update...
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['hideaddress'] = $new_instance['hideaddress']!="";
		return $instance;
	}

} // class ColbyContact_Widget


function register_ColbyContact_widget() {
    register_widget( 'ColbyContact_widget' );
}
add_action( 'widgets_init', 'register_ColbyContact_widget' );
?>
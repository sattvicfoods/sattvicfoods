<?php
/*
Plugin Name: WooCommerce Product Navigation
Plugin URI: http://wpbackoffice.com/plugins/woocommerce-product-navigation
Description: Easily enable users to navigate from one product to the next with our next / previous product buttons. Simply activate the plugin and you're done! Then visit the settings page to upload custom images, change the position and format text.
Version: 1.0.0
Author: WP BackOffice
Author URI: http://wpbackoffice.com
License: GPLv2 or later
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Woocommerce_Product_Navigation' ) ) :

class Woocommerce_Product_Navigation {
	
	public $options = array();
	
	public $button_positions = array(
		'before_product' 	=> 'woocommerce_before_single_product',
		'before_summary' 	=> 'woocommerce_before_single_product_summary',
		'product_summary' 	=> 'woocommerce_single_product_summary',
		'after_product' 	=> 'woocommerce_after_single_product',
		'after_summary' 	=> 'woocommerce_after_single_product_summary'
	);
	
	public $defaults = array (		
		'wpn_button_type'		=> 'product_name',
		'wpn_next_text' 		=> 'Next Product',
		'wpn_next_id' 			=> '',
		'wpn_previous_text'		=> 'Previous Product',
		'wpn_previous_id' 		=> '',
		'wpn_position'			=> 'product_summary',
		'wpn_position_priority' => 10,
		'wpn_custom_class'		=> '',
		'wpn_display_buttons' 	=> ''
	);
	
	public $button_position = 'woocommerce_single_product_summary';
	public $button_priority = 10;
	
	public function __construct() {

		// Retreive plugin options
		$this->options = get_option( 'wpn_options' );

		// Activation Hook
		register_activation_hook( __FILE__, array( $this, 'activation_hook' ) );
		
		// Include Required Files
		require_once( 'wpn-settings.php' );
		
		// Add front-end styles
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
		
		// Get the desired positon of buttons from settings
		$this->set_button_position();
		$this->set_button_priority();
		
		if ( isset( $this->options['wpn_display_buttons'] ) and $this->options['wpn_display_buttons'] != 'on' ) {
			// Add Button to Single Product Action
			add_action( $this->button_position, array( $this, 'product_navigation_buttons' ), $this->button_priority );
		}
		
		// Add Shortcode
		add_shortcode( 'product_navigation', array( $this, 'product_navigation_buttons' ) );
		
		// Add Plugin Settings
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", array( $this, 'wpn_settings_links' ) );

		// Clear Options (for testing)
		//delete_option('wpn_options');			
	}

	/*
	*	Add settings link on plugin page
	*/
	public function wpn_settings_links($links) { 
  	  $support_link = '<a href="http://www.wpbackoffice.com">Premium Support</a>'; 
	  array_unshift($links, $support_link); 
	  
	  $docs_link = '<a href="http://www.wpbackoffice.com/plugins/woocommerce-product-navigation">Docs</a>'; 
	  array_unshift($links, $docs_link); 
	  
	  $settings_link = '<a href="/wp-admin/admin.php?page=wpn-settings.php">Settings</a>'; 
	  array_unshift($links, $settings_link); 
	  
	  return $links; 
	}
 
	/*
	*	Adds default option values
	*/	
	public function activation_hook() {

		$options = get_option( 'wpn_options' );

		if ( $options == false or $options == ''  ) {
			$result = add_option( 'wpn_options', $this->defaults );
		} else {
			foreach ( $this->defaults as $key => $val )  {
				if ( !isset( $options[$key] ) ) {
					$options[$key] = $val;
				}		
			}
			update_option( 'wpn_options', $options );
		}		
	}
	
	/*
	*	Include Styles
	*/	
	public function add_styles() {
		
		global $woocommerce;
	
		if ( is_product() ) {
			wp_enqueue_style( 
				'wpn_product_styles', 
				plugins_url( '/assets/css/wpn-product.css', __FILE__ )
			);
		}
	}

	/*
	*	Show the next / previous buttons on the single product page
	*/
	public function product_navigation_buttons() {

		if ( $this->options == false ) {
			return;
		}

		extract( $this->options );
		
		// Get the current url	
		global $post;
		$current_url = get_permalink( $post->ID );
		$next = '';
		$previous = '';
			
		// Get the previous and next product links
		$previous_link = get_permalink(get_adjacent_post(false,'',false)); 
		$next_link = get_permalink(get_adjacent_post(false,'',true));
			
		// Congigure text button type
		if ( $wpn_button_type == 'text' ) {
			
			// Get previous text if it exists, otherwise use default
			if ( isset( $wpn_previous_text ) and $wpn_previous_text != '' ) {
				$previous_text = $wpn_previous_text;
			} else {
				$previous_text = 'Previous Product';
			}
			
			// Get next text if it exists, otherwise use default
			if ( isset( $wpn_next_text ) and $wpn_next_text != '' ) {
				$next_text = $wpn_next_text;
			} else {
				$next_text = 'Next Product';
			}

			// Create the two links provided the product exists
			if ( $next_link != $current_url ) {
				$next = "<a href='" . $next_link . "'>" . $next_text . "</a>";
			}
			if ( $previous_link != $current_url ) {
				$previous = "<a href='" . $previous_link . "'>" . $previous_text . "</a>";
			}
		
		// Configure image button type
		} else if ( $wpn_button_type == 'image' ) {
		
			// If image id exists proceed, otherwise return
			if ( isset( $wpn_previous_id ) and $wpn_previous_id != '' ) {
				$previous_url = wp_get_attachment_url( $wpn_previous_id );
			} else {
				return;
			}
			
			// If image id exists proceed, otherwise return
			if ( isset( $wpn_next_id ) and $wpn_next_id != '' ) {
				$next_url = wp_get_attachment_url( $wpn_next_id );
			} else {
				return;
			}
		
			// Create the two links provided the product exists
			if ( $next_link != $current_url ) {
				$next = "<a href='" . $next_link . "'><img src='" . $next_url . "' alt='Next Product'></a>";
			} 
			if ( $previous_link != $current_url ) {
				$previous = "<a href='" . $previous_link . "'><img src='" . $previous_url . "' alt='Previous Product'></a>";
			}
		
		// Show basic product name
		} else if ( $wpn_button_type == 'product_name' ) {
			
			// Create the two links provided the product exists
			if ( $next_link != $current_url ) {
				$next_text = get_adjacent_post(false,'',true)->post_title;
				$next = "<a href='" . $next_link . "'>" . $next_text . "</a>";
			} 
			if ( $previous_link != $current_url ) {
				$previous_text = get_adjacent_post(false,'',false)->post_title;
				$previous = "<a href='" . $previous_link . "'>" . $previous_text . "</a>";
			}

		// Otherwise the setting doesn't validate so return
		} else {
			return;
		}
				
		// Create HTML Output
		$output  = '<div class="wpn_buttons ' . $wpn_custom_class .'">'; 
		if ( $previous != '' )
			$output .= '<span class="previous"> ' . $previous . '</span>';
		if ( $next != '' )
			$output .= '<span class="next">' . $next .'</span>';
		$output .= '</div>';
		
		// Display the final output
		echo $output;
	}

	/*
	*	Returns the correct woocommerce hook for displaying the buttons
	*/
	public function set_button_position() {
		if ( isset( $this->options['wpn_position'] ) and isset( $this->button_positions[$this->options['wpn_position']] ) ) {
			$this->button_position = $this->button_positions[$this->options['wpn_position']];
		} else {
			$this->button_position = 'woocommerce_after_single_product_summary'; 
		}		
	}
	
	/*
	*	Returns the button hook priority
	*/
	public function set_button_priority() {
		
		if ( isset( $this->options['wpn_position_priority'] ) and 
			$this->options['wpn_position_priority'] != '' and
			$this->options['wpn_position_priority'] > 0 ) {
			$this->button_priority = $this->options['wpn_position_priority'];
		}
	}
}

endif;

/*
*	Create a new instance of the plugin
*/
new Woocommerce_Product_Navigation();
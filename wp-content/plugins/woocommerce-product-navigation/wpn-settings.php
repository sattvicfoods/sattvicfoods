<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPN_Settings' ) ) :

class WPN_Settings {
	
	public $page_url = 'admin.php?page=wpn-settings.php';
	
	public function __construct() {
		
		// Add WPN Settings page at the bottom of the woocommerce tab
		add_action( 'admin_menu', array( $this, 'wpn_add_settings_page' ), 100);
		
		// Add Scripts and styles		
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_and_styles' ) );
	}
	
	/*
	*	Adds necessary admin scripts
	*/
	public function admin_scripts_and_styles() {
		
		// Get current screen attributes
		$screen = get_current_screen();
		
		if ( $screen != null and $screen->id == "woocommerce_page_wpn-settings" ) {
			
			// Adds WP Modal Window References			
			wp_enqueue_media();
			
			// Enque the script
			wp_enqueue_script( 'wcb_admin_script',
				plugin_dir_url( __FILE__ ) . 'assets/js/wpn-settings.js',
				array('jquery'), '1.0.0', true
			);
			
			// Add Style
			wp_enqueue_style( 
				'wpn_admin_styles', 
				plugins_url( '/assets/css/wpn-admin.css', __FILE__ )
			);
		}
	}
	
	/*
	* Add Settings Page
	*/
	public function wpn_add_settings_page() {
		
		$slug = add_submenu_page(
			'woocommerce', 
			'Product Navigation', 
			'Product Navigation', 
			'edit_posts', 
			basename(__FILE__), 
			array( $this, 'advanced_rules_page_content')
		);
		
		// Load action, checks for posted form
		add_action( "load-{$slug}", array( $this, 'page_loaded') );
	}
	
	/*
	* 	Processes save settings if applicable and redirect the user 
	*	with a success messsage.
	*/
	public function page_loaded() {

		// Verify Nonce before proceeding
		if ( ! empty( $_POST ) && check_admin_referer( 'wpn_settings' ) ) {
			$this->save_settings();
			$url_parameters = 'updated=true';
			wp_redirect(admin_url( $this->page_url . '&' . $url_parameters));
			exit;
		}
	}
	
	/*
	*	Update the settings based on the post values
	*/
	public function save_settings() {
				
		// Get Settings
		$settings = get_option( 'wpn_options' );
		
		// Validate all fields
		if ( isset( $_POST['wpn_button_type'] ) ) {
			
			if ( $_POST['wpn_button_type'] == 'product_name' ) {
				$settings['wpn_button_type'] = 'product_name';
			} elseif ( $_POST['wpn_button_type'] == 'text' ) {
				$settings['wpn_button_type'] = 'text';
			} elseif ( $_POST['wpn_button_type'] == 'image' ) {
				$settings['wpn_button_type'] = 'image';
			}
		}
		
		if ( $_POST['wpn_position'] != '' ) {
			if ( $_POST['wpn_position'] == 'before_product' ) {
				$settings['wpn_position'] = 'before_product';
			} elseif ( $_POST['wpn_position'] == 'before_summary' ) {
				$settings['wpn_position'] = 'before_summary';
			} elseif ( $_POST['wpn_position'] == 'product_summary' ) {
				$settings['wpn_position'] = 'product_summary';
			} elseif ( $_POST['wpn_position'] == 'after_summary' ) {
				$settings['wpn_position'] = 'after_summary';
			} elseif ( $_POST['wpn_position'] == 'after_product' ) {
			echo "After";
				$settings['wpn_position'] = 'after_product';
			}			
		}

		if ( isset( $_POST['wpn_position_priority'] ) and 
			intval( $_POST['wpn_position_priority'] ) > 0 ) {
			$settings['wpn_position_priority'] = strip_tags ( intval( $_POST['wpn_position_priority'] ) );
		}
		
		if ( isset( $_POST['wpn_next_text'] ) ) {
			$settings['wpn_next_text'] = strip_tags( $_POST['wpn_next_text'] );
		}
		
		if ( isset( $_POST['wpn_previous_text'] ) ) {
			$settings['wpn_previous_text'] = strip_tags( $_POST['wpn_previous_text'] );
		}
		
		if ( isset( $_POST['wpn_custom_class'] ) ) {
			$settings['wpn_custom_class'] = strip_tags( $_POST['wpn_custom_class'] );
		}

		if ( isset( $_POST['wpn_next_id'] ) ) {
			$settings['wpn_next_id'] = strip_tags( $_POST['wpn_next_id'] );
		}
		
		if ( isset( $_POST['wpn_previous_id'] ) ) {
			$settings['wpn_previous_id'] = strip_tags( $_POST['wpn_previous_id'] );
		}

		if ( isset( $_POST['wpn_next_text'] ) ) {
			$settings['wpn_next_text'] = strip_tags( $_POST['wpn_next_text'] );
		}

		if ( isset( $_POST['wpn_display_buttons'] ) and 
			$_POST['wpn_display_buttons'] == 'on' ) {
			$settings['wpn_display_buttons'] = 'on';
		} else {
			$settings['wpn_display_buttons'] = '';
		}

		// Update Settings
		$updated = update_option( 'wpn_options', $settings );
		
	}
	
	/**
	*	Advanced Rules Page Content
	*/
	public function advanced_rules_page_content() {
		
		$options = get_option( 'wpn_options' );
		// var_dump($options);
		
		if ($options != false) {
			extract($options);
		}

		?>
		<h2>WooCommerce Product Navigation Settings</h2>
		
		<?php if ( isset( $_GET['updated'] ) and $_GET['updated'] == 'true' ): ?>
			<div class='updated'>Your Setting have been Updated</div>
		<?php endif; ?> 
		
		<?php $this->wpbo_meta_box(); ?>
		
		<form method="post" action="<?php echo admin_url( $this->page_url ); ?>" id="wpn_settings">
		
			<?php wp_nonce_field( "wpn_settings" ); ?>

			<table class="form-table">
			
				<tr>
					<th>Button Style</th>
					<td>
						<select name="wpn_button_type">
							<option value="product_name" <?php if ( isset( $wpn_button_type ) and $wpn_button_type == 'product_name' ) echo "selected" ?>>Product Names</option>
							<option value="text" <?php if ( isset( $wpn_button_type ) and $wpn_button_type == 'text' ) echo "selected" ?>>Text</option>
							<option value="image" <?php if ( isset( $wpn_button_type ) and $wpn_button_type == 'image' ) echo "selected" ?>>Images</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<th>Button Position</th>
					<td>
						<select name="wpn_position">
							<option value="before_product" <?php if ( isset( $wpn_position ) and $wpn_position == 'before_product' ) echo "selected" ?>>Before Product</option>
							<option value="before_summary" <?php if ( isset( $wpn_position ) and $wpn_position == 'before_summary' ) echo "selected" ?>>Before Product Summary</option>
							<option value="product_summary" <?php if ( isset( $wpn_position ) and $wpn_position == 'product_summary' ) echo "selected" ?>>In Product Summary</option>
							<option value="after_summary" <?php if ( isset( $wpn_position ) and $wpn_position == 'after_summary' ) echo "selected" ?>>After Product Summary</option>
							<option value="after_product" <?php if ( isset( $wpn_position ) and $wpn_position == 'after_product' ) echo "selected" ?>>After Product</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<th>Position Priority</th>
					<td>
						<input type="number" min="1" name="wpn_position_priority" value="<?php if ( isset( $wpn_position_priority ) ) echo $wpn_position_priority ?>">
						<p><i>*Note: Adjust to move buttons relative to other elements in the selected button section. Lower the number to move up, increase to move down, <strong>default is 10.</strong></i></p>
					</td>
				</tr>

				<tr>
					<th>Next Product Text</th>
					<td>
						<input type="text" name="wpn_next_text" value="<?php if ( isset( $wpn_next_text ) ) echo $wpn_next_text ?>">
					</td>
				</tr>
				
				<tr>
					<th>Previous Product Text</th>
					<td>
						<input type="text" name="wpn_previous_text" value="<?php if ( isset( $wpn_previous_text ) ) echo $wpn_previous_text ?>">
					</td>
				</tr>
				
				<tr>
					<th>Next Product Image</th>
					<td>
						<a class='wpn_upload_next_image button' uploader_title='Select File' uploader_button_text='Include File'>Upload File</a>
						<a class='wpn_remove_file button'>Remove File</a>
						<label class='wpn_next_url_label wpn_url_label' ><?php if ( isset( $wpn_next_id ) ) echo basename( wp_get_attachment_url( $wpn_next_id ) ) ?></label>
						
						<img class="wpn_next_img_preview wpn_img_preview" src="<?php if ( isset( $wpn_next_id ) ) echo wp_get_attachment_url( $wpn_next_id ) ?>" />

						<p><i>*Note: Image will display at full size so crop image accordingly.</i></p>
						
						<input type="hidden" class='wpn_next_id wpn_id' name='wpn_next_id' value='<?php if ( isset( $wpn_next_id ) ) echo $wpn_next_id; ?>' />

					</td>
				</tr>
				
				<tr>
					<th>Previous Product Image</th>
					<td>
						<a class='wpn_upload_previous_image button' id="wpn_previous" uploader_title='Select File' uploader_button_text='Include File'>Upload File</a>
						<a class='wpn_remove_file button'>Remove File</a>
						<label class='wpn_previous_url_label wpn_url_label' ><?php if ( isset( $wpn_previous_id ) ) echo basename( wp_get_attachment_url( $wpn_previous_id ) ) ?></label>
						
						<img class="wpn_previous_img_preview wpn_img_preview" src="<?php if ( isset( $wpn_previous_id ) ) echo wp_get_attachment_url( $wpn_previous_id ) ?>" />

						<p><i>*Note: Image will display at full size so crop image accordingly.</i></p>
						
						<input type="hidden" class='wpn_previous_id wpn_id' name='wpn_previous_id' value='<?php if ( $wpn_previous_id != null ) echo $wpn_previous_id; ?>' />

					</td>
				</tr>
				
				<tr>
					<th>Custom CSS Class</th>
					<td>
						<input type="text" name="wpn_custom_class" value="<?php if ( isset( $wpn_custom_class ) ) echo $wpn_custom_class ?>">
					</td>
				</tr>
				
				<tr>
					<th>Disable Button</th>
					<td>
						<input type="checkbox" name="wpn_display_buttons" <?php if ( isset( $wpn_display_buttons ) and $wpn_display_buttons == 'on' ) echo 'checked' ?>>
					</td>
				</tr>
				
				<tr>
					<th>Shortcode</th>
					<td>[product_navigation]</td>
				</tr>
				<tr>
					<th>Developers (in PHP)</th>
					<td>do_shortcode( '[product_navigation]' );</td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
			</p>
		</form>
		<?php	
	}
	
	/*
	*	Shows the WP BackOffice Meta Box
	*/
	public function wpbo_meta_box() {
		?>
		<div class="wpn_wpbo_meta">
			<a href="http://www.wpbackoffice.com">
				<img src="<?php echo plugins_url( '/assets/img/wpbo-logo.png', __FILE__ ) ?>" />
			</a>
			<h2>Provided by <a href="http://www.wpbackoffice.com">WPBackOffice.com</a></h2>
		
			<p>
				<a href="http://wordpress.org/support/view/plugin-reviews/woocommerce-product-navigation">Rate Us</a> |
				<a href="http://wordpress.org/support/plugin/woocommerce-product-navigation">Support</a><br />
				<a href="http://www.wpbackoffice.com">Hosting</a> |
				<a href="http://www.wpbackoffice.com/plugins">Plugins</a><br />
				<a href="http://www.wpbackoffice.com/plugins/woocommerce-product-navigaiton">Documentation</a>
			</p>
		</div>
		<?php 
	}
}

endif;

return new WPN_Settings();

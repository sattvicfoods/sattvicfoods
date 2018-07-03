<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wc_print_notices();
?>
<?php 
if(isset($_GET['login']) && $_GET['login']=='failed') {
			echo '<p class="woo-ma-login-failed woo-ma-error login_error">';
			_e('Login failed, please try again','woocommerce-my-account-widget');
			echo '</p>';
}

if(isset($_GET['register']) && $_GET['register'] == 'true' ) {
	$login_class = '';
	$register_class = 'current';
} else {
	$login_class = 'current';
	$register_class = '';
}
?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
<script type="text/javascript">
jQuery(document).ready(function($){
		$('#tabs ul.tabs_accaunt_login li').click(function(){
			var tab_id_contact = $(this).attr('data-tab');
			$('#tabs ul.tabs_accaunt_login li').removeClass('current');
			$('#tabs .tab-content').removeClass('current');
			$(this).addClass('current');
			$("#"+tab_id_contact).addClass('current'); 
		});
});
</script>

<div class="important_note">
	<p class="icon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></p>
	<p class="text"><span>Important:</span>Login/Registration is not required for Placing an Order</p>
</div>

<div id="tabs">
	<ul class="tabs_accaunt_login">
		<li class="tab-heading <?=$login_class?>" data-tab="login_tab"><a class="tab_link" title="Login" id="tabb_1">Login</a></li>
		<li class="tab-heading <?=$register_class?>" data-tab="register_tab"><a class="tab_link" title="Register" id="tabb_1">Register</a></li>
		<li class="empty"></li>
	</ul>
	
<div class="col2-set" id="customer_login">

<?php endif; ?>
		
		<div id="login_tab" class="tab-content <?=$login_class?>">
		<p class="notice">You can login with you Username</p>
		<form method="post" class="login">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="form-row form-row-wide">
				<label for="username"><?php _e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>
				<i class="fa fa-user-circle-o" aria-hidden="true"></i><input type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
			</p>
			<p class="form-row form-row-wide">
				<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<i class="fa fa-lock" aria-hidden="true"></i><input class="input-text" type="password" name="password" id="password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>
			<!--<p class="checking">
				<label for="rememberme" class="inline">
					<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
				</label>
				<span class="lost_password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
				</span>
			</p>-->
			<p class="form-row form-row-wide">
				<?php wp_nonce_field( 'woocommerce-login' ); ?>
				<input type="submit" class="button" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>" />
			</p>
				
			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>
		<div class="social_login">
			<!--<div class="fb-login-button" data-max-rows="1" data-size="icon" data-show-faces="false" data-auto-logout-link="false"></div>-->
		</div>
		</div>
		
<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
	
		<div id="register_tab" class="tab-content <?=$register_class?>">
		<form method="post" class="register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="form-row form-row-wide">
					<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
					<i class="fa fa-user-circle-o" aria-hidden="true"></i><input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</p>

			<?php endif; ?>

			<p class="form-row form-row-wide">
				<label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
				<i class="fa fa-envelope" aria-hidden="true"></i><input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="form-row form-row-wide">
					<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<i class="fa fa-lock" aria-hidden="true"></i><input type="password" class="input-text" name="password" id="reg_password" />
				</p>

			<?php endif; ?>

			<!-- Spam Trap -->
			<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>
			<?php do_action( 'register_form' ); ?>

			<p class="form-row form-row-wide">
				<?php wp_nonce_field( 'woocommerce-register' ); ?>
				<input type="submit" class="button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>" />
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>
        </div>
<?php endif; ?>
		
</div>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
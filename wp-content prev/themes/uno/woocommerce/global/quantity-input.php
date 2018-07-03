<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/javascript">
    function incrementValue() {
		var value = parseInt(document.getElementById('input_quantity_new').value, 10);
		value = isNaN(value) ? 0 : value;
		value++;
		document.getElementById('input_quantity_new').value = value;
	}
	function subtractValue() {
		var value = parseInt(document.getElementById('input_quantity_new').value, 10);
		value = isNaN(value) ? 0 : value;
		value--;
		document.getElementById('input_quantity_new').value = value;
	}

</script>
<style type="text/css">
	.new_quantity .qty {
		    float: none;
			margin: 0;
			border: 0;
			border-right: 1px solid #e2e5e6;
			border-left: 1px solid #e2e5e6;
			border-radius: 0;
			padding: 5px 0;
			font-size: 0.9em;
	}
	.new_quantity label {
		color: #8a8a8a;
		width: 22%;
		display: inline-block;
	} 
	.single-product .new_quantity .qty::-webkit-inner-spin-button, 
	.single-product .new_quantity .qty::-webkit-outer-spin-button { 
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
		margin: 0; 
	}
	.new_quantity span {cursor:pointer;padding: 0 7px;color: #3d3d3d;}
	.new_quantity p {
		margin: 0;
		display: inline-block;
		padding: 5px 7px;
		border: 1px solid #e2e5e6;
		color: #a6a6a6;
		font-size: .9rem;
		background-color: #f3f5f8;
		border-radius: 5px;
		padding: 0;
	}
	.new_quantity {
		float: none !important;
		border-bottom: 1px solid #e1e1e1;
		margin:0 !important;
	}
	.woocommerce-cart .cart_item .new_quantity label {display:none;}
	.woocommerce-cart .cart_item .new_quantity {
		text-align: center;
		border:none;
	}
</style>
<div class="quantity new_quantity">
	<?php if (is_cart()) { ?>
	<div class="quantity">
		<input type="number" class="input-text qty text" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" size="4" pattern="<?php echo esc_attr( $pattern ); ?>" inputmode="<?php echo esc_attr( $inputmode ); ?>" />
	</div>
	<?php } else { ?>
	<label>Quantity:</label>
	<p>
		<span id="subtract_quantity_new" onclick="subtractValue()">-</span>
		<input type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="input-text qty text" size="4" pattern="<?php echo esc_attr( $pattern ); ?>" inputmode="<?php echo esc_attr( $inputmode ); ?>" id="input_quantity_new" />
		<span id="add_quantity_new" onclick="incrementValue()">+</span>
	</p>
	<?php } ?>
</div>

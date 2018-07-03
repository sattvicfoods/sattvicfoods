<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'WC_Email_Customer_Fail_Order ' ) ) :
/**
 * Factory Failed Order Email
 *
 * An email sent to the factory when a new order is completed.
 *
 * @class 		WC_Failed_Order_Email
 * @version		2.0.0
 * @package		WooCommerce/Classes/Emails
 * @author 		WooThemes
 * @extends 		WC_Email
 */
class WC_Email_Customer_Fail_Order extends WC_Email {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id               = 'customer_failed_order';
		$this->customer_email   = true;
		$this->title            = __( 'Order Failed', 'woocommerce' );
		$this->description      = __( 'This is an order notification sent to customers containing order details after an order is failed.', 'woocommerce' );
		$this->heading          = __( 'Failed Order Notification emails are sent to payment is failed.', 'woocommerce' );
		$this->subject          = __( 'Your {site_title} order receipt from {order_date}', 'woocommerce' );
		$this->template_html    = 'emails/customer-failed-order.php';
		$this->template_plain   = 'emails/plain/customer-failed-order.php';

		// Triggers for this email
		add_action( 'woocommerce_order_status_pending_to_failed_notification', array( $this, 'trigger' ) );
		add_action( 'woocommerce_order_status_on-hold_to_failed_notification', array( $this, 'trigger' ) );

		// Call parent constructor
		parent::__construct();
	}
	/**
	 * Trigger.
	 *
	 * @param int $order_id
	 */
	public function trigger( $order_id ) {

		if ( $order_id ) {
			$this->object       = wc_get_order( $order_id );
			$this->recipient    = $this->object->billing_email;

			$this->find['order-date']      = '{order_date}';
			$this->find['order-number']    = '{order_number}';

			$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $this->object->order_date ) );
			$this->replace['order-number'] = $this->object->get_order_number();
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false,
			'email'			=> $this
		) );
	}

	/**
	 * Get content plain.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true,
			'email'			=> $this
		) );
	}
}

endif;

return new WC_Email_Customer_Fail_Order();
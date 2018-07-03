<?php

/**
 * Class FUE_Addon_Subscriptions_V2
 */
class FUE_Addon_Subscriptions_V2 {

    private static $instance = null;

    public function __construct() {
        self::$instance = $this;
    }

    /**
     * Get an instance of Follow_Up_Emails
     *
     * @return Follow_Up_Emails
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new FUE_Addon_Subscriptions_V2();
        }

        return self::$instance;
    }

    /**
     * Register subscription variables to be replaced
     *
     * @param FUE_Sending_Email_Variables   $var
     * @param array                 $email_data
     * @param FUE_Email             $email
     * @param object                $queue_item
     */
    public function register_variable_replacements( $var, $email_data, $email, $queue_item ) {
        $variables = array(
            'subs_renew_date', 'subs_end_date', 'days_to_renew', 'item_name', 'item_url'
        );

        // use test data if the test flag is set
        if ( isset( $email_data['test'] ) && $email_data['test'] ) {
            $variables = $this->add_test_variable_replacements( $variables, $email_data, $email );
        } else {
            $variables = $this->add_variable_replacements( $variables, $email_data, $queue_item, $email );
        }

        $var->register( $variables );
    }

    /**
     * Scan through the keys of $variables and apply the replacement if one is found
     * @param array     $variables
     * @param array     $email_data
     * @param object    $queue_item
     * @param FUE_Email $email
     * @return array
     */
    protected function add_variable_replacements( $variables, $email_data, $queue_item, $email ) {
        if ( !$queue_item->order_id  ) {
            return $variables;
        }

        $order = WC_FUE_Compatibility::wc_get_order( $queue_item->order_id );

        if ( ! wcs_order_contains_subscription( $order ) ) {
            return $variables;
        }

        if ( empty( $queue_item->meta['subs_key'] ) ) {
            return $variables;
        }

        $subscription_id    = $queue_item->meta['subs_key'];
        $subscription       = wcs_get_subscription( $subscription_id );

        if ( !empty( $queue_item->meta['item_id'] ) ) {
            $item_id = $queue_item->meta['item_id'];
        } else {
            $items   = array_keys( $subscription->get_items() );
            $item_id = current( $items );
        }

        if ( !$item_id ) {
            return $variables;
        }

        $item       = wcs_get_order_item( $item_id, $subscription );
        $renewal    = $subscription->calculate_date( 'next_payment' );

        $renew_date = date( get_option('date_format'), strtotime( $renewal ) );
        $end_date   = $subscription->get_date( 'end' );

        if ( $end_date == 0 ) {
            $end_date = __('Until Cancelled', 'follow_up_emails');
        } else {
            $end_date = date( get_option('date_format'), strtotime( $end_date ) );
        }

        // calc days to renew
        $now    = current_time( 'timestamp' );
        $diff   = strtotime( $renewal ) - $now;
        $days_to_renew = 0;
        if ( $diff > 0 ) {
            $days_to_renew = floor( $diff / 86400 );
        }

        $item_url = FUE_Sending_Mailer::create_email_url(
            $queue_item->id,
            $queue_item->id,
            $email_data['user_id'],
            $email_data['email_to'],
            get_permalink($item_id)
        );

        $variables['subs_renew_date']   = $renew_date;
        $variables['subs_end_date']     = $end_date;
        $variables['days_to_renew']     = $days_to_renew;
        $variables['item_name']         = $item['name'];
        $variables['item_url']          = $item_url;

        return $variables;
    }

    /**
     * Add variable replacements for test emails
     *
     * @param array     $variables
     * @param array     $email_data
     * @param FUE_Email $email
     *
     * @return array
     */
    protected function add_test_variable_replacements( $variables, $email_data, $email ) {
        $variables['subs_renew_date']   = date( get_option('date_format'), time()+86400);
        $variables['subs_end_date']     = date( get_option('date_format'), time()+(86400*7) );
        $variables['days_to_renew']     = 1;
        $variables['item_name']         = 'Test Subscription';

        return $variables;
    }

    /**
     * Trigger subscription emails based on the new status
     *
     * @param WC_Subscription $subscription
     * @param string $new_status
     * @param string $old_status
     */
    public static function trigger_subscription_status_emails( $subscription, $new_status, $old_status ) {

        if ( $new_status == 'active' ) {
            if ( $subscription->suspension_count > 0 ) {
                // reactivated
                self::subscription_reactivated( $subscription );
            }

            // activated
            self::subscription_activated( $subscription );

            self::set_renewal_reminder( $subscription );
            self::set_expiration_reminder( $subscription );
        } else {
            switch ( $new_status ) {

                case 'cancelled':
                    self::subscription_cancelled( $subscription );
                    break;

                case 'expired':
                    self::subscription_expired( $subscription );
                    break;

                case 'on-hold':
                    self::suspended_subscription( $subscription );
                    break;

            }

        }

    }

    /**
     * Fired after a subscription gets activated. All unsent items in the queue
     * with the same subscription key and the subs_cancelled and
     * subs_suspended trigger will get deleted to avoid sending emails
     * with incorrect subscription status
     *
     * @param WC_Subscription $subscription
     */
    public static function subscription_activated( $subscription ) {
        global $wpdb;

        $product_ids = self::get_subscription_product_ids( $subscription );

        array_push( $product_ids, 0 );

        $product_ids =  implode( ',', array_map( 'absint', $product_ids ) );

        if ( $subscription->get_completed_payment_count() > 1 ) {
            $triggers[] = 'subs_renewed';
        }  else {
            $triggers[] = 'subs_activated';
        }

        // delete queued emails with the same product id and the 'subs_cancelled' or 'subs_suspended' trigger
        $rows = $wpdb->get_results("
            SELECT eo.id
            FROM {$wpdb->prefix}followup_email_orders eo, {$wpdb->postmeta} pm
            WHERE eo.is_sent = 0
            AND eo.product_id IN ($product_ids)
            AND eo.email_id = pm.post_id
            AND pm.meta_key = '_interval_type'
            AND (
              pm.meta_value = 'subs_cancelled' OR pm.meta_value = 'subs_suspended'
            )
        ");

        if ( $rows ) {
            foreach ( $rows as $row ) {
                Follow_Up_Emails::instance()->scheduler->delete_item( $row->id );
            }
        }

        // Tell FUE that an email order has been created
        // to stop it from sending storewide emails
        if (! defined('FUE_ORDER_CREATED'))
            define('FUE_ORDER_CREATED', true);

        self::add_to_queue( $subscription->order->id, $triggers, $subscription->id, $subscription->get_user_id() );

    }

    /**
     * Fired after a subscription gets cancelled
     *
     * @param WC_Subscription $subscription
     */
    public static function subscription_cancelled( $subscription ) {
        global $wpdb;

        $order_id       = $subscription->order->id;
        $product_ids    = self::get_subscription_product_ids( $subscription );

        array_push( $product_ids, 0 );

        $product_ids =  implode( ',', array_map( 'absint', $product_ids ) );

        // delete queued emails with the same product id/order id and the following triggers
        $triggers = array(
            'subs_activated', 'subs_renewed', 'subs_reactivated',
            'subs_suspended', 'subs_before_renewal', 'subs_before_expire'
        );
        $sql = $wpdb->prepare("
            SELECT eo.id
            FROM {$wpdb->prefix}followup_email_orders eo, {$wpdb->postmeta} pm
            WHERE eo.is_sent = 0
            AND eo.product_id IN ($product_ids)
            AND eo.order_id = %d
            AND eo.email_id = pm.post_id
            AND pm.meta_key = '_interval_type'
            AND pm.meta_value IN ('". implode( "','", $triggers ) ."')
        ", $order_id);
        $rows = $wpdb->get_results( $sql );

        if ( $rows ) {
            foreach ( $rows as $row ) {
                Follow_Up_Emails::instance()->scheduler->delete_item( $row->id );
            }
        }

        $triggers = array('subs_cancelled');

        // get the user's email address
        $user = new WP_User( $subscription->get_user_id() );

        self::add_to_queue($order_id, $triggers, $subscription->id, $user->user_email);
    }

    /**
     * Fired after a subscription expires.
     *
     * @param WC_Subscription $subscription
     */
    public static function subscription_expired( $subscription ) {

        $order_id       = $subscription->order->id;
        $triggers[]     = 'subs_expired';

        self::add_to_queue($order_id, $triggers, $subscription->id, $subscription->get_user_id());
    }

    /**
     * Fired after a subscription get reactivated
     *
     * @param WC_Subscription $subscription
     */
    public static function subscription_reactivated( $subscription ) {
        global $wpdb;

        $order_id       = $subscription->order->id;
        $product_ids    = self::get_subscription_product_ids( $subscription );

        array_push( $product_ids, 0 );

        $product_ids =  implode( ',', array_map( 'absint', $product_ids ) );

        // delete queued emails with the same product id and the 'subs_cancelled' or 'subs_suspended' trigger
        $rows = $wpdb->get_results("
            SELECT eo.id
            FROM {$wpdb->prefix}followup_email_orders eo, {$wpdb->postmeta} pm
            WHERE eo.is_sent = 0
            AND eo.product_id IN ($product_ids)
            AND eo.email_id = pm.post_id
            AND pm.meta_key = '_interval_type'
            AND (
              pm.meta_value = 'subs_cancelled' OR pm.meta_value = 'subs_suspended'
            )
        " );

        if ( $rows ) {
            foreach ( $rows as $row ) {
                Follow_Up_Emails::instance()->scheduler->delete_item( $row->id );
            }
        }

        $triggers[] = 'subs_reactivated';

        self::add_to_queue( $order_id, $triggers, $subscription->id, $subscription->get_user_id() );
    }

    /**
     * Fired after a subscription gets suspended
     *
     * @param WC_Subscription $subscription
     */
    public static function suspended_subscription( $subscription ) {

        $order_id       = $subscription->order->id;
        $triggers[]     = 'subs_suspended';

        self::add_to_queue( $order_id, $triggers, $subscription->id, $subscription->get_user_id() );

    }

    /**
     * Fires after a renewal order is created to allow admin to
     * send emails after every subscription payment
     *
     * @param WC_Order $renewal_order
     * @param WC_Subscription $subscription
     */
    public static function subscription_renewal_order_created( $renewal_order, $subscription ) {
        $triggers[] = 'subs_renewal_order';

        self::add_to_queue( $subscription->order->id, $triggers, $subscription->id, $subscription->get_user_id() );
    }

    /**
     * Add renewal reminder emails to the queue right after the subscription has been activated
     * @param WC_Subscription $subscription
     */
    public static function set_renewal_reminder( $subscription ) {
        $order_id   = $subscription->order->id;
        $order      = WC_FUE_Compatibility::wc_get_order( $order_id );
        $queued     = array();

        if ( ! wcs_order_contains_subscription( $order ) )
            return;

        $renewal_date = $subscription->get_date( "next_payment" );

        if (! $renewal_date )
            return;

        // convert to local time
        $renewal_timestamp = get_date_from_gmt( $renewal_date, 'U' );

        if ( current_time('timestamp', true) > $renewal_timestamp ) {
            return;
        }

        // look for renewal emails
        $emails = fue_get_emails( 'any', FUE_Email::STATUS_ACTIVE, array(
            'meta_query'    => array(
                array(
                    'key'   => '_interval_type',
                    'value' => 'subs_before_renewal'
                )
            )
        ) );

        if ( count($emails) > 0 ) {
            $product_ids = self::get_subscription_product_ids( $subscription );

            foreach ( $emails as $email ) {
                // product_id filter
                if ( !empty( $email->product_id ) && !in_array( $email->product_id, $product_ids ) ) {
                    continue;
                }

                // look for a possible duplicate item in the queue
                $dupes = Follow_Up_Emails::instance()->scheduler->get_items(array(
                    'email_id'  => $email->id,
                    'is_sent'   => 0,
                    'order_id'  => $order_id,
                    'user_id'   => $subscription->get_user_id()
                ));

                if ( count( $dupes ) > 0 ) {
                    // there already is an unsent queue item for the exact same order
                    continue;
                }

                // add this email to the queue
                $interval   = (int)$email->interval_num;
                $add        = FUE_Sending_Scheduler::get_time_to_add( $interval, $email->interval_duration );
                $send_on    = $renewal_timestamp - $add;

                $insert = array(
                    'user_id'       => $subscription->get_user_id(),
                    'send_on'       => $send_on,
                    'email_id'      => $email->id,
                    'product_id'    => 0,
                    'order_id'      => $order_id
                );

                $insert['meta']['subs_key'] = $subscription->id;

                if ( !is_wp_error( FUE_Sending_Scheduler::queue_email( $insert, $email ) ) ) {
                    $queued[] = $insert;
                }

            }
        }

        if ( count( $queued ) > 0 ) {
            Follow_Up_Emails::instance()->fue_wc->wc_scheduler->add_order_notes_to_queued_emails( $queued );
        }

    }

    /**
     * Set expiration reminder after the subscription gets activated
     *
     * @param WC_Subscription $subscription
     */
    public static function set_expiration_reminder( $subscription ) {
        $order_id   = $subscription->order->id;
        $order      = WC_FUE_Compatibility::wc_get_order( $order_id );
        $queued     = array();

        if ( ! wcs_order_contains_subscription( $order ) )
            return;

        $expiry_date = $subscription->get_date('end');

        if (! $expiry_date )
            return;

        // convert to local time
        $expiry_timestamp = get_date_from_gmt( $expiry_date, 'U' );

        if ( current_time('timestamp', true) > $expiry_timestamp ) {
            return;
        }

        // look for renewal emails
        $emails = fue_get_emails( 'any', FUE_Email::STATUS_ACTIVE, array(
            'meta_query'    => array(
                array(
                    'key'   => '_interval_type',
                    'value' => 'subs_before_expire'
                )
            )
        ) );

        if ( count($emails) > 0 ) {
            $product_ids = self::get_subscription_product_ids( $subscription );
            foreach ( $emails as $email ) {
                // product_id filter
                if ( !empty( $email->product_id ) && !in_array( $email->product_id, $product_ids ) ) {
                    continue;
                }

                // look for a possible duplicate item in the queue
                $dupes = Follow_Up_Emails::instance()->scheduler->get_items(array(
                    'email_id'  => $email->id,
                    'is_sent'   => 0,
                    'order_id'  => $order_id,
                    'user_id'   => $subscription->get_user_id()
                ));

                if ( count( $dupes ) > 0 ) {
                    // there already is an unsent queue item for the exact same order
                    continue;
                }

                // add this email to the queue
                $interval   = (int)$email->interval_num;
                $add        = FUE_Sending_Scheduler::get_time_to_add( $interval, $email->interval_duration );
                $send_on    = $expiry_timestamp - $add;

                $insert = array(
                    'user_id'       => $subscription->get_user_id(),
                    'send_on'       => $send_on,
                    'email_id'      => $email->id,
                    'product_id'    => 0,
                    'order_id'      => $order_id
                );

                $insert['meta']['subs_key'] = $subscription->id;

                if ( !is_wp_error( FUE_Sending_Scheduler::queue_email( $insert, $email ) ) ) {
                    $queued[] = $insert;
                }
            }
        }

        if ( count( $queued ) > 0 ) {
            Follow_Up_Emails::instance()->fue_wc->wc_scheduler->add_order_notes_to_queued_emails( $queued );
        }

    }

    /**
     * Do not send email if the status has changed from the time it was queued
     *
     * @param bool      $skip
     * @param FUE_Email $email
     * @param object    $queue_item
     *
     * @return bool
     */
    public static function skip_sending_if_status_changed( $skip, $email, $queue_item ) {
        if ( isset($queue_item->meta) && !empty($queue_item->meta) ) {

            $meta = maybe_unserialize($queue_item->meta);

            if ( isset($meta['subs_key']) ) {
                $delete         = false;
                $subscription   = wcs_get_subscription( $meta['subs_key'] );

                if ( $subscription ) {

                    if ( $email->interval_type == 'subs_suspended' && $subscription->status != 'on-hold' ) {
                        $delete = true;
                        $skip = true;
                    } elseif ( $email->interval_type == 'subs_expired' && $subscription->status != 'expired' ) {
                        $delete = true;
                        $skip = true;
                    } elseif ( ($email->interval_type == 'subs_activated' || $email->interval_type == 'subs_renewed' || $email->interval_type == 'subs_reactivated') && $subscription->status != 'active' ) {
                        $delete = true;
                        $skip = true;
                    } elseif ( $email->interval_type == 'subs_cancelled' && $subscription->status != 'cancelled' ) {
                        $delete = true;
                        $skip = true;
                    } elseif ( $email->interval_type == 'subs_before_renewal' && $subscription->status != 'active' ) {
                        $delete = true;
                        $skip = true;
                    }

                    if ( $delete ) {
                        Follow_Up_Emails::instance()->scheduler->delete_item( $queue_item->id );
                    }

                } // if ($subscription)
            } // if ( isset($meta['subs_key']) )

        } // if ( isset($email_order->meta) && !empty($email_order->meta) )

        return $skip;

    }

    /**
     * Add email to the queue
     *
     * @param $order_id
     * @param $triggers
     * @param int $subscription_id
     * @param int $user_id
     */
    public static function add_to_queue( $order_id, $triggers, $subscription_id, $user_id = 0 ) {
        $subscription = wcs_get_subscription( $subscription_id );
        $emails = fue_get_emails( 'any', FUE_Email::STATUS_ACTIVE, array(
            'meta_query'    => array(
                array(
                    'key'       => '_interval_type',
                    'value'     => $triggers,
                    'compare'   => 'IN'
                )
            )
        ) );

        foreach ( $emails as $email ) {
            $interval   = (int)$email->interval_num;

            $add            = FUE_Sending_Scheduler::get_time_to_add( $interval, $email->interval_duration );
            $send_on        = current_time('timestamp') + $add;

            foreach ( $subscription->get_items() as $item_id => $item ) {
                if ( $email->product_id > 0 && !in_array( $email->product_id, array( $item['product_id'], $item['variation_id'] ) ) ) {
                    continue;
                }

                $insert = array(
                    'send_on'       => $send_on,
                    'email_id'      => $email->id,
                    'product_id'    => $email->product_id,
                    'order_id'      => $order_id
                );

                $insert['meta']['subs_key'] = $subscription_id;
                $insert['meta']['item_id']  = $item_id;

                if ($user_id) {
                    $user = new WP_User($user_id);
                    $insert['user_id']      = $user_id;
                    $insert['user_email']   = $user->user_email;
                }

                if ( !is_wp_error( FUE_Sending_Scheduler::queue_email( $insert, $email ) ) ) {
                    Follow_Up_Emails::instance()->fue_wc->wc_scheduler->add_order_notes_to_queued_emails( array( $insert ) );
                }
            }

        }

    }

    public static function get_subscription_product_ids( $subscription ) {
        $items = $subscription->get_items();
        $product_ids = array();

        foreach ( $items as $item ) {
            $product_ids[] = $item['product_id'];

            if ( $item['variation_id'] ) {
                $product_ids[] = $item['variation_id'];
            }
        }

        $product_ids = array_unique( $product_ids );

        return $product_ids;
    }

}
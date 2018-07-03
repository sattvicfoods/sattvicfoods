<?php

class FUE_Newsletter {

    /**
     * The number of subscribers found when searching with FUE_Newsletter::get_subscribers()
     * @var int
     */
    public $found_subscribers = 0;

    public function __construct() {}

    /**
     * Get the site hash
     * @return string
     */
    public static function get_site_id() {
        $site_id = get_option( 'fue_newsletter_site_id', false );

        if ( !$site_id ) {
            $site_id = md5( uniqid() );
            update_option( 'fue_newsletter_site_id', $site_id );
        }

        return $site_id;
    }

    /**
     * Get subscribers in the given list.
     * @param array $args
     * @return array
     */
    public function get_subscribers( $args = array() ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        $defaults = array(
            'list'      => false,
            'length'    => -1,
            'page'      => 1,
            'orderby'   => 'date_added',
            'order'     => 'DESC'
        );
        $args = wp_parse_args( $args, $defaults );

        $limit_str = "";

        if ( $args['length'] > 0 ) {
            $start = ( $args['page'] * $args['length'] ) - $args['length'];
            $limit_str = "LIMIT $start, {$args['length']}";
        }

        if ( $args['list'] !== false ) {
            $list_id = $args['list'];

            if ( !is_numeric( $args['list'] ) ) {
                $list_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT id
                    FROM {$wpdb->prefix}followup_subscriber_lists
                    WHERE list_name = %s",
                    $args['list']
                ));
            }

            if ( empty( $args['list'] ) ) {
                $subscribers = $wpdb->get_col(
                    "SELECT SQL_CALC_FOUND_ROWS DISTINCT id
                    FROM {$wpdb->prefix}followup_subscribers s
                    WHERE NOT EXISTS(
                        SELECT *
                        FROM {$wpdb->prefix}followup_subscribers_to_lists s2l
                        WHERE s.id = s2l.subscriber_id
                    )
                    ORDER BY {$args['orderby']} {$args['order']}
                    {$limit_str}"
                );
            } else {
                $subscribers = $wpdb->get_col($wpdb->prepare(
                    "SELECT SQL_CALC_FOUND_ROWS DISTINCT id
                    FROM {$wpdb->prefix}followup_subscribers s, {$wpdb->prefix}followup_subscribers_to_lists s2l
                    WHERE s.id = s2l.subscriber_id
                    AND s2l.list_id = %d
                    ORDER BY {$args['orderby']} {$args['order']}
                    {$limit_str}",
                    $list_id
                ));
            }
        } else {
            $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT id
                    FROM {$wpdb->prefix}followup_subscribers
                    ORDER BY {$args['orderby']} {$args['order']}
                    {$limit_str}";
            $subscribers = $wpdb->get_col( $sql );
        }

        $this->found_subscribers = $wpdb->get_var("SELECT FOUND_ROWS()");

        if ( $subscribers ) {
            foreach ( $subscribers as $idx => $subscriber_id ) {
                $subscribers[ $idx ] = $this->get_subscriber( $subscriber_id );
            }
        }

        return $subscribers;
    }

    /**
     * Get a subscriber using the ID or email
     * @param int|string $term
     * @return array
     */
    public function get_subscriber( $term ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        if ( is_numeric( $term ) ) {
            $row = $wpdb->get_row( $wpdb->prepare( "SELECT id, email, date_added FROM {$wpdb->prefix}followup_subscribers WHERE id = %d", $term ), ARRAY_A );
        } else {
            $row = $wpdb->get_row( $wpdb->prepare( "SELECT id, email, date_added FROM {$wpdb->prefix}followup_subscribers WHERE email = %s", $term ), ARRAY_A );
        }

        if ( $row ) {
            $row['lists'] = $wpdb->get_results($wpdb->prepare(
                "SELECT l.id, l.list_name AS name
                FROM {$wpdb->prefix}followup_subscriber_lists l, {$wpdb->prefix}followup_subscribers_to_lists s2l
                WHERE l.id = s2l.list_id
                AND s2l.subscriber_id = %d",
                $row['id']
            ), ARRAY_A);
        }

        return $row;
    }

    /**
     * Add a subscriber to a specific list
     *
     * @param string $email
     * @param string|array $lists
     *
     * @return int|WP_Error
     */
    public function add_subscriber( $email, $lists = '' ) {
        $email = sanitize_email( $email );

        if ( !is_email( $email ) ) {
            return new WP_Error( 'fue_add_subscriber', __('Please enter a valid email address', 'follow_up_emails') );
        }

        if ( fue_subscriber_email_exists( $email ) ) {
            return new WP_Error( 'fue_add_subscriber', __('The email address is already in use', 'follow_up_emails') );
        }

        $wpdb = Follow_Up_Emails::instance()->wpdb;

        $subscriber = $this->get_subscriber( $email );

        if ( !$subscriber ) {
            $insert = array(
                'email'         => $email,
                'date_added'    => current_time( 'mysql' )
            );
            $wpdb->insert( $wpdb->prefix .'followup_subscribers', $insert );
            $subscriber = $this->get_subscriber( $wpdb->insert_id );
        }

        if ( !empty( $lists ) ) {
            if ( !is_array( $lists ) ) {
                $lists = array( $lists );
            }

            foreach ( $lists as $list ) {
                $this->add_to_list( $subscriber['id'], $list );
            }
        }

        return $subscriber['id'];
    }

    /**
     * Delete a subscriber from the system
     *
     * @param mixed $term ID or email address of the subscriber
     */
    public function remove_subscriber( $term ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;
        $id   = $term;

        if ( is_email( $term ) ) {
            $subscriber = $this->get_subscriber( $term );
            $id = $subscriber['id'];
        }

        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}followup_subscribers_to_lists
            WHERE subscriber_id = %d",
            $id
        ));

        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}followup_subscribers
            WHERE id = %d",
            $id
        ));

    }

    public function remove_from_list( $subscriber, $list = array() ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        if ( is_email( $subscriber ) ) {
            $subscriber = $this->get_subscriber( $subscriber );
            $subscriber_id = $subscriber['id'];
        } else {
            $subscriber_id = $subscriber;
        }

        if ( empty( $list ) ) {
            // remove from all the lists
            $wpdb->query($wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}followup_subscribers_to_lists
                WHERE subscriber_id = %d",
                $subscriber_id
            ));
        } else {
            $lists = implode( ',', array_map( 'esc_sql', $list ) );
            $wpdb->query($wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}followup_subscribers_to_lists
                WHERE subscriber_id = %d
                AND list_id IN ($lists)",
                $subscriber_id
            ));
        }
    }

    public function subscriber_exists( $email ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        $count = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*)
            FROM {$wpdb->prefix}followup_subscribers
            WHERE email = %s",
            sanitize_email( $email )
        ) );

        return ($count > 0);
    }

    /**
     * Get all the lists available
     * @return array
     */
    public function get_lists() {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        return $wpdb->get_results("SELECT DISTINCT * FROM {$wpdb->prefix}followup_subscriber_lists", ARRAY_A);
    }

    /**
     * Add a new list
     *
     * @param string $list
     * @return int The ID of the new list
     */
    public function add_list( $list ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        $list_id = $wpdb->get_var($wpdb->prepare(
            "SELECT id
            FROM {$wpdb->prefix}followup_subscriber_lists
            WHERE list_name = %s",
            $list
        ));

        if ( !$list_id ) {
            $wpdb->insert( $wpdb->prefix . 'followup_subscriber_lists', array('list_name' => $list) );
            $list_id = $wpdb->insert_id;
        }

        return $list_id;
    }

    public function edit_list() {

    }

    public function remove_list() {

    }

    /**
     * Add a subscriber to a list
     *
     * @param mixed $subscriber
     * @param string $list
     */
    public function add_to_list( $subscriber, $list ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        $subscriber_id = $subscriber;

        if ( is_email( $subscriber ) ) {
            $subscriber = $this->get_subscriber( $subscriber );
            $subscriber_id = $subscriber->id;
        }

        if ( empty( $list ) ) {
            return;
        }

        if ( !is_numeric( $list ) ) {
            $list_id = $this->add_list( $list );
        } else {
            $list_id = $list;
        }

        if ( !$this->in_list( $list_id, $subscriber_id ) ) {
            $wpdb->insert(
                $wpdb->prefix .'followup_subscribers_to_lists',
                array(
                    'subscriber_id' => $subscriber_id,
                    'list_id'       => $list_id
                )
            );
        }

    }

    /**
     * Check if the $subscriber is in the provided $list
     *
     * @param mixed $list
     * @param mixed $subscriber
     * @return bool
     */
    public function in_list( $list, $subscriber ) {
        $wpdb = Follow_Up_Emails::instance()->wpdb;

        if ( !is_numeric( $list ) ) {
            $list_id = $wpdb->get_var($wpdb->prepare(
                "SELECT id
                FROM {$wpdb->prefix}followup_subscriber_lists
                WHERE list_name = %s",
                $list
            ));
        } else {
            $list_id = $list;
        }

        if ( !is_numeric( $subscriber ) ) {
            $subscriber = $this->get_subscriber( $subscriber );
            $subscriber_id = $subscriber['id'];
        } else {
            $subscriber_id = $subscriber;
        }

        $check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*)
            FROM {$wpdb->prefix}followup_subscribers_to_lists
            WHERE subscriber_id = %d
            AND list_id = %d",
            $subscriber_id,
            $list_id
        ));

        if ( $check > 0 ) {
            return true;
        }

        return false;
    }

}
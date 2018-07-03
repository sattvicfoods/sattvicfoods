<?php
/**
 * FUE API Newsletter Class
 *
 * Handles requests to the /newsletter endpoint
 *
 * @author      75nineteen
 * @since       4.1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class FUE_API_Newsletter extends FUE_API_Resource {

    /** @var string $base the route base */
    protected $base = '/newsletter';

    /**
     * Register the routes for this class
     *
     * GET /newsletter
     *
     * @since 4.1.8
     *
     * @param array $routes
     *
     * @return array
     */
    public function register_routes( $routes ) {

        # GET /newsletter
        $routes[ $this->base ] = array(
            array( array( $this, 'get_routes' ), FUE_API_Server::READABLE ),
        );

        # GET /newsletter/lists
        $routes[ $this->base . '/lists' ] = array(
            array( array( $this, 'get_lists' ), FUE_API_Server::READABLE )
        );

        # GET/POST/DELETE /newsletter/subscribers
        $routes[ $this->base . '/subscribers' ] = array(
            array( array( $this, 'get_subscribers' ), FUE_API_Server::READABLE ),
            array( array( $this, 'add_subscriber' ), FUE_API_Server::CREATABLE | FUE_API_Server::ACCEPT_DATA ),
            array( array( $this, 'delete_subscriber' ), FUE_API_SERVER::DELETABLE | FUE_API_Server::ACCEPT_DATA )
        );

        return $routes;
    }

    /**
     * Get a list of available routes for this endpoint
     *
     * @since 4.1.8
     * @return array
     */
    public function get_routes() {
        return array(
            'routes' => array(
                '/newsletter/' => array(
                    'supports'  => array('HEAD','GET')
                ),

                '/newsletter/lists/' => array(
                    'supports'  => array('HEAD', 'GET')
                ),

                '/newsletter/subscribers/' => array(
                    'supports'  => array('HEAD', 'GET', 'POST', 'DELETE')
                ),
            )
        );
    }

    /**
     * Get all newsletter lists
     * @return array
     */
    public function get_lists() {
        return array(
            'lists' => array_values(fue_get_subscription_lists())
        );
    }

    /**
     * Get all subscribers. Can be filtered by passing in a list
     * @param array $filter
     * @param int $page
     * @return array
     */
    public function get_subscribers( $filter = array(), $page = 1 ) {
        $wpdb       = Follow_Up_Emails::instance()->wpdb;
        $newsletter = Follow_Up_Emails::instance()->newsletter;

        $page               = absint( $page );
        $filter['limit']    = ( !empty( $filter['limit'] ) ) ? absint( $filter['limit'] ) : get_option('posts_per_page');
        $filter['list']     = ( !empty( $filter['list'] ) ) ? $filter['list'] : '';

        $start = ( $page * $filter['limit'] ) - $filter['limit'];

        $param  = array();

        if ( !empty( $filter['list'] ) ) {
            $list_id = $wpdb->get_var($wpdb->prepare(
                "SELECT SQL_CALC_FOUND_ROWS id
                FROM {$wpdb->prefix}followup_subscriber_lists
                WHERE list_name = %s",
                $filter['list']
            ));

            if ( $list_id ) {
                $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT s.id
                        FROM {$wpdb->prefix}followup_subscribers s, {$wpdb->prefix}followup_subscribers_to_lists s2l
                        WHERE s.id = s2l.subscriber_id
                        AND s2l.list_id = %d";
            }
            $param[] = $list_id;
        } else {
            $sql = "SELECT id, email FROM {$wpdb->prefix}followup_subscribers";
        }

        $sql .= " ORDER BY email ASC LIMIT $start, {$filter['limit']}";

        if ( !empty( $param ) ) {
            $sql = $wpdb->prepare( $sql, $param );
        }

        $result = $wpdb->get_results( $sql );
        $total = $wpdb->get_var("SELECT FOUND_ROWS()");
        $num_pages = ceil( $total / $filter['limit'] );

        // set the pagination data
        $query = array(
            'page'        => $page,
            'single'      => count( $result ) == 1,
            'total'       => $total,
            'total_pages' => $num_pages
        );
        $this->server->add_pagination_headers( $query );

        $response = array();

        foreach ( $result as $row ) {
            $response[] = array('subscriber' => $newsletter->get_subscriber( $row->id ));
        }

        return $response;

    }

    /**
     * Add one or more subscribers
     * @param array $data
     * @return array
     */
    public function add_subscriber( $data = array() ) {
        $emails = (!empty( $data['email'] ) ) ? $data['email'] : '';
        $list   = (!empty( $data['list'] ) ) ? $data['list'] : '';

        if ( empty( $emails ) ) {
            return new WP_Error('fue_api_missing_parameter', __('Cannot add a subscriber without an email address', 'follow_up_emails') );
        }

        // convert the emails into an array
        $emails = array_map( 'sanitize_email', explode( ',', $emails ) );
        $ids    = array();

        foreach ( $emails as $email ) {
            $id = fue_add_subscriber( $email, $list );

            if ( is_wp_error( $id ) ) {
                return $id;
            }

            $ids[] = $id;
        }

        $response = array();
        foreach ( $ids as $id ) {
            $response[] = fue_get_subscriber( $id );
        }

        return array( 'subscribers' => $response );
    }

    /**
     * Delete one or more subscribers
     * @param array $data
     * @return array
     */
    public function delete_subscriber( $data = array() ) {
        $emails = (!empty( $data['email'] ) ) ? $data['email'] : '';

        if ( empty( $emails ) ) {
            return new WP_Error('fue_api_missing_parameter', __('Cannot remove a subscriber without an email address', 'follow_up_emails') );
        }

        // convert the emails into an array
        $emails = array_map( 'sanitize_email', explode( ',', $emails ) );

        foreach ( $emails as $email ) {
            fue_exclude_email_address( $email );
            fue_remove_subscriber( $email );
        }

        return array( 'ack' => 'OK' );
    }

}
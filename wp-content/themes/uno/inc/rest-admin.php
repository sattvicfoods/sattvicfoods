<?php


class Sattvic_REST
{
    public function __construct()
    {
        add_action('init', [&$this, 'routes']);
    }

    public function routes()
    {
        register_rest_route('sattvic/v1', '/paycheck', [
           'method' => 'POST',
           'callback' => [&$this, 'checkPayment']
        ]);
    }

    public function checkPayment()
    {
        $post_id = $_GET['id'];

        $key = "mZnOKb";
        $salt = "BBKho8Pc";
        $command = "verify_payment";
        $date = get_the_date('ymd',$post_id);
        $var1 = $post_id.'_'.$date;

        $hash_str = $key  . '|' . $command . '|' . $var1 . '|' . $salt ;
        $hash = strtolower(hash('sha512', $hash_str));
        $r = array('key' => $key , 'hash' =>$hash , 'var1' => $var1, 'command' => $command);
        $wsUrl = "https://info.payu.in/merchant/postservice?form=2";
        $qs= http_build_query($r);
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $wsUrl);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        $o = curl_exec($c);
        if (curl_errno($c)) {
            $sad = curl_error($c);
            throw new Exception($sad);
        }
        curl_close($c);
        $valueSerialized = @unserialize($o);

        if ( true ) {
            $payment_gateway = get_post_meta( $post_id, '_payment_method', true );
            if ($payment_gateway == 'pumcp') {
                $someArray = json_decode($o);
                foreach($someArray->transaction_details as $trans => $single_t) {
                    // echo '<pre>';
                    // var_dump($someArray);
                    // echo '</pre>';
                    if ($single_t->status === 'success') {
                        wp_send_json_success('<span style="color:green;text-transform: capitalize;">' . $single_t->status .'</span>');
                    } elseif ($single_t->status === 'failure') {
                        wp_send_json_success('<span style="color:red;text-transform: capitalize;">' . $single_t->status .'</span>');
                    } else {
                        wp_send_json_success('<span style="color:#ffb300;">' . $single_t->status .'</span>');
                    }
                }
            } else if ($payment_gateway == 'ccavenue') {

                $ccavenue_api = new CCAvenue_API();

                $cc_order_id = get_post_meta($post_id,'_ccave_order_id',true);

                if (!$cc_order_id) {
                    $order = new WC_Order($post_id);
                    $order_data = $order->get_data();
                    // $itmeta = wc_display_item_meta( $order );
                    // $transID = $order->get_transaction_id();
                    // $ref_no = 567116;
                    $order_date_created = $order_data['date_created']->date('ymd');

                    $cc_order_id = $post_id . '_' . $order_date_created;
                    // $order_date_modified = $order_data['date_modified']->date('ymd');
                }

                $cc_post_data = array("order_no" => $cc_order_id);

                $cc_t_data = trim($ccavenue_api->orderStatusTracker($cc_post_data));

                $cc_t_data_end =  strrpos($cc_t_data, '}');
                $cc_t_data = substr($cc_t_data, 0, $cc_t_data_end + 1);

                $cc_response = json_decode($cc_t_data);

                $cc_order_status = $cc_response->order_status;

                if ($cc_order_status == 'Success' ||
                    $cc_order_status == 'Shipped' ||
                    $cc_order_status == 'Successful') {

                    wp_send_json_success('<span style="color:green;text-transform: capitalize;">' . $cc_order_status .'</span>');

                } else if ($cc_order_status == 'Awaited' ||
                    $cc_order_status == 'Chargeback' ||
                    $cc_order_status == 'Fraud' ||
                    $cc_order_status == 'Initiated' ||
                    $cc_order_status == 'Refunded' ||
                    $cc_order_status == 'Refunded' ||
                    $cc_order_status == 'System refund' ) {

                    wp_send_json_success('<span style="color:#ffb300;">' . $cc_order_status .'</span>');

                } else if ($cc_order_status == 'Auto-Cancelled' ||
                    $cc_order_status == 'Aborted' ||
                    $cc_order_status == 'Failure' ||
                    $cc_order_status == 'Cancelled' ||
                    $cc_order_status == 'Invalid' ||
                    $cc_order_status == 'Unsuccessful' ){

                    wp_send_json_success('<span style="color:red;text-transform: capitalize;">' . $cc_order_status .'</span>');

                } else {

                    wp_send_json_success('<span style="color:black;text-transform: capitalize;"> Cancelled </span>');

                    /* error debug */
                    // var_dump($cc_response);
                    // var_dump( $cc_order_id);
                    // echo $order_date_created . ' - ' . $order_date_modified;
                }

            } else {
                wp_send_json_success('Not Payu Gateway');
            }
        }
        
    }
}

new Sattvic_REST();
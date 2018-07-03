<?php

/**
 * SMS Gateway handler class
 *
 * @author satosms
 */
class SatSMS_SMS_Gateways {

    private static $_instance;

    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new SatSMS_SMS_Gateways();
        }

        return self::$_instance;
    }

    /**
     * TalkwithText SMS Gateway
     *
     * @param  array $sms_data
     * @return boolean
     */
    function talkwithtext( $sms_data ) {
        $username = satosms_get_option( 'talkwithtext_username', 'satosms_gateway', '' );
        $password = satosms_get_option( 'talkwithtext_password', 'satosms_gateway', '' );
        $originator = satosms_get_option( 'talkwithtext_originator', 'satosms_gateway', '' );
        $admin_phone = str_replace( '+', '', $sms_data['number'] );

        if( empty( $username ) || empty( $password ) ) {
            return;
        }

        require_once SATSMS_DIR . '/lib/infobip/vendor/autoload.php';

        $smsClient = new \infobip\SmsClient( $username, $password );

        $smsMessage = new \infobip\models\SMSRequest();
        $smsMessage->senderAddress = $originator;
        $smsMessage->address = $sms_data['number'];
        $smsMessage->message = $sms_data['sms_body'];

        $smsMessageSendResult = $smsClient->sendSMS($smsMessage);

        $smsMessageStatus = $smsClient->queryDeliveryStatus($smsMessageSendResult);
        $deliveryStatus = $smsMessageStatus->deliveryInfo[0]->deliveryStatus;

        if( $smsMessageStatus->isSuccess() ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sends SMS via SMSGlobal api
     *
     * @param array $sms_data
     * @return boolean
     */
    function smsglobal( $sms_data ) {
        $response = false;

        $username = satosms_get_option( 'smsglobal_name', 'satosms_gateway' );
        $password = satosms_get_option( 'smsglobal_password', 'satosms_gateway' );
        $from = satosms_get_option( 'smsglobal_sender_no', 'satosms_gateway' );
        $phone = str_replace( '+', '', $sms_data['number'] );
        //bail out if no username or password given
        if ( empty( $username ) || empty( $password ) ) {
            return $response;
        }

        $content = 'action=sendsms' .
                '&user=' . rawurlencode( $username ) .
                '&password=' . rawurlencode( $password ) .
                '&to=' . rawurlencode( $phone ) .
                '&from=' . rawurlencode( $from ) .
                '&text=' . rawurlencode( $sms_data['sms_body'] );

        $smsglobal_response = file_get_contents( 'http://www.smsglobal.com.au/http-api.php?' . $content );

        $explode_response = explode( 'SMSGlobalMsgID:', $smsglobal_response );

        if ( count( $explode_response ) == 2 ) {
            $response = true;
        }

        return $response;
    }

    /**
     * Sends SMS via Clickatell api
     *
     * @param type $sms_data
     * @return boolean
     */
    function clickatell( $sms_data ) {

        $response = false;
        $username = satosms_get_option( 'clickatell_name', 'satosms_gateway' );
        $password = satosms_get_option( 'clickatell_password', 'satosms_gateway' );
        $api_key = satosms_get_option( 'clickatell_api', 'satosms_gateway' );
        $phone = str_replace( '+', '', $sms_data['number'] );
        $text = urlencode( $sms_data['sms_body'] );

        //bail out if nothing provided
        if ( empty( $username ) || empty( $password ) || empty( $api_key ) ) {
            return $response;
        }

        // auth call
        $baseurl = "http://api.clickatell.com";
        $url = sprintf( '%s/http/auth?user=%s&password=%s&api_id=%s', $baseurl, $username, $password, $api_key );

        // do auth call
        $ret = file( $url );

        // explode our response. return string is on first line of the data returned
        $sess = explode( ":", $ret[0] );
        if ( $sess[0] == "OK" ) {

            $sess_id = trim( $sess[1] ); // remove any whitespace
            $url = sprintf( '%s/http/sendmsg?session_id=%s&to=%s&text=%s', $baseurl, $sess_id, $phone, $text );

            // do sendmsg call
            $ret = file( $url );
            $send = explode( ":", $ret[0] );

            if ( $send[0] == "ID" ) {
                $response = true;
            }
        }

        return $response;
    }

    /**
     * Sends SMS via Nexmo api
     *
     * @param type $sms_data
     * @return boolean
     */
    function nexmo( $sms_data ) {

        $response =  false;

        $api_key = satosms_get_option( 'nexmo_api', 'satosms_gateway' );
        $api_secret = satosms_get_option( 'nexmo_api_secret', 'satosms_gateway' );
        $from = satosms_get_option( 'nexmo_from_name', 'satosms_gateway' );

        require_once dirname( __FILE__ ) . '/../lib/NexmoMessage.php';

        $nexmo_sms = new NexmoMessage( $api_key, $api_secret);
        $info = $nexmo_sms->sendText( $sms_data['number'], $from, $sms_data['sms_body'] );

        if ( $info->messages[0]->status == '0' ) {
            $response = true;
        }

        return $response;
    }

    /**
     * Sends SMS via Twillo api
     *
     * @param type $sms_data
     * @return boolean
     */
    function twilio( $sms_data ) {

        $sid = satosms_get_option( 'twilio_sid', 'satosms_gateway' );
        $token = satosms_get_option( 'twilio_token', 'satosms_gateway' );
        $from = satosms_get_option( 'twilio_from_number', 'satosms_gateway' );

        require_once dirname( __FILE__ ) . '/../lib/twilio/Twilio.php';

        $client = new Services_Twilio( $sid, $token );

        try {
            $message = $client->account->sms_messages->create(
                    $from, '+' . $sms_data['number'], $sms_data['sms_body']
            );

            if ( $message->status != 'failed' ) {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    /**
     * Sending SMS via hoiio gateway
     *
     * @param  array $sms_data
     * @return boolean
     */
    function hoiio( $sms_data ) {
        $app_id       = satosms_get_option( 'hoiio_appid', 'satosms_gateway' );
        $access_token = satosms_get_option( 'hoiio_accesstoken', 'satosms_gateway' );

        $content = 'dest=' . rawurlencode( $sms_data['number'] ) .
                '&msg=' . rawurlencode( $sms_data['sms_body'] ) .
                '&access_token=' . $access_token .
                '&app_id=' . $app_id;

        $hoiio_response = file_get_contents( 'https://secure.hoiio.com/open/sms/send?' . $content );

        $response = (array)json_decode( $hoiio_response );

        if ( $response['status'] == 'success_ok' ) {
            return true;
        }

        return false;
    }

    /**
     * Intellisms Gateway for Sending SMS
     *
     * @param array $sms_data
     * @return boolean
     */
    function intellisms( $sms_data ) {
        $intellisms_username  = satosms_get_option( 'intellisms_username', 'satosms_gateway' );
        $intellisms_password  = satosms_get_option( 'intellisms_password', 'satosms_gateway' );
        $intellisms_sender_no = satosms_get_option( 'intellisms_sender_no', 'satosms_gateway' );
        $result = false;

        require_once SATSMS_DIR . '/lib/IntelliSMS.php';

        $objIntelliSMS = new IntelliSMS();

        $objIntelliSMS->Username = $intellisms_username;
        $objIntelliSMS->Password = $intellisms_password;

        $SendStatusCollection = $objIntelliSMS->SendMessage ( $sms_data['number'], $sms_data['sms_body'], $intellisms_sender_no );

        foreach ( $SendStatusCollection as $SendStatus ) {
            if ( $SendStatus["Result"] == "OK" ) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * TalkwithText SMS Gateway
     *
     * @param  array $sms_data
     * @return boolean
     */
    function infobip( $sms_data ) {
        $infobip_username        = satosms_get_option( 'infobip_username', 'satosms_gateway', '' );
        $infobip_password        = satosms_get_option( 'infobip_password', 'satosms_gateway', '' );
        $infobip_sender_no       = satosms_get_option( 'infobip_sender_no', 'satosms_gateway', '' );

        if( empty( $infobip_username ) || empty( $infobip_password ) ) {
            return;
        }

        require_once dirname( __FILE__ ) . '/../lib/infobip/vendor/autoload.php';

        $smsClient = new \infobip\SmsClient( $infobip_username, $infobip_password );

        $smsMessage = new \infobip\models\SMSRequest();
        $smsMessage->senderAddress = $infobip_sender_no;
        $smsMessage->address = $sms_data['number'];
        $smsMessage->message = $sms_data['sms_body'];

        $smsMessageSendResult = $smsClient->sendSMS($smsMessage);

        $smsMessageStatus = $smsClient->queryDeliveryStatus($smsMessageSendResult);

        if( $smsMessageStatus->isSuccess() ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Atom Park SMS Gateways
     *
     * @param  array $sms_data
     *
     * @return boolean
     */
    function atompark( $sms_data ) {
        $atompark_username        = satosms_get_option( 'atompark_username', 'satosms_gateway', '' );
        $atompark_password        = satosms_get_option( 'atompark_password', 'satosms_gateway', '' );
        $atompark_sender_no       = satosms_get_option( 'atompark_sender_no', 'satosms_gateway', '' );

        $src = '<?xml version="1.0" encoding="UTF-8"?>
        <SMS>
            <operations>
            <operation>SEND</operation>
            </operations>
            <authentification>
            <username>'.$atompark_username.'</username>
            <password>'.$atompark_password.'</password>
            </authentification>
            <message>
            <sender>'.$atompark_sender_no.'</sender>
            <text>'.$sms_data['sms_body'].'</text>
            </message>
            <numbers>
            <number messageID="msg11">'.$sms_data['number'].'</number>
            </numbers>
        </SMS>';

        $Curl = curl_init();
        $CurlOptions = array(
            CURLOPT_URL=>'http://my.atompark.com/sms/xml.php',
            CURLOPT_FOLLOWLOCATION=>false,
            CURLOPT_POST=>true,
            CURLOPT_HEADER=>false,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CONNECTTIMEOUT=>15,
            CURLOPT_TIMEOUT=>100,
            CURLOPT_POSTFIELDS=>array('XML'=>$src),
        );
        curl_setopt_array($Curl, $CurlOptions);
        if(false === ($Result = curl_exec($Curl))) {
            throw new Exception('Http request failed');
        }

        curl_close($Curl);

        $xml = simplexml_load_string( $Result );
        $status = reset( $xml->status );

        if ( $status ) {
            return true;
        } else {
            return false;
        }

    }
    function smsidindia( $sms_data ) {

        $authKey       = satosms_get_option( 'smsidindia_username', 'satosms_gateway' );
        $mobileNumber = $sms_data['number'];
        $senderId = satosms_get_option( 'smsidindia_password', 'satosms_gateway' );
        $message = $sms_data['sms_body'];
        //  $route = "1";
        $response = "json";
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            // 'route' => $route
            'response' => $response
        );
        //echo '<pre>'; print_r($postData); echo '</pre>';exit;
        //API URL
        $url="http://sms.ssdindia.com/api/sendhttp.php";
// init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);

        $response = (array)json_decode($output );
       // echo '<pre>'; print_r($response); echo '</pre>';exit;
        if ( $response['type'] == 'success' ) {
            return true;
        }

        return false;
    }

}

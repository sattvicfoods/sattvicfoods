<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WOEL_Email_Log {

    private $order_id;
 
	static function unInstallDatabaseTables() {
		global $wpdb;
		$tableName = self::table_name();
		$wpdb->query("DROP TABLE IF EXISTS `$tableName`");
	}


    static function table_name(){
        global $wpdb;
        return $wpdb->prefix.'woel_woocommerce_order_emails_log';
    }
    
    
	public function addFilterActions() {      
    
        // saves order id
        add_action( 'woocommerce_email_order_details', array( &$this, 'email_details' ), 10, 4 );
        
        // fires on wp_mail 
		add_filter( 'wp_mail', array( &$this, 'log_email' ), PHP_INT_MAX );
               
	}
    
    // saved order id
    public function email_details( $order, $sent_to_admin = false, $plain_text = false, $email = '' ) {                            
        $this->order_id = trim(str_replace('#', '', $order->get_order_number()));                
    }  

	private function extractReceiver( $receiver ) {
		return is_array( $receiver ) ? implode( ',\n', $receiver ) : $receiver;
	}

	private function extractHeader( $headers ) {
		return is_array( $headers ) ? implode( ',\n', $headers ) : $headers;
	}

	private function extractAttachments( $attachments ) {
        
		$attachments = is_array( $attachments ) ? $attachments : array( $attachments );
		$attachment_urls = array();
		$basename = 'uploads';
		$basename_needle = '/'.$basename.'/';
        
		foreach ( $attachments as $attachment ) {
    		$append_url = substr( $attachment, strrpos( $attachment, $basename_needle ) + strlen($basename_needle) - 1 );
			$attachment_urls[] = $append_url;
		}
        
		return implode( ',\n', $attachment_urls );
	}

	private function extractMessage( $mail ) {
        
		if ( isset($mail['message']) ) {
			return $mail['message'];
		} elseif ( isset($mail['html']) ) {
			return $mail['html'];
		}
        
		return "";
        
	}
    
	private function extractFields( $mail ) {
        
		return array(
			'receiver'			=> $this->extractReceiver( $mail['to'] ),
			'subject'			=> $mail['subject'],
            'message'			=> $this->extractMessage( $mail ),            
			'headers'			=> $this->extractHeader( $mail['headers'] ),
			//'attachments'		=> $this->extractAttachments( $mail['attachments'] ),
			'timestamp'         => current_time( 'mysql' ),
			'host'              => isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : ''
		);
        
	}

	public function log_email( $mailOriginal ) {
 
        global $wpdb;
        
        $mail = $mailOriginal;

        $order_id = 0;
         
        if( isset( $this->order_id ) && $this->order_id != null ){ 
            $order_id = $this->order_id;
        }  
        
        $fields = $this->extractFields( $mail );
                
        // only log for woocommerce order && non test email
        if( isset( $order_id ) && $order_id != 0 && strpos( $fields['subject'], 'TEST EMAIL' ) === false ){
                    
            $fields['order_id'] = $order_id;
         
            // save in table
            $wpdb->insert( self::table_name(), $fields );         

        }
        
		return $mailOriginal;
	}
    
    
    public function get_emails_sent( $order_id ){
        
        global $wpdb;
        
        $order_id = sanitize_text_field($order_id);
        
        $table = self::table_name();
        
		$sql = $wpdb->prepare( " SELECT * FROM {$table} WHERE order_id = %d ORDER BY timestamp ", $order_id );
		$emails = $wpdb->get_results($sql); 

        return $emails;        
        
    }
    
    
    
    public function get_email_message( $email_id ){
        
        global $wpdb;
        
        $email_id = sanitize_text_field($email_id);
        
        $table = self::table_name();
        
		$sql = $wpdb->prepare( " SELECT * FROM {$table} WHERE id = %d ", $email_id );
		$email = $wpdb->get_row($sql); 

        return $email->message;            
        
    }
    
    
    public static function admin_options(){
        
    	?>
		<h3>Email Logs</h3>
    	<p></p>
    		<table class="form-table">
	    		
			</table><!--/.form-table-->
		<?php        
    }    
    
}



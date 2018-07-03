<?php 
    global $woocommerce, $post;  
    
    // get all emails sent from db log
    $order_id = $_GET['post'];
    
    $WOEL_Email_Log = new WOEL_Email_Log;
    $WOEL_emails_sent = $WOEL_Email_Log->get_emails_sent( $order_id );
    
?>

    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th class="manage-column column-cb check-column" scope="col"></th>
                <th class="manage-column column-columnname" scope="col">Sent Date</th>
                <th class="manage-column column-columnname" scope="col">To</th>
                <th class="manage-column column-columnname" scope="col">Subject</th>               
                <th class="manage-column column-columnname" scope="col">View email content (Premium)</th>
                <th class="manage-column column-columnname" scope="col">Resend email (Premium)</th>
            </tr>
        </thead>

        <tbody>
            <?php 
            if( $WOEL_emails_sent ) {
                $count = 1;
                foreach( $WOEL_emails_sent as $email ){ 
                ?>
                <tr class="<?php if( $count%2 ) { echo "alternate"; } ?>">
                    <td class="column-columnname"><?php echo $count;?></td>
                    <td class="column-columnname"><?php echo $email->timestamp;?></td>          
                    <td class="column-columnname"><?php echo $email->receiver;?></td>
                    <td class="column-columnname"><?php echo $email->subject;?></td>
                    <td class="column-columnname"><a href="<?php echo admin_url('admin.php');?>?page=wc-settings&tab=integration&section=woel-settings-screen">Upgrade</a></td>   
                    <td class="column-columnname"><a href="<?php echo admin_url('admin.php');?>?page=wc-settings&tab=integration&section=woel-settings-screen">Upgrade</a></td>                    
                </tr>
                <?php 
                    $count++;
                } 
            } else {
            ?>        
                <tr>
                    <td colspan="4">No Emails Logged</td>
                </tr>   
            <?php } ?>            
        </tbody>
        
    </table>
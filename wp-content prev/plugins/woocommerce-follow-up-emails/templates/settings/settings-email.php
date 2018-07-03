<?php
$bounce_defaults = FUE_Bounce_Handler::get_default_settings();
$bounce = wp_parse_args( $bounce, $bounce_defaults );

?>
<form action="admin-post.php" method="post" id="emails_form" enctype="multipart/form-data">

    <h3><?php _e('Daily Emails Summary', 'follow_up_emails'); ?></h3>

    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th><label for="daily_emails"><?php _e('Email Address(es)', 'follow_up_emails'); ?></label></th>
            <td>
                <input type="text" name="daily_emails" id="daily_emails" value="<?php echo esc_attr( get_option('fue_daily_emails', '') ); ?>" />
                <span class="description"><?php _e('comma separated', 'follow_up_emails'); ?></span>
            </td>
        </tr>
        <tr valign="top">
            <th><label for="daily_emails_time_hour"><?php _e('Preferred Time', 'follow_up_emails'); ?></label></th>
            <td>
                <?php
                $time   = get_option('fue_daily_emails_time', '12:00 AM');
                $parts  = explode(':', $time);
                $parts2 = explode(' ', $parts[1]);
                $hour   = $parts[0];
                $minute = $parts2[0];
                $ampm   = $parts2[1];
                ?>
                <select name="daily_emails_time_hour" id="daily_emails_time_hour">
                    <?php
                    for ($x = 1; $x <= 12; $x++):
                        $val = ($x >= 10) ? $x : '0'.$x;
                        ?>
                        <option value="<?php echo $val; ?>" <?php selected($hour, $val); ?>><?php echo $val; ?></option>
                    <?php endfor; ?>
                </select>

                <select name="daily_emails_time_minute" id="daily_emails_time_minute">
                    <?php
                    for ($x = 0; $x <= 55; $x+=15):
                        $val = ($x >= 10) ? $x : '0'. $x;
                        ?>
                        <option value="<?php echo $val; ?>" <?php selected($minute, $val); ?>><?php echo $val; ?></option>
                    <?php endfor; ?>
                </select>

                <select name="daily_emails_time_ampm" id="daily_emails_time_ampm">
                    <option value="AM" <?php selected($ampm, 'AM'); ?>>AM</option>
                    <option value="PM" <?php selected($ampm, 'PM'); ?>>PM</option>
                </select>
            </td>
        </tr>
        </tbody>
    </table>

    <h3><?php _e('Email Settings', 'follow_up_emails'); ?></h3>

    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th>
                <label for="bcc"><?php _e('BCC', 'follow_up_emails'); ?></label>
                <img class="help_tip" title="<?php _e('All emails will be blind carbon copied to this address', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL .'/images/help.png'; ?>" width="16" height="16" />
            </th>
            <td>
                <input type="text" name="bcc" id="bcc" value="<?php echo esc_attr( $bcc ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th>
                <label for="from_name"><?php _e('From/Reply-To Name', 'follow_up_emails'); ?></label>
                <img class="help_tip" title="<?php _e('The name that your emails will come from and replied to', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL .'/images/help.png'; ?>" width="16" height="16" />
            </th>
            <td>
                <input type="text" name="from_name" id="from_name" value="<?php echo esc_attr( $from_name ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th>
                <label for="from_email"><?php _e('From/Reply-To Email', 'follow_up_emails'); ?></label>
                <img class="help_tip" title="<?php _e('The email address that your emails will come from and replied to', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL .'/images/help.png'; ?>" width="16" height="16" />
            </th>
            <td>
                <input type="text" name="from_email" id="from_email" value="<?php echo esc_attr( $from ); ?>" />
            </td>
        </tr>
        </tbody>
    </table>

    <h3><?php _e('Bounce Settings', 'follow_up_emails'); ?></h3>

    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th class="titledesc">
                <label for="bounce_email"><?php _e('Bounce Address', 'follow_up_emails'); ?></label>
                <img class="help_tip" title="<?php _e('Undelivered emails will be sent to this address', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL .'/images/help.png'; ?>" width="16" height="16" />
            </th>
            <td>
                <input type="text" name="bounce[email]" id="bounce_email" value="<?php echo esc_attr( $bounce['email'] ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th class="titledesc">
                <label for="bounce_handling"><?php _e('Automatic bounce handling', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <input type="checkbox" name="bounce[handle_bounces]" id="bounce_handling" value="1" <?php checked( 1, $bounce['handle_bounces'] ); ?> />
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <td colspan="2">
                <?php _e('To enable the automatic handling of bounced emails, enter the POP3 account of the bounce address above.', 'follow_up_emails'); ?>
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <th class="titledesc">
                <label for="bounce_server"><?php _e('Server Address', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <input type="text" name="bounce[server]" id="bounce_server" value="<?php echo esc_attr( $bounce['server'] ); ?>" />
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <th class="titledesc">
                <label for="bounce_port"><?php _e('Port', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <input type="text" name="bounce[port]" id="bounce_port" size="3" value="<?php echo esc_attr( $bounce['port'] ); ?>" />
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <th class="titledesc">
                <label for="bounce_ssl"><?php _e('Use SSL', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <input type="checkbox" name="bounce[ssl]" id="bounce_ssl" value="1" <?php checked( 1, $bounce['ssl'] ); ?> />
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <th class="titledesc">
                <label for="bounce_username"><?php _e('Username', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <input type="text" name="bounce[username]" id="bounce_username" value="<?php echo esc_attr( $bounce['username'] ); ?>" />
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <th class="titledesc">
                <label for="bounce_password"><?php _e('Password', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <input type="password" name="bounce[password]" id="bounce_password" value="<?php echo esc_attr( $bounce['password'] ); ?>" />
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <th class="titledesc">
                <label for="bounce_delete_messages"><?php _e('Delete Messages', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <input type="checkbox" name="bounce[delete_messages]" id="bounce_delete_messages" value="1" <?php checked( 1, $bounce['delete_messages'] ); ?> />
                <span class="description"><?php _e('Delete emails to keep the mailbox clean', 'follow_up_emails'); ?></span>
            </td>
        </tr>
        <tr valign="top" class="bounce_enabled">
            <th class="titledesc">
                <label for="bounce_soft_bounce_resend_interval"><?php _e('Soft Bounces', 'follow_up_emails'); ?></label>
            </th>
            <td>
                <?php
                printf(
                    __('Attemp to resend up to %s times with an interval of %s minutes between each send before marking as a Hard Bounce.', 'follow_up_emails'),
                    '<input type="number" name="bounce[soft_bounce_resend_limit]" id="bounce_soft_bounce_resend_limit" style="width: 50px;" value="'. $bounce['soft_bounce_resend_limit'] .'" />',
                    '<input type="number" name="bounce[soft_bounce_resend_interval]" id="bounce_soft_bounce_resend_interval" style="width: 50px;" value="'. $bounce['soft_bounce_resend_interval'] .'" />'
                );
                ?>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="submit" style="width: auto;">
        <input class="button button-secondary test-bounce" type="button" value="<?php _e('Test Bounce Settings', 'follow_up_emails'); ?>" />
        <div class="spinner test-bounce-spinner" style="float: none;"></div>
        <div class="test-bounce-status" style="display: none;"><?php _e('Loading...', 'follow_up_emails'); ?></div>
    </div>

    <?php do_action( 'fue_settings_crm' ); ?>

    <p class="submit">
        <input type="hidden" name="action" value="fue_followup_save_settings" />
        <input type="hidden" name="section" value="<?php echo $tab; ?>" />
        <input type="submit" name="save" value="<?php _e('Save Settings', 'follow_up_emails'); ?>" class="button-primary" />
    </p>

</form>
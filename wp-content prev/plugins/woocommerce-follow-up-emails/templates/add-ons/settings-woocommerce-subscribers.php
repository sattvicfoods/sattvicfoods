<h3><?php _e('Checkout Subscription', 'follow_up_emails'); ?></h3>

<table class="form-table">
    <tr>
        <th colspan="2">
            <label for="enable_checkout_subscription">
                <input type="checkbox" name="enable_checkout_subscription" id="enable_checkout_subscription" value="1" <?php if (1 == get_option('fue_enable_checkout_subscription', 1)) echo 'checked'; ?> />
                <?php _e('Allow customers to subscribe to the newsletter on the checkout form', 'follow_up_emails'); ?>
            </label>
        </th>
    </tr>
    <tr>
        <th>
            <label for="checkout_message">
                <?php _e('Checkout Field Label', 'follow_up_emails'); ?>
            </label>
        </th>
        <td>
            <?php
            $label = get_option( 'fue_checkout_subscription_field_label', 'Send me promos and product updates.' );
            ?>
            <input type="text" name="checkout_subscription_field_label" id="checkout_message" value="<?php echo esc_attr( $label ); ?>" size="50" />
        </td>
    </tr>
</table>
<style>
    div.fue-settings h3 {
        margin-top: 30px;
    }
</style>
<div class="wrap fue-settings woocommerce">
    <div class="icon32"><img src="<?php echo FUE_TEMPLATES_URL; ?>/images/send_mail.png" /></div>
    <h2>
        <?php _e('Follow-Up Emails &mdash; Settings', 'follow_up_emails'); ?>
    </h2>

    <?php include FUE_TEMPLATES_DIR .'/settings/notifications.php'; ?>

    <?php include FUE_TEMPLATES_DIR .'/settings/menu.php'; ?>

    <?php
    switch ( $tab ) {

        case 'email':
            include 'settings-email.php';
            break;

        case 'auth':
            include 'settings-auth.php';
            break;

        case 'crm':
            include 'settings-crm.php';
            break;

        case 'info':
            include 'system-info.php';
            break;

        case 'subscribers':
            include 'settings-subscribers.php';
            break;

        case 'documentation':
            include 'settings-documentation.php';
            break;

        case 'integration':
            include 'settings-integration.php';
            break;

        case 'system':
            include 'settings-system.php';
            break;

        default:
            do_action( "fue_settings_{$tab}" );
            break;

    }

    do_action('fue_settings_form');

    ?>

</div>
<script>
    jQuery(document).ready(function() {
        jQuery(".tips, .help_tip").tipTip({
            'attribute' : 'title',
            'fadeIn' : 50,
            'fadeOut' : 50,
            'delay' : 200
        });
    });
</script>
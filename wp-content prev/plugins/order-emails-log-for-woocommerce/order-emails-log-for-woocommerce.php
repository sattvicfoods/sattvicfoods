<?php 
/*
 * Plugin Name: WooCommerce Order Emails Log
 * Plugin URI: 
 * Description: Logs sent emails related to orders, and displays them in a table on the order screen.
 * Version:  1.2
 * Author: RaiserWeb
 * Author URI: http://www.raiserweb.com
 * Developer: RaiserWeb
 * Developer URI: http://www.raiserweb.com
 * Text Domain: raiserweb
 * License: GPLv2
 *
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include( 'email-log.php' );
		
add_action( 'plugins_loaded', 'WOEL_plugin_init', 0 );
function WOEL_plugin_init(){
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

		// plugin updater
        $woel_main_license_key = get_option('woocommerce_woel-settings-screen_settings', "false");        
        if( isset($woel_main_license_key['woel_main_license_key']) && $woel_main_license_key['woel_main_license_key'] ){
            require 'plugin-update-checker/plugin-update-checker.php';
            // check for updates
            $MyUpdateChecker = PucFactory::buildUpdateChecker(
                'http://raiserweb.com/wp-update-server-master/?action=get_metadata&slug=order-emails-log-for-woocommerce&license_key='.$woel_main_license_key['woel_main_license_key'],
                __FILE__,
                'order-emails-log-for-woocommerce'
            );
        }   

        // include plugin files
        include( 'settings.php' );      
     
        // init the email log filter and actions
        $WOEL_Email_Log = new WOEL_Email_Log;
        $WOEL_Email_Log->addFilterActions();
            
        // Add Meta container admin shop_order pages
        add_action( 'add_meta_boxes', 'WOEL_add_meta_box_edit_order_page' );
        function WOEL_add_meta_box_edit_order_page(){
            add_meta_box( 'woel_order_emails', 'Order Emails Sent Log', 'WOEL_emails_meta_box', 'shop_order', 'normal' );
        }
        function WOEL_emails_meta_box(){       
            include( 'admin/emails-sent-meta-box.php' );       
        }
        
        
        // add the settings screen to integrations tab
        // page=wc-settings&tab=integration&section=woel-settings-screen
        function add_WOEL_settings_integration($methods) {
            $methods[] = 'WOEL_Settings_Integration';
            return $methods;
        }
        add_filter('woocommerce_integrations', 'add_WOEL_settings_integration' );                   
       
    }
         
}
    
	
// plugin activation
function WOEL_installDatabaseTables() {

    global $wpdb;
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 
    $charset_collate = $wpdb->get_charset_collate();        
    
    $tableName = WOEL_Email_Log::table_name();

    $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `order_id` INT NOT NULL,
            `timestamp` DATETIME NOT NULL,
            `host` VARCHAR(200) NULL ,
            `receiver` VARCHAR(200) NOT NULL DEFAULT '0',
            `subject` VARCHAR(400) NOT NULL DEFAULT '0',
            `message` TEXT NULL,
            `headers` TEXT NULL,
            `attachments` VARCHAR(800) NULL ,
            `meta1` TEXT NULL,
            `meta2` TEXT NULL,
            PRIMARY KEY (`id`) 
        ) {$charset_collate};");
               
}
register_activation_hook( __FILE__, 'WOEL_installDatabaseTables' ); 
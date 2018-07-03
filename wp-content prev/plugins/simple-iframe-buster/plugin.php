<?php
/*
Plugin Name: Simple Iframe Buster
Version: 1.1
Description: Enqueues a sitewide javascript to inhibit iframes
Author: Mikel King
Text Domain: simple-iframe-buster
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause
*/

/*
    Copyright (C) 2014, Mikel King, rd.com, (mikel.king AT rd DOT com)
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
    
        * Redistributions of source code must retain the above copyright notice, this
          list of conditions and the following disclaimer.
        
        * Redistributions in binary form must reproduce the above copyright notice,
          this list of conditions and the following disclaimer in the documentation
          and/or other materials provided with the distribution.
        
        * Neither the name of the {organization} nor the names of its
          contributors may be used to endorse or promote products derived from
          this software without specific prior written permission.
    
    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
    AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
    IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
    FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
    DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
    CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
    OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class Simple_Iframe_Buster {
    const VERSION   = '1.1';
    const ENABLED   = true;
    const DISABLED  = false;
    const IN_FOOTER      = true;
    const IN_HEADER      = false;
    const DEPENDS        = 'jquery';
    const SCRIPT_FILE    = '/js/iframe-buster.js';
    const SCRIPT_SLUG    = 'iframe-buster-script';

    private static $instance = array();
    
    protected static $initialized = false;

    protected $notifier;

    public function __construct() {
        add_action( 'init', array($this, 'register_buster_script'));
        //add_action('wp_footer', array($this, 'enq_buster_script'));
        //add_action( 'wp_head', array($this, 'debug_plugin' ));
        add_action( 'send_headers', array( $this, 'send_x_frame_meta_header' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'enq_buster_script' ));
    }

    
    public function register_buster_script() {
        $script_url = $this->get_url_to_asset(self::SCRIPT_FILE);
        wp_register_script(self::SCRIPT_SLUG, $script_url, array(), self::VERSION, self::IN_FOOTER);
    }

    public function enq_buster_script() {
        wp_enqueue_script( self::SCRIPT_SLUG );
    }

    public function get_url_to_asset( $script ) {
        return( plugins_url( $script, __FILE__ ));
    }
    
    public function send_x_frame_meta_header() {
        header( 'X-Frame-Options: SAMEORIGIN' );
    }
    
    /*
        Will always return the self initiated copy of itself.
    */
    public static function init() {
        if (function_exists("is_admin") && is_admin() &&
            function_exists('add_filter') && ! self::$initialized) {
            self::$initialized = true;
            return( self::$initialized );
        }
    }

    public static function get_instance() {
        $caller = get_called_class();
        if ( !isset( self::$instance[$caller] ) ) {
            self::$instance[$caller] = new $caller();
            self::$instance[$caller]->init();
        }

        return( self::$instance[$caller] );
    }
}


$sib = Simple_Iframe_Buster::get_instance();

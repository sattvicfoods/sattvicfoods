simple-iframe-buster
===================

Simple Iframe Buster - a WordPress Plugin

=== Plugin Name ===
Contributors: vizkr
Tags: iframe, x-frame-options
Requires at least: 3.9
Tested up to: 4.6
Stable tag: 3.8
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause

Provides a method of setting the X-Frame-Options header to SAMEORIGIN. Also enqueues a javascript based iframe blocker.

== Description ==

Provides a method of adding X-Frame-Options to the http headers for sites hosted in an environment that does not grant access to 
the webserver config, .htaccess or lack mod_headers type facility.

+ Sets X-Frame-Options to SAMEORIGIN
+ Enqueue iframe blocking javascript

== Installation ==

1. Upload `simple-iframe-buster` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is there a setting or options page? =

Currently no.

== Screenshots ==

1. Since there's no settings page there is nothing to see here... yet. 

== Changelog ==

= 1.1 =
* Corrected quoting issues in js files.

= 1.0 =
* Initial stable version.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely 
complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.




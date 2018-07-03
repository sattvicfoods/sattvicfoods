<?php
/**
 * WooCommerce Print Invoices/Packing Lists
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Print
 * Invoices/Packing Lists to newer versions in the future. If you wish to
 * customize WooCommerce Print Invoices/Packing Lists for your needs please refer
 * to http://docs.woocommerce.com/document/woocommerce-print-invoice-packing-list/
 *
 * @package   WC-Print-Invoices-Packing-Lists/Templates
 * @author    SkyVerge
 * @copyright Copyright (c) 2011-2017, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * PIP Documents Styles Template
 *
 * Use this template to override styles used in PIP documents.
 * However, you can also add more styles from PIP settings page
 * or hooking `wc_pip_styles` action without copying and editing
 * over this template.
 *
 * @type \WC_PIP_Document $document Document object
 *
 * @version 3.3.0
 * @since 3.0.0
 */

?>
<style type="text/css">


	/* ==========*
	 * HTML TAGS *
	 * ==========*/

	html, body {
		background: #FFFFFF;
	}

	body {
		display: block;
		color: #000000;
		font: normal 12px/150% Verdana, Arial, Helvetica, sans-serif;
		margin: 0 auto;
		-webkit-print-color-adjust: exact;
	}

	a {
		color: <?php echo get_option( 'wc_pip_link_color', '#000000' ); ?>;
	}

	hr {
		margin-top: 1em;
	}

	blockquote {
		border-left: 10px solid #DDD;
		color: #444444;
		font-style: italic;
		margin: 1.5em;
		padding-left: 10px;
	}

	h1, h2, h3, h4, h5, h6 {
		color: <?php echo get_option( 'wc_pip_headings_color', '#000000' ); ?>;
		line-height: 150%;
	}

	<?php $h_size = (int) get_option( 'wc_pip_heading_font_size', '28' ); $i = 0; ?>
	<?php foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $h ) : ?>
		<?php echo $h . ' { font-size: ' . ( $h_size - ( 4 * $i ) ) . 'px; } '; $i++; ?>
	<?php endforeach; ?>


	/* =============== *
	 * UTILITY CLASSES *
	 * =============== */

	.left {
		float: left;
	}

	.align-left {
		text-align: left;
	}

	.right {
		float: right;
	}

	.align-right {
		text-align: right;
	}

	.center {
		float: none;
		margin: 0 auto;
		text-align: center;
		width: 100%;
	}

	.align-center {
		text-align: center;
	}

	.clear {
		clear: both;
	}

	.containerr {
		background: #FFF;
                margin: 0 auto;
                padding:0;
	}


	/* ============= *
	 * ORDER DETAILS *
	 * ============= */

	.title a {
		font-size: <?php echo ( (int) get_option( 'wc_pip_heading_font_size', '28' ) + 4 ) . 'px'; ?>;
		font-weight: bold;
		text-decoration: none;
	}

	.title,
	.subtitle {
		margin: 0;
	}

	.left .logo {
		padding-right: 0;
                max-width: 100% !important;
                vertical-align: -webkit-baseline-middle;
                width: 45%;
                padding-top: 1rem;
	}

	.right .logo {
		padding-left: 1em;
	}

	.company-information {
		margin-bottom: 3em;
	}

	.company-address {
		font-style: normal;
		padding-top: 1em;
	}

	.customer-addresses {
		
	}

	.customer-addresses .column {
		padding: 0 15px;
		width: 33.33333333%;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	.document-heading {
		margin: 2em 0;
	}

	.order-info {
                font-size: 1.17em;
	}

	.order-date {
		color: #666666;
		margin: 0;
	}
        .date_order {
                font-size: 1.17em;
                margin:0 0 1rem;
                line-height: 1;
        }
        address {font-style:normal;}
	<?php if ( 1 === (int) get_option( 'wc_pip_return_policy_fine_print' ) ) : ?>
		.terms-and-conditions {
			font-size: 90%;
			line-height: 120%;
		}
	<?php endif; ?>

	span.coupon {
		background: #F4F4F4;
		color: #333;
		font-family: monospace;
		padding: 2px 4px;
	}


	/* ===== *
	 * LISTS *
	 * ===== */

	dl {
		margin: 1em 0;
	}

	dl.variation {
		font-size: 0.85em;
		margin: 5px 0 0 0;
	}

	dl.variation dt {
		display:inline-block;
		margin: 0 1px 0 0;
	}

	dl.variation dd {
		display:inline-block;
                margin: 0 3px 0 0;
	}
        dl.variation dd.variation-size:after {
              content:',';
        }
        dl.variation dd.variation-size:after:last-child {
              display:none;
        }

	dl.variation p {
		margin: 0;
                display: inline;
	}


	/* ============ *
	 * ORDER TABLES *
	 * ============ */

	table {
		border-collapse: collapse;
		font: normal <?php echo get_option( 'wc_pip_body_font_size', '11' ); ?>px Verdana, Arial, Helvetica, sans-serif;
		margin: 3em 0 2em;
		text-align: left;
		width: 100%;
	}

	table td, table th {
		color: #000000;
                border: 2px solid #FFFFFF;
	    font-weight: normal;
	    background-color: #F5F5F5;
	    -webkit-print-color-adjust: exact;
	    vertical-align: top;
	    padding: 3px;
	}
	table th {
		font-weight: bold;
		-webkit-print-color-adjust: exact;
	}

	table thead.order-table-head th {
		background-color: #576099;
                color: #FFFFFF;
                font-size: 14px;
                font-weight: bold;
                border-left: 2px solid #FFFFFF;
                -webkit-print-color-adjust: exact;
	}

	table tbody th a {
		color: #333333;
		font-weight: bold;
	}

	table tfoot td {
		text-align: right;
		background: none;
	}

        table tfoot td.value {
		background-color: #F5F5F5;
		text-align: left;
	}

	table tbody tr.heading th {
		background-color: #666666;
		border-color: #666666;
		color: #FFFFFF;
	}

	table tbody tr.heading th.order-number a {
		color: #FFF;
		font-weight: bold;
		text-decoration: none;
	}

	table tbody tr.heading th.no-items {
		background-color: #A0A0A0;
		font-weight: 400;
	}

	table tbody tr.heading th.breadcrumbs {
		background-color: #D8D8D8;
		border-color: #D8D8D8;
		color: #666666;
		font-weight: normal;
	}


	/* ============ *
	 * PRINT STYLES *
	 * ============ */

	@media print {

		/* Background is always white in print */
		html, body {
			background: #FFFFFF;
		}

		a {
			text-decoration: none;
		}

		/* Break pages when printing multiple documents */
		.containerr {
			page-break-after: always;
		}
		.containerr:last-child {
			page-break-after: auto;
		}

		table td, table th {
			padding: 0.4em 1.2em;
		}

		/* Print URL after link text */
		.document-heading a:after,
		.document-footer a:after {
			content: " (" attr(href) ")";
		}
	}
	
	/* ============ *
	 * PRINT STYLES CUSTOM *
	 * ============ */
	.address_cust {padding-bottom: 10px;}
	.address_cust p {
	    font-size: 20px;
	    font-weight: bold;
	    line-height: 22px;
	    margin: 0.5rem 0;
	}
	.pagebreak {page-break-after: always;}
	.customer-addresses h3 {
           margin-top: 0;
	    font-size:1.17em;
        }
        .customer-addresses p {
           margin: 0.5rem 0;
        }
        .document-body table,.datagrid table {margin:0;}
        .terms-and-conditions br {display:none;}
        .terms-and-conditions br:nth-child(3n) {display:block;}
        footer hr {display:none;}
	<?php
		/**
		 * Fires inside the document's `<style>` element to allow for custom CSS.
		 *
		 * @since 3.0.0
		 */
		do_action( 'wc_pip_styles' );
	?>
</style>
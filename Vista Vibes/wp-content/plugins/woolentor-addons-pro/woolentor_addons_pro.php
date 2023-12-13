<?php
/**
 * Plugin Name: ShopLentor Pro – WooCommerce Builder for Elementor & Gutenberg
 * Description: An all-in-one WooCommerce solution to create a beautiful WooCommerce store.
 * Plugin URI: 	https://woolentor.com/
 * Version: 	2.2.9
 * Author: 		HasThemes
 * Author URI: 	https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/
 * License:  	GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: woolentor-pro
 * Domain Path: /languages
 * WC tested up to: 8.3.1
 * Elementor tested up to: 3.17.3
 * Elementor Pro tested up to: 3.17.1
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

update_option( 'WooLentorPro_lic_Key', 'activated' );
update_option( 'WooLentorPro_lic_email', 'noreply@gmail.com' );

define( 'WOOLENTOR_VERSION_PRO', '2.2.9' );
define( 'WOOLENTOR_ADDONS_PL_ROOT_PRO', __FILE__ );
define( 'WOOLENTOR_ADDONS_PL_URL_PRO', plugins_url( '/', WOOLENTOR_ADDONS_PL_ROOT_PRO ) );
define( 'WOOLENTOR_ADDONS_PL_PATH_PRO', plugin_dir_path( WOOLENTOR_ADDONS_PL_ROOT_PRO ) );
define( 'WOOLENTOR_ADDONS_DIR_URL_PRO', plugin_dir_url( WOOLENTOR_ADDONS_PL_ROOT_PRO ) );
define( 'WOOLENTOR_TEMPLATE_PRO', trailingslashit( WOOLENTOR_ADDONS_PL_PATH_PRO . 'includes/templates' ) );

// Required File
require_once ( WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/base.php' );
\WooLentorPro\woolentor_pro();

// Compatible With WooCommerce Custom Order Tables
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
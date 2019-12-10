<?php
/**
 * Plugin Name:	HM Product Catalog
 * Plugin URI:	http://wordpress.org/plugins/hm-product-catalog/
 * Description:	A simple plugin to display products in your Post/Page area. Use shortcode: [hm_product_catalog]
 * Version:		1.2
 * Author:		Hossni Mubarak
 * Author URI:	http://www.hossnimubarak.com
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WPHPC_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPHPC_ASSETS', plugins_url( '/assets/', __FILE__ ) );
define( 'WPHPC_SLUG', plugin_basename( __FILE__ ) );
define( 'WPHPC_PRFX', 'wphpc_' );
define( 'WPHPC_CLS_PRFX', 'cls-hm-product-catalog-' );
define( 'WPHPC_TXT_DOMAIN', 'hm-product-catalog' );
define( 'WPHPC_VERSION', '1.2' );

require_once WPHPC_PATH . 'inc/' . WPHPC_CLS_PRFX . 'master.php';
$wphpc = new WPHPC_Master();
$wphpc->wphpc_run();
register_deactivation_hook( __FILE__, array($wphpc, WPHPC_PRFX . 'unregister_settings') );
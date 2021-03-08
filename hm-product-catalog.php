<?php
/**
 * Plugin Name:	HM Product Catalog
 * Plugin URI:	https://wordpress.org/plugins/hm-product-catalog/
 * Description:	A simple plugin to display products in your Post/Page area. Use shortcode: [hm_product_catalog]
 * Version:		  1.5
 * Author:		  HM Plugin
 * Author URI:	https://hmplugin.com
 * License:		  GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( ! defined('ABSPATH') ) exit;

define('WPHPC_PATH', plugin_dir_path(__FILE__));
define('WPHPC_ASSETS', plugins_url('/assets/', __FILE__));
define('WPHPC_SLUG', plugin_basename(__FILE__));
define('WPHPC_PRFX', 'wphpc_');
define('WPHPC_CLS_PRFX', 'cls-hmpc-');
define('WPHPC_TXT_DOMAIN', 'hm-product-catalog');
define('WPHPC_VERSION', '1.5');

require_once WPHPC_PATH . 'inc/' . WPHPC_CLS_PRFX . 'master.php';
$wphpc = new WPHPC_Master();
$wphpc->wphpc_run();

// Donate link to plugin description
function wphpc_donation_link_to_plugin_active( $links, $file ) {

  if ( WPHPC_SLUG === $file ) {
    $row_meta = array(
      'wphpc_donation_link'  => '<a href="' . esc_url( 'https://www.paypal.me/mhmrajib/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Donate us', 'hm-product-catalog' ) . '" style="color:green; font-weight: bold;">' . esc_html__( 'Donate us', 'hm-product-catalog' ) . '</a>'
    );

    return array_merge( $links, $row_meta );
  }
  return (array) $links;
}
add_filter( 'plugin_row_meta', 'wphpc_donation_link_to_plugin_active', 10, 2 );
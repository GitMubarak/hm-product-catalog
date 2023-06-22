<?php
/**
 * Plugin Name:	      HM Product Catalog
 * Plugin URI:	      https://wordpress.org/plugins/hm-product-catalog/
 * Description:	      Product Showcase & Affiliat Plugin for WordPress to display products in your webpage.
 * Version:		        1.7.2
 * Author:		        HM Plugin
 * Author URI:	      https://hmplugin.com
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Tested up to:      6.2.2
 * Text Domain:       hm-product-catalog
 * Domain Path:       /languages/
 * License:		        GPL-2.0+
 * License URI:	      http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( ! defined('ABSPATH') ) exit;

define('WPHPC_PATH', plugin_dir_path(__FILE__));
define('WPHPC_ASSETS', plugins_url('/assets/', __FILE__));
define('WPHPC_SLUG', plugin_basename(__FILE__));
define('WPHPC_PRFX', 'wphpc_');
define('WPHPC_CLS_PRFX', 'cls-hmpc-');
define('WPHPC_TXT_DOMAIN', 'hm-product-catalog');
define('WPHPC_VERSION', '1.7.2');

require_once WPHPC_PATH . 'inc/' . WPHPC_CLS_PRFX . 'master.php';
$wphpc = new WPHPC_Master();
$wphpc->wphpc_run();

// Donate link to plugin description
function wphpc_donation_link_to_plugin_active( $links, $file ) {

  if ( WPHPC_SLUG === $file ) {
    $row_meta = array(
      'wphpc_donation_link'  => '<a href="' . esc_url( 'https://www.paypal.me/mhmrajib/' ) . '" target="_blank" aria-label="' . __( 'Donate us', 'hm-product-catalog' ) . '" style="color:green; font-weight: bold;">' . __( 'Donate us', 'hm-product-catalog' ) . '</a>'
    );

    return array_merge( $links, $row_meta );
  }
  return (array) $links;
}
add_filter( 'plugin_row_meta', 'wphpc_donation_link_to_plugin_active', 10, 2 );

// Add Columns To Custom Post Types
function wphpc_add_products_columns( $columns ) {
  $columns['sku']    = 'SKU';
  $columns['stock'] = 'Stock';
  return $columns;
}
add_filter('manage_products_posts_columns' , 'wphpc_add_products_columns');

// Add Data To Custom Post Type Columns
function wphpc_products_column_data( $column, $post_id ) {
  switch ( $column ) {
    case 'sku':
      echo get_post_meta( $post_id , 'wphpc_sku' , true );
      break;
    case 'stock':
      echo ( 'in' !== get_post_meta( $post_id , 'wphpc_stock_status' , true ) ) ? '<b style="color:red;">Out of stock</b>' : '<b style="color:green;">In stock</b>';
      break;
  }
}
add_action( 'manage_products_posts_custom_column' , 'wphpc_products_column_data', 10, 2 );


// Sorting Column
function wphpc_prducts_table_sorting( $columns ) {
  $columns['sku'] = 'sku';
  $columns['stock'] = 'stock';
  return $columns;
}
add_filter( 'manage_edit-products_sortable_columns', 'wphpc_prducts_table_sorting' );
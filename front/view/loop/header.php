<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$wphpcGeneralSettings   = stripslashes_deep( unserialize( get_option('wphpc_general_settings') ) );
$wphpcCurrency          = isset( $wphpcGeneralSettings['wphpc_currency'] ) ? $wphpcGeneralSettings['wphpc_currency'] : '';
$wphpcDetailsIsSxternal = ($wphpcGeneralSettings['wphpc_details_is_external'] == 1) ? ' target="_blank"' : '';
$wphpcCatLbl            = ($wphpcGeneralSettings['wphpc_cat_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_cat_label_txt'] : '';
$wphpcPriceLbl          = ($wphpcGeneralSettings['wphpc_price_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_price_label_txt'] : '';
$wphpcProductColumn     = ($wphpcGeneralSettings['wphpc_product_column'] != '') ? $wphpcGeneralSettings['wphpc_product_column'] : 4;

$wphpcCurrencySymbol    = $this->get_currency_symbol( $wphpcCurrency );

$wphpcColumn = isset( $attr['column'] ) ? $attr['column'] : $wphpcProductColumn;

// Setting pagination query params for home and other page
if ( is_front_page() ) {
    $wphpcPaged           = ( get_query_var('page') ) ? get_query_var('page') : 1;
} else {
    $wphpcPaged           = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
}

// Assign all shortcode params
$wphpcCatelog 		= isset( $attr['catalog']) ? $attr['catalog'] : '';
$wphpcDisplay		= isset( $attr['display']) ? $attr['display'] : '';
$wphpcPagination	= isset( $attr['pagination']) ? $attr['pagination'] : false;

$wphpcProductsArr = array(
    'post_type' => 'products',
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => 'wphpc_status',
            'value' => 'active',
            'compare' => '='
        )
    ),
);

// Sorting and search operation started
// if your meta value is not numeric for example if it is a date value; than you can add parameter meta_type => DATE
// Possible values are; 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'
if ( isset( $_GET['sorting-by'] ) && ( $_GET['sorting-by'] === 'price' ) ) {
    $wphpcProductsArr['meta_key'] = 'wphpc_regular_price';
    $wphpcProductsArr['orderby'] = 'meta_value_num';
    $wphpcProductsArr['meta_type'] = 'DECIMAL';
    $wphpcProductsArr['order'] = 'ASC';
}
if ( isset( $_GET['sorting-by'] ) && ( $_GET['sorting-by'] === 'price-desc' ) ) {
    $wphpcProductsArr['meta_key'] = 'wphpc_regular_price';
    $wphpcProductsArr['orderby'] = 'meta_value_num';
    $wphpcProductsArr['meta_type'] = 'DECIMAL';
    $wphpcProductsArr['order'] = 'DESC';
}
if ( isset( $_GET['sorting-by'] ) && ( $_GET['sorting-by'] === 'date' ) ) {
    $wphpcProductsArr['orderby'] = 'date';
    $wphpcProductsArr['order'] = 'DESC';
    $wphpcProductsArr['suppress_filters'] = true;
}
if ( isset( $_GET['sorting-by'] ) && ( $_GET['sorting-by'] === 'default' ) ) {
    $wphpcProductsArr['orderby'] = 'date';
    $wphpcProductsArr['order'] = 'DESC';
    $wphpcProductsArr['suppress_filters'] = true;
}

$get_category =  isset( $_GET['category'] ) ? esc_html( $_GET['category'] ) : '';

if ( $get_category != '' ) {
    $wphpcProductsArr['tax_query'] = array(
        array(
            'taxonomy' => 'product_category',
            'field' => 'name',
            'terms' => urldecode( $get_category )
        )
    );
}
// Sorting and search operation ended


if ( $wphpcCatelog != '' ) {
    $wphpcProductsArr['tax_query'] = array(
        array(
            'taxonomy' => 'product_catalog',
            'field' => 'name',
            'terms' => $wphpcCatelog
        )
    );
}

if ( $wphpcDisplay != '' ) {
    $wphpcProductsArr['posts_per_page'] = $wphpcDisplay;
}

if ( $wphpcPagination == 'true' ) {
    $wphpcProductsArr['paged'] = $wphpcPaged;
}

$wphpcProducts = new WP_Query( $wphpcProductsArr );
?>
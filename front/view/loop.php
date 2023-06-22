<?php
if ( ! defined('ABSPATH') ) exit;

global $post;

$wphpcGeneralSettings   = stripslashes_deep( unserialize( get_option('wphpc_general_settings') ) );
$wphpcCurrency          = isset( $wphpcGeneralSettings['wphpc_currency'] ) ? $wphpcGeneralSettings['wphpc_currency'] : '';
$wphpcDetailsIsSxternal = ($wphpcGeneralSettings['wphpc_details_is_external'] == 1) ? ' target="_blank"' : '';
$wphpcCatLbl            = ($wphpcGeneralSettings['wphpc_cat_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_cat_label_txt'] : '';
$wphpcPriceLbl          = ($wphpcGeneralSettings['wphpc_price_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_price_label_txt'] : '';
$wphpcProductColumn     = ($wphpcGeneralSettings['wphpc_product_column'] != '') ? $wphpcGeneralSettings['wphpc_product_column'] : 4;

$wphpcCurrencySymbol    = $this->get_currency_symbol( $wphpcCurrency );

$wphpcColumn = isset( $attr['column'] ) ? $attr['column'] : $wphpcProductColumn;

if ( is_front_page() ) {
 
  $wphpcPaged           = ( get_query_var('page') ) ? get_query_var('page') : 1;
 
} else {

  $wphpcPaged           = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

}

$wphpcProductsArr = array(
  'post_type' => 'products',
  'post_status' => 'publish',
  'meta_query' => array(
    array(
      'key' => 'wphpc_status',
      'value' => 'active',
      'compare' => '='
  )),
);

// Sorting operation
// if your meta value is not numeric for example if it is a date value; than you can add parameter meta_type => DATE
// Possible values are; 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'
if ( isset( $_GET['orderby'] ) && ( $_GET['orderby'] === 'price' ) ) {
  $wphpcProductsArr['meta_key'] = 'wphpc_regular_price';
  $wphpcProductsArr['orderby'] = 'meta_value_num';
  $wphpcProductsArr['meta_type'] = 'DECIMAL';
  $wphpcProductsArr['order'] = 'ASC';
}
if ( isset( $_GET['orderby'] ) && ( $_GET['orderby'] === 'price-desc' ) ) {
  $wphpcProductsArr['meta_key'] = 'wphpc_regular_price';
  $wphpcProductsArr['orderby'] = 'meta_value_num';
  $wphpcProductsArr['meta_type'] = 'DECIMAL';
  $wphpcProductsArr['order'] = 'DESC';
}
if ( isset( $_GET['orderby'] ) && ( $_GET['orderby'] === 'date' ) ) {
  $wphpcProductsArr['orderby'] = 'date';
  $wphpcProductsArr['order'] = 'DESC';
  $wphpcProductsArr['suppress_filters'] = true;
}
if ( isset( $_GET['orderby'] ) && ( $_GET['orderby'] === 'default' ) ) {
  $wphpcProductsArr['orderby'] = 'date';
  $wphpcProductsArr['order'] = 'DESC';
  $wphpcProductsArr['suppress_filters'] = true;
}


if ($wphpcDisplay != '') {
  $wphpcProductsArr['posts_per_page'] = $wphpcDisplay;
}

if ($wphpcCatelog != '') {
  $wphpcProductsArr['tax_query'] = array(
    array(
      'taxonomy' => 'product_catalog',
      'field' => 'name',
      'terms' => $wphpcCatelog
    )
  );
}

if ( $wphpcPagination == 'true' ) {
  $wphpcProductsArr['paged'] = $wphpcPaged;
}

$wphpcProducts = new WP_Query( $wphpcProductsArr );

if ( $wphpcProducts->have_posts() ) {
    
  $wphpc_prev_posts = ( $wphpcPaged - 1 ) * $wphpcProducts->query_vars['posts_per_page'];
  $wphpc_from       = 1 + $wphpc_prev_posts;
  $wphpc_to         = count( $wphpcProducts->posts ) + $wphpc_prev_posts;
  $wphpc_of         = $wphpcProducts->found_posts;
  ?>
  <div class="item-sorting clearfix">
    <div class="result-column pull-left">
      <div class="text">Showing <span><?php printf( '%s-%s of %s', $wphpc_from, $wphpc_to, $wphpc_of ); ?></span> Results</div>
    </div>
    <div class="select-column select-box pull-right">
      <select class="selectmenu" id="ui-id-1" style="display: none;">
        <option value="">Default Sorting</option>
        <option value="date" <?php echo ( isset( $_GET['orderby'] ) && ( $_GET['orderby'] === 'date' ) ) ? 'selected' :''; ?> >Sort by latest</option>
        <option value="price-low" <?php echo ( isset( $_GET['orderby'] ) && ( $_GET['orderby'] === 'price' ) ) ? 'selected' :''; ?> >Sort by price: low to high</option>
        <option value="price-high" <?php echo ( isset( $_GET['orderby'] ) && ( $_GET['orderby'] === 'price-desc' ) ) ? 'selected' :''; ?> >Sort by price: high to low</option>
      </select>
    </div>
  </div>
  <div class="wphpc-main-wrapper <?php esc_attr_e('wphpc-product-column-' . $wphpcColumn); ?>">
      <?php 
      while ( $wphpcProducts->have_posts() ) {

        $wphpcProducts->the_post();
        ?>
        <div class="wphpc-item">
          <div class="wphpc-image-box">
            <?php
            if ( has_post_thumbnail() ) {
              $attachment_ids[0]  = get_post_thumbnail_id( $post->ID );
              $attachment         = wp_get_attachment_image_src( $attachment_ids[0], 'medium' );
              $wphpc_image_url    = $attachment[0];
            } else { 
              $wphpc_image_url    = '';
            }
            ?>
            <img src="<?php echo esc_url( $wphpc_image_url ); ?>" alt="<?php the_title(); ?>">
          </div>
          <a href="<?php echo get_the_permalink( $post->ID ); ?>" class="wphpc-product-title-link" <?php printf('%s', $wphpcDetailsIsSxternal); ?>>
            <?php
            $wphpcTitleLen = strlen( get_the_title() );
            if ( $wphpcTitleLen > 50 ) {
              echo substr( get_the_title(), 0, 40 ) . '...';
            } else {
              the_title();
            }
            ?>
          </a>
          <span>
            <?php echo esc_html( $wphpcCatLbl ); ?>
            <?php
            $wphpcCatArray = array();
            $wphpcCategory = wp_get_post_terms( $post->ID, 'product_category', array('fields' => 'all') );
            foreach( $wphpcCategory as $cat) {
                $wphpcCatArray[] = $cat->name . '';
            }
            echo implode( ', ', $wphpcCatArray );
            ?>
          </span>
          <div class="regular-price">
            <?php echo esc_html( $wphpcPriceLbl ); ?>
            <?php
            $wphpcRegularPrice  = get_post_meta($post->ID, 'wphpc_regular_price', true);
            $wphpcSalePrice     = get_post_meta($post->ID, 'wphpc_sale_price', true);
            if ( empty( $wphpcSalePrice ) ) {
              echo ( ! empty( $wphpcRegularPrice ) ) ? '<span class="wphpc-price price-after">' . esc_html( $wphpcCurrencySymbol ) . '&nbsp;' . $wphpcRegularPrice . '</span>' : '';
            } else {
              echo '<span class="wphpc-price price-before">' . esc_html( $wphpcCurrencySymbol ) . '&nbsp;' . $wphpcRegularPrice . '</span> <span class="wphpc-price price-after">' . esc_html( $wphpcCurrencySymbol ) . '&nbsp;' . $wphpcSalePrice . '</span>';
            }
            ?>
          </div>

        </div>
        <?php 
      } // End While
      ?>
  </div>
  <?php 
  if ( $wphpcPagination == 'true' ) { 
    ?>
    <div class="wphpc-pagination">
        <?php

        $wphpcTotalPages = $wphpcProducts->max_num_pages;

        if ( $wphpcTotalPages > 1 ) {

          $wphpcPaginateBig = 999999999;
          $wphpcPaginateArgs = array(
              'base' => str_replace( $wphpcPaginateBig, '%#%', esc_url( get_pagenum_link( $wphpcPaginateBig ) ) ),
              'format' => '?page=%#%',
              'total' => $wphpcTotalPages,
              'current' => max( 1, $wphpcPaged ),
              'end_size' => 1,
              'mid_size' => 2,
              'prev_text' => __('« '),
              'next_text' => __(' »'),
              'type' => 'list',
          );
          echo paginate_links( $wphpcPaginateArgs );
        }
        
        ?>
    </div>
  <?php 
  }
} else {
  ?><p class="wphpc-no-products-found"><?php _e('No products found!', 'hm-product-catalog'); ?></p><?php
}

wp_reset_postdata();
?>
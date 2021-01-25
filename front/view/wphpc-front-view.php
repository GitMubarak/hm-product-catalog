<?php
if ( ! defined('ABSPATH') ) exit;

$wphpcGeneralSettings = stripslashes_deep(unserialize(get_option('wphpc_general_settings')));
$wphpcCurrency = ($wphpcGeneralSettings['wphpc_currency'] != '') ? $wphpcGeneralSettings['wphpc_currency'] : '';
$wphpcDetailsIsSxternal = ($wphpcGeneralSettings['wphpc_details_is_external'] == 1) ? ' target="_blank"' : '';
$wphpcCatLbl = ($wphpcGeneralSettings['wphpc_cat_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_cat_label_txt'] : '';
$wphpcPriceLbl = ($wphpcGeneralSettings['wphpc_price_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_price_label_txt'] : '';
$wphpcProductColumn = ($wphpcGeneralSettings['wphpc_product_column'] != '') ? $wphpcGeneralSettings['wphpc_product_column'] : 4;

$wphpcColumn = isset( $attr['column'] ) ? $attr['column'] : $wphpcProductColumn;

$wphpcPaged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$wphpcProductsArr = array(
  'post_type' => 'products',
  'post_status' => 'publish',
  'order' => 'DESC',
  //'posts_per_page' => 10
  'meta_query' => array(
    array(
      'key' => 'wphpc_status',
      'value' => 'active',
      'compare' => 'LIKE'
    )
  )
);

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
  ?>
  <div class="wphpc-main-wrapper <?php esc_attr_e('wphpc-product-column-' . $wphpcColumn); ?>">
      <?php 
      while ( $wphpcProducts->have_posts() ) {
        $wphpcProducts->the_post();
        global $post; 
        ?>
        <div class="wphpc-item">
          <?php
          if ( has_post_thumbnail() ) {
            the_post_thumbnail();
          } else { 
            ?>
            <img src="img_snow.jpg" alt="Snow">
            <?php 
          } 
          ?>
            <a href="<?php echo get_the_permalink($post->ID); ?>" <?php printf('%s', $wphpcDetailsIsSxternal); ?>>
                <?php
            $wphpcTitleLen = strlen(get_the_title());
            if ($wphpcTitleLen > 50) {
              echo substr(get_the_title(), 0, 40) . '...';
            } else {
              the_title();
            }
            ?>
            </a>
            <span>
                <?php echo esc_html($wphpcCatLbl); ?>
                <?php
            $wphpcCategory = wp_get_post_terms($post->ID, 'product_category', array('fields' => 'all'));
            echo $wphpcCategory[0]->name;
            ?>
            </span>
            <div class="regular-price">
                <?php echo esc_html($wphpcPriceLbl); ?>
                <?php
            $wphpcRegularPrice = get_post_meta($post->ID, 'wphpc_regular_price', true);
            $wphpcSalePrice = get_post_meta($post->ID, 'wphpc_sale_price', true);
            if (empty($wphpcSalePrice)) {
              echo (!empty($wphpcRegularPrice)) ? '<span class="price-after">' . $wphpcRegularPrice . '</span>' : '';
            } else {
              echo '<span class="price-before">' . $wphpcRegularPrice . '</span> <span class="price-after">' . $wphpcSalePrice . '</span>';
            }
            echo '&nbsp;' . esc_html($wphpcCurrency);
            ?>
            </div>

        </div>
        <?php 
      } 
      ?>
  </div>
  <?php 
  if ($wphpcPagination == 'true') { 
    ?>
    <div class="wphpc-pagination">
        <?php
        $wphpcTotalPages = $wphpcProducts->max_num_pages;

        if ( $wphpcTotalPages > 1 ) {

          $wphpcCurrentPage = max(1, get_query_var('paged'));

          echo paginate_links(array(
            'base'      => get_pagenum_link(1) . '%_%',
            'format'    => '/page/%#%',
            'current'   => $wphpcCurrentPage,
            'total'     => $wphpcTotalPages,
            'prev_text' => __('« '),
            'next_text' => __(' »'),
          ));
        }
        ?>
    </div>
  <?php 
  }
} else {
  ?><p class="wphpc-no-products-found"><?php _e('No products found.', WPHPC_TXT_DOMAIN); ?></p><?php
}

wp_reset_postdata();
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

include 'loop/header.php';

if ( $wphpcProducts->have_posts() ) {
  
    $wphpc_prev_posts = ( $wphpcPaged - 1 ) * $wphpcProducts->query_vars['posts_per_page'];
    $wphpc_from       = 1 + $wphpc_prev_posts;
    $wphpc_to         = count( $wphpcProducts->posts ) + $wphpc_prev_posts;
    $wphpc_of         = $wphpcProducts->found_posts;
    ?>
    <div class="hmpc-loop-parent-wrapper clearfix">
      <div class="hmpc-loop-parent-left pull-left">
        <?php
        if ( function_exists( 'register_sidebar' ) ) {
            dynamic_sidebar( 'Product Catalog Sidebar' );
        }
        ?>
        <div style="width: 100%;">
          <form method="get" action="<?php echo get_permalink(); ?>">
            <div class="price_slider_wrapper" id="price_slider_wrapper">
              <div class="price_slider_amount" data-step="10">
                <input type="text" id="min_price" name="min_price" value="0" data-min="0" placeholder="Min price" style="display: none;">
                <input type="text" id="max_price" name="max_price" value="500" data-max="500" placeholder="Max price" style="display: none;">
                <button type="submit" class="button filter-btn">Filter</button>
                <div class="price_label" style="">
                  <span class="from">৳&nbsp;0</span> — <span class="to">৳&nbsp;500</span>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="hmpc-loop-parent-right pull-right">
        <div class="item-sorting clearfix">
          <div class="result-column pull-left">
            <div class="text">Showing <span><?php printf( '%s-%s of %s', $wphpc_from, $wphpc_to, $wphpc_of ); ?></span> Results</div>
          </div>
          <div class="select-column select-box pull-right">
            <select class="selectmenu" id="ui-id-1" style="display: none;">
              <option value="">Default Sorting</option>
              <option value="date" <?php echo ( isset( $_GET['sorting-by'] ) && ( $_GET['sorting-by'] === 'date' ) ) ? 'selected' :''; ?> >Sort by latest</option>
              <option value="price-low" <?php echo ( isset( $_GET['sorting-by'] ) && ( $_GET['sorting-by'] === 'price' ) ) ? 'selected' :''; ?> >Sort by price: low to high</option>
              <option value="price-high" <?php echo ( isset( $_GET['sorting-by'] ) && ( $_GET['sorting-by'] === 'price-desc' ) ) ? 'selected' :''; ?> >Sort by price: high to low</option>
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
                  $wphpc_image_url = WPHPC_ASSETS . 'img/no-image.jpg';
                  if ( get_the_post_thumbnail( get_the_ID() ) ) {
                    $wphpc_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
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
                  <?php esc_html_e( $wphpcCatLbl ); ?>
                  <?php
                  $wphpcCatArray = [];
                  $wphpcCategory = wp_get_post_terms( $post->ID, 'product_category', array('fields' => 'all') );
                  foreach( $wphpcCategory as $cat) {
                      $wphpcCatArray[] = $cat->name . '';
                  }
                  echo implode( ', ', $wphpcCatArray );
                  ?>
                </span>
                <div class="regular-price">
                  <?php esc_html_e( $wphpcPriceLbl ); ?>
                  <?php
                  $wphpcRegularPrice  = get_post_meta($post->ID, 'wphpc_regular_price', true);
                  $wphpcSalePrice     = get_post_meta($post->ID, 'wphpc_sale_price', true);
                  if ( empty( $wphpcSalePrice ) ) {
                    echo ( ! empty( $wphpcRegularPrice ) ) ? '<span class="wphpc-price price-after">' . esc_html( $wphpcCurrencySymbol ) . $wphpcRegularPrice . '</span>' : '';
                  } else {
                    echo '<span class="wphpc-price price-before">' . esc_html( $wphpcCurrencySymbol ) . $wphpcRegularPrice . '</span> <span class="wphpc-price price-after">' . esc_html( $wphpcCurrencySymbol ) . $wphpcSalePrice . '</span>';
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
        ?>
      </div>
    </div>
    <?php
    wp_reset_postdata();
} else {
  ?><p class="wphpc-no-products-found"><?php _e('No products found!', 'hm-product-catalog'); ?></p><?php
}
?>
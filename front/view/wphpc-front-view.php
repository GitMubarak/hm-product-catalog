<?php
$wphpcCatelog =  isset($attr['catalog']) ? $attr['catalog'] : '';
$wphpcDisplay = isset($attr['display']) ? $attr['display'] : '';
$wphpcPagination = isset($attr['pagination']) ? $attr['pagination'] : false;
$wphpcPaged = (get_query_var('paged')) ? get_query_var('paged') : 1;
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

if($wphpcDisplay!=''){
  $wphpcProductsArr['posts_per_page'] = $wphpcDisplay;
}

if($wphpcCatelog!=''){
  $wphpcProductsArr['tax_query'] = array(
                                          array(
                                                  'taxonomy' => 'product_catalog',   // taxonomy name
                                                  //'field' => 'portfolio categories',    // term_id, slug or name
                                                  //'terms' => 'portfolio-category',      // term id, term slug or term name
                                                  'field' => 'name',
                                                  'terms' => $wphpcCatelog
                                              )
                                          );
}

if($wphpcPagination=='true'){
  $wphpcProductsArr['paged'] = $wphpcPaged;
}
wp_reset_query();
$wphpcProducts = new WP_Query($wphpcProductsArr);
?>

<div class="wphpc-main-wrapper w3-row-padding w3-padding-16 w3-center">
  <?php while($wphpcProducts->have_posts()) : $wphpcProducts->the_post(); global $post; ?>
  <div class="wphpc-item w3-quarter">
    <?php
    if ( has_post_thumbnail() ) {
        the_post_thumbnail();
    }
    else { ?>
      <img src="img_snow.jpg" alt="Snow">
    <?php } ?>
    <a href="<?php echo get_the_permalink($post->ID); ?>" target="blank">
        <?php 
          $wphpcTitleLen = strlen(get_the_title());
          if($wphpcTitleLen > 50){
            echo substr(get_the_title(), 0, 40) . '...';
          } else{
            the_title();
          }
        ?>
    </a>
    <span>
      Category:
      <?php
      $wphpcCategory = wp_get_post_terms( $post->ID, 'product_category', array( 'fields' => 'all' ) );
      echo $wphpcCategory[0]->name;
      ?>
    </span>
    <div class="regular-price">
      Price:
      <?php
      $wphpcRegularPrice = get_post_meta( $post->ID, 'wphpc_regular_price', true );
      $wphpcSalePrice = get_post_meta( $post->ID, 'wphpc_sale_price', true );
      if ( empty( $wphpcSalePrice ) ){
        echo ( !empty( $wphpcRegularPrice ) ) ? '<span class="price-after">' . $wphpcRegularPrice . '</span>' : '';
      } else {
        echo '<span class="price-before">' . $wphpcRegularPrice . '</span> <span class="price-after">' . $wphpcSalePrice . '</span>';
      }
      $wphpcCurrency = get_post_meta( $post->ID, 'wphpc_currency', true );
      if ( !empty( $wphpcCurrency ) ){
          echo ' ' . $wphpcCurrency;
      }
      ?>
    </div>
    
  </div>
  <?php endwhile; ?>
</div>
<?php if($wphpcPagination=='true'){ ?>
<div class="wphpc-pagination">
      <?php
      $wphpcTotalPages = $wphpcProducts->max_num_pages;

      if ($wphpcTotalPages > 1){
  
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
      wp_reset_postdata();
      ?>
</div>
<?php } ?>
<?php get_header(); ?>

<div class="wphpc-details-wrapper">

<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

    <div class="wphpc-details-image">
        <?php
        if ( has_post_thumbnail() ) {
            the_post_thumbnail();
        }
        else { ?>
        <img src="img_snow.jpg" alt="Snow" style="width:100%">
        <?php 
        } ?>
    </div>
    <div class="wphpc-details-description">
        <h5 class="wphpc-details-book-title"><?php the_title(); ?></h5>
        <span>
            <b>SKU:</b>
            <?php
            $wphpcSku = get_post_meta( $post->ID, 'wphpc_sku', true );
            if ( !empty( $wphpcSku ) ){
                echo $wphpcSku;
            }
            ?>
        </span>
        <span>
            <b>Category:</b>
            <?php
            $wphpcCategory = wp_get_post_terms( $post->ID, 'product_category', array( 'fields' => 'all' ) );
            echo $wphpcCategory[0]->name;
            ?>
        </span>
        <span>
            <b>Regular Price:</b>
            <?php
            $wphpcCurrency = get_post_meta( $post->ID, 'wphpc_currency', true );
            $wphpcRegularPrice = get_post_meta( $post->ID, 'wphpc_regular_price', true );
            if ( !empty( $wphpcRegularPrice ) ){
                echo $wphpcCurrency . ' ' . $wphpcRegularPrice;
            }
            ?>
        </span>
        <?php
        $wphpcSalePrice = get_post_meta( $post->ID, 'wphpc_sale_price', true );
        if ( !empty( $wphpcSalePrice ) ){
            echo '<span class="sale-price"><b>Sale Price:</b> <b>' . $wphpcCurrency . ' ' . $wphpcSalePrice . '</b></span>';
        }
        ?>
    </div>
    <div class="wphpc-details-description-full">
        <div class="wphpc-details-description-title">
            <b>Description:</b>
            <hr>
        </div>
        <div class="wphpc-details-description-content">
            <?php the_content(); ?>
        </div>
    </div>
<?php endwhile; endif; ?>

</div>

<?php get_footer(); ?>
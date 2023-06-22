<?php
/**
 * Template Name: Product Catalog Single
 *
 */
get_header();

require_once WPHPC_PATH . 'front/' . WPHPC_CLS_PRFX . 'front.php';
$wphpcFrontObj = new WPHPC_Front( WPHPC_VERSION );

// General settings data
$wphpcGeneralSettings 	= stripslashes_deep( unserialize( get_option('wphpc_general_settings') ) );
$wphpcCurrency 			= isset( $wphpcGeneralSettings['wphpc_currency'] ) ? $wphpcGeneralSettings['wphpc_currency'] : '';
$wphpcCatLbl 			= ($wphpcGeneralSettings['wphpc_cat_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_cat_label_txt'] : '';
$wphpcPriceLbl 			= ($wphpcGeneralSettings['wphpc_price_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_price_label_txt'] : '';
$wphpc_details_is_external  = ($wphpcGeneralSettings['wphpc_details_is_external'] != '') ? $wphpcGeneralSettings['wphpc_details_is_external'] : '';
$wphpcCurrencySymbol    = $wphpcFrontObj->get_currency_symbol( $wphpcCurrency );

// Meta data
$wphpcRegularPrice      = get_post_meta( $post->ID, 'wphpc_regular_price', true );
$wphpcSalePrice         = get_post_meta( $post->ID, 'wphpc_sale_price', true );
$wphpc_product_type     = get_post_meta( $post->ID, 'wphpc_product_type', true );
$wphpc_product_url      = get_post_meta( $post->ID, 'wphpc_product_url', true );
$wphpcShortDescription  = get_post_meta( $post->ID, 'wphpc_short_description', true );
$wphpc_stock_status     = get_post_meta( $post->ID, 'wphpc_stock_status', true );
$wphpc_weight           = get_post_meta( $post->ID, 'wphpc_weight', true );
?>
<div class="wphpc-single-section clearfix">

    <div class="wphpc-details-column wphpc-details-wrapper">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <div class="wphpc-details-image">
            <?php
            if ( has_post_thumbnail() ) {
                $attachment_ids[0]  = get_post_thumbnail_id( $post->ID );
                $attachment         = wp_get_attachment_image_src( $attachment_ids[0], 'full' );
                $wphpc_image_url    = $attachment[0];
            } else { 
                $wphpc_image_url    = '';
            }
            ?>
            <img src="<?php echo esc_url( $wphpc_image_url ); ?>" alt="<?php the_title(); ?>">
        </div>
        <div class="wphpc-details-description">

            <h2 class="wphpc-details-book-title"><?php the_title(); ?></h2>

            <table cellspacing="1" cellpadding="1" class="wphpc-single-info-table">
            <tr>
                <td colspan="2">
                    <div class="regular-price">
                        <?php
                        if ( empty( $wphpcSalePrice ) ) {
                            echo ( ! empty( $wphpcRegularPrice ) ) ? '<span class="wphpc-price single price-after">' . esc_html( $wphpcCurrencySymbol ) . '&nbsp;' . $wphpcRegularPrice . '</span>' : '';
                        } else {
                            echo '<span class="wphpc-price single price-before">' . esc_html( $wphpcCurrencySymbol ) . '&nbsp;' . $wphpcRegularPrice . '</span>&nbsp;&nbsp;&nbsp;<span class="wphpc-price single price-after">' . esc_html( $wphpcCurrencySymbol ) . '&nbsp;' . $wphpcSalePrice . '</span>';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="wphpc-short-description">
                        <?php
                        if ( ! empty( $wphpcShortDescription ) ) {
                            echo '<span>' . wp_kses_post( $wphpcShortDescription ) . '</span>';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                    if ( 'in' === $wphpc_stock_status ) {
                        echo '<div class="wphpc-single-stock in">In stock</div>';
                    } else {
                        echo '<div class="wphpc-single-stock out">Out of stock</div>';
                    }
                    ?>
                </td>
            </tr>
            <?php
            if ( '' !== $wphpc_weight ) {
                ?>
                <tr>
                    <td class="wphpc-single-tbl-left">
                        <b>Weight:</b>
                    </td>
                    <td class="wphpc-single-tbl-right">
                        <?php esc_html_e( $wphpc_weight ); ?>
                    </td>
                </tr>
                <?php
            }

            if ( 'external' === $wphpc_product_type ) {
                ?>
                <tr>
                    <td colspan="2">
                        <a href="<?php echo esc_url( $wphpc_product_url ); ?>" class="wphpc-buy-now-btn" target="_blank"><?php _e( 'Buy Now', 'hm-product-catalog' ); ?></a>      
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td class="wphpc-single-tbl-left">
                    <b>Category:</b>
                </td>
                <td class="wphpc-single-tbl-right">
                    <?php
                    $wphpcCatArray = array();
                    $wphpcCategory = wp_get_post_terms( $post->ID, 'product_category', array('fields' => 'all') );
                    foreach( $wphpcCategory as $cat) {
                        $wphpcCatArray[] = $cat->name . '';
                    }
                    echo implode( ', ', $wphpcCatArray );
                    ?>
                </td>
            </tr>
            <?php
            $wphpc_sku = get_post_meta( $post->ID, 'wphpc_sku', true );
            if ( '' !== $wphpc_sku ) {
            ?>
            <tr>
                <td class="wphpc-single-tbl-left">
                    <b>SKU:</b>
                </td>
                <td class="wphpc-single-tbl-right">
                    <?php esc_html_e( $wphpc_sku ); ?>
                </td>
            </tr>
            <?php
            }
            ?>
            </table>
        </div>
        <div class="wphpc-details-description-full">
            <div class="wphpc-details-description-title">
                <span><?php _e( 'Description', 'hm-product-catalog' ); ?></span>
            </div>
            <div class="wphpc-details-description-content">
                <?php the_content(); ?>
            </div>
        </div>
        <?php endwhile;
        endif; 
        
        if ( ! $wphpc_details_is_external ) {
            ?>
            <a href="#" class="button wbg-btn-back" onclick="javascript:history.back()"><< <?php _e('Back', 'hm-product-catalog'); ?></a>
            <?php
        }
        ?>

    </div>

    <div class="wphpc-details-column wphpc-sidebar-right">
        <?php
            if ( function_exists( 'register_sidebar' ) ) {
                dynamic_sidebar();
            } else {
                _e('No sidebar registered', 'hm-product-catalog');
            }
        ?>
    </div>

</div>
<?php get_footer(); ?>
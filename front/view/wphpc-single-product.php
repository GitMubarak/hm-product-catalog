<?php
/**
 * Template Name: Product Catalog Details
 *
 */
get_header();

require_once WPHPC_PATH . 'front/' . WPHPC_CLS_PRFX . 'front.php';
$wphpcFrontObj = new WPHPC_Front( WPHPC_VERSION );

$wphpcGeneralSettings 	= stripslashes_deep( unserialize( get_option('wphpc_general_settings') ) );
$wphpcCurrency 			= isset( $wphpcGeneralSettings['wphpc_currency'] ) ? $wphpcGeneralSettings['wphpc_currency'] : '';
$wphpcCatLbl 			= ($wphpcGeneralSettings['wphpc_cat_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_cat_label_txt'] : '';
$wphpcPriceLbl 			= ($wphpcGeneralSettings['wphpc_price_label_txt'] != '') ? $wphpcGeneralSettings['wphpc_price_label_txt'] : '';
$wphpcCurrencySymbol    = $wphpcFrontObj->get_currency_symbol( $wphpcCurrency );
?>
<div class="wphpc-details-wrapper">

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
        <img src="<?php echo esc_url( $wphpc_image_url ); ?>" alt="<?php esc_attr_e( 'ALT', WPHPC_TXT_DOMAIN ); ?>">
    </div>
    <div class="wphpc-details-description">

        <h2 class="wphpc-details-book-title"><?php the_title(); ?></h2>

        <table cellspacing="1" cellpadding="1" class="wphpc-single-info-table">
        <tr>
            <td colspan="2">
                <div class="regular-price">
                    <?php
                    $wphpcRegularPrice  = get_post_meta($post->ID, 'wphpc_regular_price', true);
                    $wphpcSalePrice     = get_post_meta($post->ID, 'wphpc_sale_price', true);
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
                    $wphpcShortDescription = get_post_meta( $post->ID, 'wphpc_short_description', true );
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
                $wphpc_stock_status = get_post_meta( $post->ID, 'wphpc_stock_status', true );
                if ( 'in' === $wphpc_stock_status ) {
                    echo '<div class="wphpc-single-stock in">In stock</div>';
                } else {
                    echo '<div class="wphpc-single-stock out">Out of stock</div>';
                }
                ?>
            </td>
        </tr>
        <?php
        $wphpc_weight = get_post_meta( $post->ID, 'wphpc_weight', true );
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
        ?>
        <tr>
            <td colspan="2">
                <?php
                $wphpc_product_type = get_post_meta( $post->ID, 'wphpc_product_type', true );
                $wphpc_product_url  = get_post_meta( $post->ID, 'wphpc_product_url', true );
                if ( 'external' === $wphpc_product_type ) {
                    ?>
                    <a href="<?php echo esc_url( $wphpc_product_url ); ?>" class="wphpc-buy-now-btn" target="_blank"><?php _e( 'Buy Now', WPHPC_TXT_DOMAIN ); ?></a>
                    <?php
                }
                ?>
            </td>
        </tr>
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
            <span><?php _e( 'Description', WPHPC_TXT_DOMAIN ); ?></span>
        </div>
        <div class="wphpc-details-description-content">
            <?php the_content(); ?>
        </div>
    </div>
    <?php endwhile;
    endif; ?>

</div>

<?php get_footer(); ?>
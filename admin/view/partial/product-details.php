<?php if ( ! defined('ABSPATH') ) exit; ?>
<table class="form-table">
    <tr>
        <th scope="row">
            <label><?php _e('Product Type', WPHPC_TXT_DOMAIN); ?>:</label>
        </th>
        <td>
            <input type="radio" name="wphpc_product_type" class="wphpc_product_type" id="wphpc_product_type_simple" value="simple" <?php echo ( 'external' !== $wphpc_product_type ) ? 'checked' : ''; ?> >
            <label for="wphpc_product_type_simple"><span></span><?php _e( 'Simple', WPHPC_TXT_DOMAIN ); ?></label>
                &nbsp;&nbsp;
            <input type="radio" name="wphpc_product_type" class="wphpc_product_type" id="wphpc_product_type_external" value="external" <?php echo ( 'external' === $wphpc_product_type ) ? 'checked' : ''; ?> >
            <label for="wphpc_product_type_external"><span></span><?php _e( 'External/Affiliate', WPHPC_TXT_DOMAIN ); ?></label>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Product URL', WPHPC_TXT_DOMAIN); ?>:</label>
        </th>
        <td>
            <input type="text" name="wphpc_product_url" value="<?php esc_attr_e( $wphpc_product_url ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'https://', WPHPC_TXT_DOMAIN ); ?>">
            <span><?php _e( 'Enter the external URL to the product', WPHPC_TXT_DOMAIN ); ?>.</span>
        </td>
    </tr>
    <tr class="wphpc_sku">
        <th scope="row">
            <label for="wphpc_sku"><?php esc_html_e('SKU/Product Id:', WPHPC_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <input type="text" name="wphpc_sku" value="<?php echo esc_attr($wphpc_sku); ?>" class="regular-text">
        </td>
    </tr>
    <tr class="wphpc_weight">
        <th scope="row">
            <label for="wphpc_weight"><?php esc_html_e('Weight:', WPHPC_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <input type="text" name="wphpc_weight" value="<?php echo esc_attr( $wphpc_weight ); ?>" class="regular-text">
        </td>
    </tr>
    <tr class="wphpc_regular_price">
        <th scope="row">
            <label for="wphpc_regular_price"><?php esc_html_e('Regular Price:', WPHPC_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <input type="text" name="wphpc_regular_price" value="<?php echo esc_attr($wphpc_regular_price); ?>"
                class="regular-text">
        </td>
    </tr>
    <tr class="wphpc_sale_price">
        <th scope="row">
            <label for="wphpc_sale_price"><?php esc_html_e('Sale Price:', WPHPC_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <input type="text" name="wphpc_sale_price" value="<?php echo esc_attr($wphpc_sale_price); ?>"
                class="regular-text">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Stock Status', WPHPC_TXT_DOMAIN); ?>:</label>
        </th>
        <td>
            <input type="radio" name="wphpc_stock_status" class="wphpc_stock_status" id="wphpc_stock_status_in" value="in" <?php echo ( 'out' !== $wphpc_stock_status ) ? 'checked' : ''; ?> >
            <label for="wphpc_stock_status_in"><span></span><?php _e( 'In stock', WPHPC_TXT_DOMAIN ); ?></label>
                &nbsp;&nbsp;
            <input type="radio" name="wphpc_stock_status" class="wphpc_stock_status" id="wphpc_stock_status_out" value="out" <?php echo ( 'out' === $wphpc_stock_status ) ? 'checked' : ''; ?> >
            <label for="wphpc_stock_status_out"><span></span><?php _e( 'Out of stock', WPHPC_TXT_DOMAIN ); ?></label>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Status', WPHPC_TXT_DOMAIN); ?>:</label>
        </th>
        <td>
            <input type="radio" name="wphpc_status" class="wphpc_status" id="wphpc_status_active" value="active" <?php echo ( 'inactive' !== $wphpc_status ) ? 'checked' : ''; ?> >
            <label for="wphpc_status_active"><span></span><?php _e( 'Active', WPHPC_TXT_DOMAIN ); ?></label>
                &nbsp;&nbsp;
            <input type="radio" name="wphpc_status" class="wphpc_status" id="wphpc_status_inactive" value="inactive" <?php echo ( 'inactive' === $wphpc_status ) ? 'checked' : ''; ?> >
            <label for="wphpc_status_inactive"><span></span><?php _e( 'Inactive', WPHPC_TXT_DOMAIN ); ?></label>
        </td>
    </tr>
</table>
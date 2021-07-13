<?php
if ( ! defined('ABSPATH') ) exit;

$wphpcShowGeneralMessage = false;

if (isset($_POST['updateGeneralSettings'])) {
    $wphpcGeneralSettingsInfo = array(
        'wphpc_currency' => (!empty($_POST['wphpc_currency']) && (sanitize_text_field($_POST['wphpc_currency']) != '')) ? sanitize_text_field($_POST['wphpc_currency']) : '',
        'wphpc_details_is_external' => (isset($_POST['wphpc_details_is_external']) && filter_var($_POST['wphpc_details_is_external'], FILTER_SANITIZE_NUMBER_INT)) ? $_POST['wphpc_details_is_external'] : '',
        'wphpc_cat_label_txt' => (!empty($_POST['wphpc_cat_label_txt']) && (sanitize_text_field($_POST['wphpc_cat_label_txt']) != '')) ? sanitize_text_field($_POST['wphpc_cat_label_txt']) : '',
        'wphpc_price_label_txt' => (!empty($_POST['wphpc_price_label_txt']) && (sanitize_text_field($_POST['wphpc_price_label_txt']) != '')) ? sanitize_text_field($_POST['wphpc_price_label_txt']) : '',
        'wphpc_product_column' => (filter_var($_POST['wphpc_product_column'], FILTER_SANITIZE_STRING)) ? $_POST['wphpc_product_column'] : 4,
    );
    $wphpcShowGeneralMessage = update_option('wphpc_general_settings', serialize($wphpcGeneralSettingsInfo));
}

$wphpcGeneralSettings   = stripslashes_deep( unserialize( get_option('wphpc_general_settings') ) );
$wphpc_currency         = isset( $wphpcGeneralSettings['wphpc_currency'] ) ? $wphpcGeneralSettings['wphpc_currency'] : '';
?>
<div id="wph-wrap-all" class="wrap wphpc-settings-page">

    <div class="settings-banner">
        <h2><?php esc_html_e('General Settings', WPHPC_TXT_DOMAIN); ?></h2>
    </div>

    <?php 
        if ( $wphpcShowGeneralMessage ) {
            $this->wphpc_display_notification('success', 'Your information updated successfully.');
        }
    ?>

    <div class="hmpc-wrap">
        
        <div class="hmpc_personal_wrap hmpc_personal_help" style="width: 845px; float: left; margin-top: 5px;">

            <form name="wphpc_general_settings_form" role="form" class="form-horizontal" method="post" action=""
                id="wphpc-general-settings-form">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label><?php _e('Currency:', WPHPC_TXT_DOMAIN); ?></label>
                            <?php echo ( $wphpc_currency == '&#36;' ) ? 'selected' : ''; ?>
                        </th>
                        <td>
                            <select name="wphpc_currency" id="wphpc_currency" class="regular-text">
                                <?php
                                $wphpcCurrency = $this->hm_get_all_currency();
                                foreach ( $wphpcCurrency as $wpsdcurr ) {
                                    ?>
                                    <option value="<?php esc_attr_e( $wpsdcurr->currency ); ?>" 
                                        <?php echo ( $wphpc_currency === $wpsdcurr->currency ) ? 'selected="selected"' : ''; ?> >
                                        <?php esc_html_e( $wpsdcurr->currency ); ?>-<?php esc_html_e( $wpsdcurr->abbreviation ); ?>-<?php esc_html_e( $wpsdcurr->symbol ); ?>
                                    </option>
                                    <?php 
                                } 
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="wphpc_details_is_external">
                        <th scope="row">
                            <label for="wphpc_details_is_external"><?php _e('Single Page in New Tab?', WPHPC_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="wphpc_details_is_external" id="wphpc_details_is_external" value="1"
                                <?php echo $wphpcGeneralSettings['wphpc_details_is_external'] ? 'checked' : null; ?> >
                        </td>
                    </tr>
                    <tr class="wphpc_cat_label_txt">
                        <th scope="row">
                            <label
                                for="wphpc_cat_label_txt"><?php esc_html_e('Category Label Text:', WPHPC_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wphpc_cat_label_txt" placeholder="Category:" class=""
                                value="<?php echo esc_attr($wphpcGeneralSettings['wphpc_cat_label_txt']); ?>">
                        </td>
                    </tr>
                    <tr class="wphpc_price_label_txt">
                        <th scope="row">
                            <label
                                for="wphpc_price_label_txt"><?php esc_html_e('Price Label Text:', WPHPC_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wphpc_price_label_txt" placeholder="Price:" class=""
                                value="<?php echo esc_attr($wphpcGeneralSettings['wphpc_price_label_txt']); ?>">
                        </td>
                    </tr>
                    <tr class="wphpc_product_column">
                        <th scope="row">
                            <label
                                for="wphpc_product_column"><?php esc_html_e('Products Columns:', WPHPC_TXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <select name="wphpc_product_column" class="">
                                <option value=""><?php esc_html_e('--Select One--', WPHPC_TXT_DOMAIN); ?></option>
                                <option value="2"
                                    <?php if ('2' == esc_attr($wphpcGeneralSettings['wphpc_product_column'])) echo 'selected'; ?>>
                                    <?php esc_html_e('2', WPHPC_TXT_DOMAIN); ?>
                                </option>
                                <option value="3"
                                    <?php if ('3' == esc_attr($wphpcGeneralSettings['wphpc_product_column'])) echo 'selected'; ?>>
                                    <?php esc_html_e('3', WPHPC_TXT_DOMAIN); ?>
                                </option>
                                <option value="4"
                                    <?php if ('4' == esc_attr($wphpcGeneralSettings['wphpc_product_column'])) echo 'selected'; ?>>
                                    <?php esc_html_e('4', WPHPC_TXT_DOMAIN); ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit"><button id="updateGeneralSettings" name="updateGeneralSettings"
                        class="button button-primary"><?php esc_attr_e('Save Settings', WPHPC_TXT_DOMAIN); ?></button></p>
            </form>

        </div>

        <?php include_once('partial/admin-sidebar.php'); ?> 

    </div>
</div>
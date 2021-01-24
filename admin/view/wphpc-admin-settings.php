<?php
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
$wphpcGeneralSettings = stripslashes_deep(unserialize(get_option('wphpc_general_settings')));
//echo "<pre>";
//print_r($wphpcGeneralSettings);
?>
<div id="wph-wrap-all" class="wrap wphpc-settings-page">
    <div class="settings-banner">
        <h2><?php esc_html_e('HM Product Catalog Settings', WPHPC_TXT_DOMAIN); ?></h2>
    </div>
    <?php if ($wphpcShowGeneralMessage) : $this->wphpc_display_notification('success', 'Your information updated successfully.');
    endif; ?>

    <form name="wphpc_general_settings_form" role="form" class="form-horizontal" method="post" action=""
        id="wphpc-general-settings-form">
        <table class="form-table">
            <tr class="wphpc_currency">
                <th scope="row">
                    <label for="wphpc_currency"><?php esc_html_e('Currency:', WPHPC_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wphpc_currency" placeholder="USD" class="regular-text"
                        value="<?php echo esc_attr($wphpcGeneralSettings['wphpc_currency']); ?>">
                </td>
            </tr>
            <tr class="wphpc_details_is_external">
                <th scope="row">
                    <label
                        for="wphpc_details_is_external"><?php esc_html_e('Open Details Page in New Window?', WPHPC_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wphpc_details_is_external" class="wphpc_details_is_external" value="1"
                        <?php if ($wphpcGeneralSettings['wphpc_details_is_external'] == "1") {
                                                                                                                            echo 'checked';
                                                                                                                        } ?>>
                </td>
            </tr>
            <tr class="wphpc_cat_label_txt">
                <th scope="row">
                    <label
                        for="wphpc_cat_label_txt"><?php esc_html_e('Category Label Text:', WPHPC_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wphpc_cat_label_txt" placeholder="Category:" class="regular-text"
                        value="<?php echo esc_attr($wphpcGeneralSettings['wphpc_cat_label_txt']); ?>">
                </td>
            </tr>
            <tr class="wphpc_price_label_txt">
                <th scope="row">
                    <label
                        for="wphpc_price_label_txt"><?php esc_html_e('Price Label Text:', WPHPC_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <input type="text" name="wphpc_price_label_txt" placeholder="Price:" class="regular-text"
                        value="<?php echo esc_attr($wphpcGeneralSettings['wphpc_price_label_txt']); ?>">
                </td>
            </tr>
            <tr class="wphpc_product_column">
                <th scope="row">
                    <label
                        for="wphpc_product_column"><?php esc_html_e('Products Columns:', WPHPC_TXT_DOMAIN); ?></label>
                </th>
                <td>
                    <select name="wphpc_product_column" class="regular-text">
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
                class="button button-primary"><?php esc_attr_e('Update Settings', WPHPC_TXT_DOMAIN); ?></button></p>
    </form>
</div>
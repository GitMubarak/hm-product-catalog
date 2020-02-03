<?php
$wphpcShowGeneralMessage = false;

if(isset($_POST['updateGeneralSettings'])){
     $wphpcGeneralSettingsInfo = array (
          'wphpc_details_is_external' => (isset($_POST['wphpc_details_is_external']) && filter_var($_POST['wphpc_details_is_external'], FILTER_SANITIZE_NUMBER_INT)) ? $_POST['wphpc_details_is_external'] : '',
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
    <?php if($wphpcShowGeneralMessage): $this->wphpc_display_notification('success', 'Your information updated successfully.'); endif; ?>

    <form name="wphpc_general_settings_form" role="form" class="form-horizontal" method="post" action="" id="wphpc-general-settings-form">
        <table class="form-table">
        <tr class="wphpc_details_is_external">
            <th scope="row">
                <label for="wphpc_details_is_external"><?php esc_html_e('Details Page New Window:', WPHPC_TXT_DOMAIN); ?></label>
            </th>
            <td>
            <input type="checkbox" name="wphpc_details_is_external" class="wphpc_details_is_external" value="1" <?php if($wphpcGeneralSettings['wphpc_details_is_external'] == "1") { echo 'checked'; } ?>>
            </td>
        </tr>
        </table>
        <p class="submit"><button id="updateGeneralSettings" name="updateGeneralSettings" class="button button-primary"><?php esc_attr_e('Update Settings', WPHPC_TXT_DOMAIN); ?></button></p>
    </form>
</div>
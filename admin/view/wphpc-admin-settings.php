<?php
$wphpcShowMessage = false;

if(isset($_POST['updateSettings'])){
     if (!isset($_POST['wphpc_update_setting'])) die("Something wrong!");
     if (!wp_verify_nonce($_POST['wphpc_update_setting'],'wphpc-update-setting')) die("Something wrong!");
     for($i=0; $i<count($_POST['wphpc_name']); $i++){
          $wphpcMkArr[sanitize_text_field(strtolower($_POST['wphpc_name'][$i]))] = array(
                                                                 'wphpc_description'   => (!empty($_POST['wphpc_description'][$i]) && (sanitize_textarea_field($_POST['wphpc_description'][$i])!='')) ? sanitize_textarea_field($_POST['wphpc_description'][$i]) : '',
                                                                 'wphpc_bg_color'      => (!empty($_POST['wphpc_bg_color'][$i]) && (sanitize_text_field($_POST['wphpc_bg_color'][$i])!='')) ? sanitize_text_field($_POST['wphpc_bg_color'][$i]) : ''
                                                                 );
     }
     $wphpcShowMessage = update_option('wphpc_settings', $wphpcMkArr);
}
$wphpc_settings = get_option('wphpc_settings');
?>
<div id="wph-wrap-all" class="wrap">
     <div class="settings-banner">
          <h2><?php esc_html_e('WP Alert Bar Settings', WPHPC_TXT_DOMAIN); ?></h2>
     </div>
     <?php if($wphpcShowMessage): $this->wphpc_display_notification('success', 'Your information updated successfully.'); endif; ?>

     <form name="wpre-table" role="form" class="form-horizontal" method="post" action="" id="wphpc-settings-form">
          <input type="hidden" name="wphpc_update_setting" value="<?php printf('%s', wp_create_nonce('wphpc-update-setting')); ?>" />
          <table class="wphpc-form-table">
          <tr>
               <td colspan="2">
                    <table class="wphpc-alert-bar-table" width="100%" cellpadding="0" cellspacing="0">
                         <thead>
                              <tr>
                                   <th>#</th>
                                   <th>Title</th>
                                   <th>Description</th>
                                   <th>BG Color</th>
                                   <th><input type="button" class="button button-primary add" value="Add New"></th>
                              <tr>
                         </thead>
                         <tbody class="wphpc-add-row-tbody">
                              <?php
                              $i=0;
                              if($wphpc_settings) {
                                   for($i=0; $i<count($wphpc_settings); $i++){
                                        $wphpcArrayKey = array_keys($wphpc_settings)[$i];
                                        ?>
                                        <tr class="wphpc-add-row-tr">
                                             <td style="vertical-align: top;"><?php printf('%d', $i); ?></td>
                                             <td class="wphpc_name" style="vertical-align: top;">
                                                  <input type="text" name="wphpc_name[]" class="wphpc_name" placeholder="Alert Bar Name" value="<?php esc_attr_e($wphpcArrayKey); ?>">
                                             </td>
                                             <td class="wphpc_description" style="vertical-align: top;">
                                                  <textarea name="wphpc_description[]" class="wphpc_description" cols="50" rows="1"><?php esc_html_e($wphpc_settings[$wphpcArrayKey]['wphpc_description']); ?></textarea>
                                             </td>
                                             <td class="wphpc_bg_color" style="vertical-align: top;">
                                                  <input class="wphpc-wp-color" type="text" name="wphpc_bg_color[]" id="wphpc_bg_color_<?php printf('%d', $i); ?>" value="<?php esc_attr_e($wphpc_settings[$wphpcArrayKey]['wphpc_bg_color']); ?>">
                                                  <div id="colorpicker"></div>
                                             </td>
                                             <td style="vertical-align: top;"><a href="#" class="dashicons dashicons-no delete">&nbsp;</a></td>
                                        <tr>
                                        <?php
                                   }
                              } else{ ?>
                                        <tr class="wphpc-add-row-tr">
                                             <td style="vertical-align: top;"><?php printf('%d', $i+1); ?></td>
                                             <td class="wphpc_name" style="vertical-align: top;">
                                                  <input type="text" name="wphpc_name[]" class="wphpc_name" placeholder="Alert Bar Name">
                                             </td style="vertical-align: top;">
                                             <td class="wphpc_description" style="vertical-align: top;">
                                                  <textarea name="wphpc_description[]" class="wphpc_description" cols="50" rows="1"></textarea>
                                             </td>
                                             <td class="wphpc_bg_color" style="vertical-align: top;">
                                                  <input class="wphpc-wp-color" type="text" name="wphpc_bg_color[]" id="wphpc_bg_color_<?php printf('%d', $i+1); ?>">
                                                  <div id="colorpicker"></div>
                                             </td>
                                             <td style="vertical-align: top;"></td>
                                        <tr>
                              <?php } ?>
                         </tbody>
                    </table>
               </td>
          </tr>
          <tr class="wphpc_shortcode">
               <th scope="row">
                    <label for="wphpc_shortcode"><?php esc_html_e('Shortcode: ', WPHPC_TXT_DOMAIN); ?></label>
               </th>
               <td>
                    <input type="text" name="wphpc_shortcode" id="wphpc_shortcode" class="regular-text" value="[wp_alert_bars]" readonly />
               </td>
          </tr>
          </table>
          <p class="submit"><button id="updateSettings" name="updateSettings" class="button button-primary"><?php esc_attr_e('Update Settings', WPHPC_TXT_DOMAIN); ?></button></p>
     </form>
</div>
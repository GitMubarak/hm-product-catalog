<?php

/**
 *	Admin Parent Class
 */
class WPHPC_Admin
{
	private $wphpc_version;
	private $wphpc_assets_prefix;

	function __construct($version)
	{
		$this->wphpc_version = $version;
		$this->wphpc_assets_prefix = substr(WPHPC_PRFX, 0, -1) . '-';
	}


	/**
	 *	Loading admin menu
	 */
	function wphpc_admin_menu()
	{
		$wphpc_cpt_menu = 'edit.php?post_type=products';
		add_submenu_page(
			$wphpc_cpt_menu,
			esc_html__('Settings', WPHPC_TXT_DOMAIN),
			esc_html__('Settings', WPHPC_TXT_DOMAIN),
			'manage_options',
			'wphpc-settings-section',
			array($this, WPHPC_PRFX . 'settings')
		);
	}

	/**
	 *	Loading admin panel assets
	 */
	function wphpc_enqueue_assets()
	{

		wp_enqueue_style(
			$this->wphpc_assets_prefix . 'admin-style',
			WPHPC_ASSETS . 'css/' . $this->wphpc_assets_prefix . 'admin-style.css',
			array(),
			$this->wphpc_version,
			FALSE
		);

		if (!wp_script_is('jquery')) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script(
			$this->wphpc_assets_prefix . 'admin-script',
			WPHPC_ASSETS . 'js/' . $this->wphpc_assets_prefix . 'admin-script.js',
			array('jquery'),
			$this->wphpc_version,
			TRUE
		);
	}

	function wphpc_custom_post_type()
	{
		$labels = array(
			'name'                => __('Products'),
			'singular_name'       => __('Product'),
			'menu_name'           => __('HM Product Catalog'),
			'parent_item_colon'   => __('Parent Product'),
			'all_items'           => __('All Products'),
			'view_item'           => __('View Product'),
			'add_new_item'        => __('Add New Product'),
			'add_new'             => __('Add New'),
			'edit_item'           => __('Edit Product'),
			'update_item'         => __('Update Product'),
			'search_items'        => __('Search Product'),
			'not_found'           => __('Not Found'),
			'not_found_in_trash'  => __('Not found in Trash')
		);
		$args = array(
			'label'               => __('products'),
			'description'         => __('Description For Products'),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'thumbnail'),
			'public'              => true,
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'has_archive'         => false,
			'can_export'          => true,
			'exclude_from_search' => false,
			'yarpp_support'       => true,
			'taxonomies' 	      => array('post_tag'),
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-screenoptions'
		);
		register_post_type('products', $args);
	}

	function wphpc_taxonomy_for_products()
	{

		$labelsCatalog = array(
			'name' => _x('Product Catalogs', 'taxonomy general name'),
			'singular_name' => _x('Product Catalog', 'taxonomy singular name'),
			'search_items' =>  __('Search Product Catalog'),
			'all_items' => __('All Catalogs'),
			'parent_item' => __('Parent Product Catalog'),
			'parent_item_colon' => __('Parent Product Catalog:'),
			'edit_item' => __('Edit Product Catalog'),
			'update_item' => __('Update Product Catalog'),
			'add_new_item' => __('Add New Catalog'),
			'new_item_name' => __('New Product Catalog Name'),
			'menu_name' => __('Product Catalogs'),
		);

		register_taxonomy('product_catalog', array('products'), array(
			'hierarchical' => true,
			'labels' => $labelsCatalog,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'product-catalog'),
		));

		$labels = array(
			'name' => _x('Product Categories', 'taxonomy general name'),
			'singular_name' => _x('Product Category', 'taxonomy singular name'),
			'search_items' =>  __('Search Product Categories'),
			'all_items' => __('All Categories'),
			'parent_item' => __('Parent Product Category'),
			'parent_item_colon' => __('Parent Product Category:'),
			'edit_item' => __('Edit Product Category'),
			'update_item' => __('Update Product Category'),
			'add_new_item' => __('Add New Category'),
			'new_item_name' => __('New Product Category Name'),
			'menu_name' => __('Product Categories'),
		);

		register_taxonomy('product_category', array('products'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'product-category'),
		));
	}

	function wphpc_product_details_metaboxes()
	{
		add_meta_box(
			'wphpc_product_details_link',
			'Product Details',
			array($this, WPHPC_PRFX . 'product_details_content'),
			'products',
			'normal',
			'high'
		);
	}

	function wphpc_product_details_content()
	{
		global $post;
		// Nonce field to validate form request came from current site
		wp_nonce_field(basename(__FILE__), 'event_fields');
		$wphpc_short_description	= get_post_meta($post->ID, 'wphpc_short_description', true);
		$wphpc_sku 				= get_post_meta($post->ID, 'wphpc_sku', true);
		$wphpc_regular_price 	= get_post_meta($post->ID, 'wphpc_regular_price', true);
		$wphpc_sale_price 		= get_post_meta($post->ID, 'wphpc_sale_price', true);
		$wphpc_weight 			= get_post_meta($post->ID, 'wphpc_weight', true);
		$wphpc_status 			= get_post_meta($post->ID, 'wphpc_status', true);
?>
<table class="form-table">
    <tr class="wphpc_short_description">
        <th scope="row">
            <label for="wphpc_short_description"><?php esc_html_e('Short Description:', WPHPC_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <?php
					$settings = array('media_buttons' => false, 'editor_height' => 200,);
					$content = wp_kses_post($wphpc_short_description);
					$editor_id = 'wphpc_short_description';
					wp_editor($content, $editor_id, $settings);
					?>
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
            <input type="text" name="wphpc_weight" value="<?php echo esc_attr($wphpc_weight); ?>" class="regular-text">
        </td>
    </tr>
    <tr class="wphpc_status">
        <th scope="row">
            <label for="wphpc_status"><?php esc_html_e('Status:', WPHPC_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <select name="wphpc_status" class="regular-text">
                <option value="active" <?php if ('inactive' != esc_attr($wphpc_status)) echo 'selected'; ?>>Active
                </option>
                <option value="inactive" <?php if ('inactive' == esc_attr($wphpc_status)) echo 'selected'; ?>>Inactive
                </option>
            </select>
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
</table>
<?php
	}

	/**
	 * Save the metabox data
	 */
	function wphpc_save_product_meta($post_id)
	{
		global $post;

		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		if (!isset($_POST['wphpc_regular_price']) || !wp_verify_nonce($_POST['event_fields'], basename(__FILE__))) {
			return $post_id;
		}

		$events_meta['wphpc_short_description']	= (!empty($_POST['wphpc_short_description']) && (sanitize_textarea_field($_POST['wphpc_short_description']) != '')) ? sanitize_textarea_field($_POST['wphpc_short_description']) : '';
		$events_meta['wphpc_sku'] 				= (!empty($_POST['wphpc_sku']) && (sanitize_text_field($_POST['wphpc_sku']) != '')) ? sanitize_text_field($_POST['wphpc_sku']) : '';
		$events_meta['wphpc_regular_price'] 	= (!empty($_POST['wphpc_regular_price']) && (sanitize_text_field($_POST['wphpc_regular_price']) != '')) ? sanitize_text_field($_POST['wphpc_regular_price']) : '';
		$events_meta['wphpc_sale_price'] 		= (!empty($_POST['wphpc_sale_price']) && (sanitize_text_field($_POST['wphpc_sale_price']) != '')) ? sanitize_text_field($_POST['wphpc_sale_price']) : '';
		$events_meta['wphpc_weight'] 			= (!empty($_POST['wphpc_weight']) && (sanitize_text_field($_POST['wphpc_weight']) != '')) ? sanitize_text_field($_POST['wphpc_weight']) : '';
		$events_meta['wphpc_status'] 			= (!empty($_POST['wphpc_status']) && (sanitize_text_field($_POST['wphpc_status']) != '')) ? sanitize_text_field($_POST['wphpc_status']) : '';


		foreach ($events_meta as $key => $value) :
			if ('revision' === $post->post_type) {
				return;
			}
			if (get_post_meta($post_id, $key, false)) {
				update_post_meta($post_id, $key, $value);
			} else {
				add_post_meta($post_id, $key, $value);
			}
			if (!$value) {
				delete_post_meta($post_id, $key);
			}
		endforeach;
	}

	function wphpc_settings()
	{
		require_once WPHPC_PATH . 'admin/view/' . $this->wphpc_assets_prefix . 'admin-settings.php';
	}

	function wphpc_display_notification($type, $msg)
	{ ?>
<div class="wphpc-alert <?php printf('%s', $type); ?>">
    <span class="wphpc-closebtn">&times;</span>
    <strong><?php esc_html_e(ucfirst($type), WPHPC_TXT_DOMAIN); ?>!</strong>
    <?php esc_html_e($msg, WPHPC_TXT_DOMAIN); ?>
</div>
<?php }
}
?>
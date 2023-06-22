<?php
if ( ! defined('ABSPATH') ) exit;

/**
 *	Admin Parent Class
 */
class WPHPC_Admin {

	use WPHPC_Currency;

	private $wphpc_version;
	private $wphpc_assets_prefix;

	function __construct( $version ) {
		$this->wphpc_version = $version;
		$this->wphpc_assets_prefix = substr(WPHPC_PRFX, 0, -1) . '-';
	}


	/**
	 *	Loading admin menu
	 */
	function wphpc_admin_menu() {

		add_menu_page(
			__( 'HM Product Catalog', 'hm-product-catalog' ),
			__( 'HM Product Catalog', 'hm-product-catalog' ),
			'',
			'wphpc-admin-settings',
			'',
			WPHPC_ASSETS . 'img/hmplugin-icon.png',
			25
		);

		add_submenu_page(
			'wphpc-admin-settings',
			__( 'General Settings', 'hm-product-catalog' ),
			__( 'General Settings', 'hm-product-catalog' ),
			'manage_options',
			'wphpc-settings-section',
			array( $this, WPHPC_PRFX . 'settings' )
		);

		add_submenu_page(
			'wphpc-admin-settings',
			__( 'Help & Usage', 'hm-product-catalog' ),
			__( 'Help & Usage', 'hm-product-catalog' ),
			'manage_options',
			'wphpc-get-help',
			array( $this, WPHPC_PRFX . 'get_help' )
		);
	}

	/**
	 *	Loading admin panel assets
	 */
	function wphpc_enqueue_assets() {

		wp_enqueue_style(
			$this->wphpc_assets_prefix . 'admin-style',
			WPHPC_ASSETS . 'css/' . $this->wphpc_assets_prefix . 'admin.css',
			array(),
			$this->wphpc_version,
			FALSE
		);

		if ( ! wp_script_is('jquery') ) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script(
			$this->wphpc_assets_prefix . 'admin-script',
			WPHPC_ASSETS . 'js/' . $this->wphpc_assets_prefix . 'admin.js',
			array('jquery'),
			$this->wphpc_version,
			TRUE
		);
	}

	function wphpc_custom_post_type() {

		$labels = array(
			'name'                => __('Products'),
			'singular_name'       => __('Product'),
			'menu_name'           => __('Products'),
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
			'menu_icon'           => 'dashicons-products'
		);

		register_post_type('products', $args);
	}

	function wphpc_taxonomy_for_products() {

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

	function wphpc_product_details_metaboxes() {
		add_meta_box(
			'wphpc_product_details_link',
			__( 'Product Details', 'hm-product-catalog' ),
			array($this, WPHPC_PRFX . 'product_details_content'),
			'products',
			'normal',
			'high'
		);

		add_meta_box(
			'wphpc-product-short-description',
			__( 'Product Short Description', 'hm-product-catalog' ),
			array( $this, 'wphpc_product_short_description'),
			'products',
			'normal',
			'high'
		);
	}

	function wphpc_product_details_content() {

		global $post;

		wp_nonce_field( basename(__FILE__), 'event_fields' );
		
		$wphpc_sku 					= get_post_meta( $post->ID, 'wphpc_sku', true );
		$wphpc_regular_price 		= get_post_meta( $post->ID, 'wphpc_regular_price', true );
		$wphpc_sale_price 			= get_post_meta( $post->ID, 'wphpc_sale_price', true );
		$wphpc_weight 				= get_post_meta( $post->ID, 'wphpc_weight', true );
		$wphpc_status 				= get_post_meta( $post->ID, 'wphpc_status', true );
		$wphpc_stock_status 		= get_post_meta( $post->ID, 'wphpc_stock_status', true );
		$wphpc_product_type 		= get_post_meta( $post->ID, 'wphpc_product_type', true );
		$wphpc_product_url 			= get_post_meta( $post->ID, 'wphpc_product_url', true );

		require_once 'view/partial/product-details.php';
	}

	function wphpc_product_short_description() {
		
		global $post;
		
		$wphpc_short_description	= get_post_meta( $post->ID, 'wphpc_short_description', true );
		$settings 					= array('media_buttons' => true, 'editor_height' => 200,);
		$content 					= wp_kses_post($wphpc_short_description);
		$editor_id 					= 'wphpc_short_description';
		wp_editor( $content, $editor_id, $settings );
	}

	/**
	 * Save the metabox data
	 */
	function wphpc_save_product_meta( $post_id ) {

		global $post;

		if ( ! current_user_can('edit_post', $post_id) ) {
			return $post_id;
		}

		if ( ! isset( $_POST['wphpc_regular_price']) || ! wp_verify_nonce( $_POST['event_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		$events_meta['wphpc_short_description']	= (!empty($_POST['wphpc_short_description']) && (sanitize_textarea_field($_POST['wphpc_short_description']) != '')) ? sanitize_textarea_field($_POST['wphpc_short_description']) : '';
		$events_meta['wphpc_sku'] 				= (!empty($_POST['wphpc_sku']) && (sanitize_text_field($_POST['wphpc_sku']) != '')) ? sanitize_text_field($_POST['wphpc_sku']) : '';
		$events_meta['wphpc_regular_price'] 	= (!empty($_POST['wphpc_regular_price']) && (sanitize_text_field($_POST['wphpc_regular_price']) != '')) ? sanitize_text_field($_POST['wphpc_regular_price']) : '';
		$events_meta['wphpc_sale_price'] 		= (!empty($_POST['wphpc_sale_price']) && (sanitize_text_field($_POST['wphpc_sale_price']) != '')) ? sanitize_text_field($_POST['wphpc_sale_price']) : '';
		$events_meta['wphpc_weight'] 			= (!empty($_POST['wphpc_weight']) && (sanitize_text_field($_POST['wphpc_weight']) != '')) ? sanitize_text_field($_POST['wphpc_weight']) : '';
		$events_meta['wphpc_status'] 			= (!empty($_POST['wphpc_status']) && (sanitize_text_field($_POST['wphpc_status']) != '')) ? sanitize_text_field($_POST['wphpc_status']) : '';
		$events_meta['wphpc_stock_status'] 		= isset( $_POST['wphpc_stock_status'] ) && filter_var( $_POST['wphpc_stock_status'], FILTER_SANITIZE_STRING ) ? $_POST['wphpc_stock_status'] : '';
		$events_meta['wphpc_product_type'] 		= isset( $_POST['wphpc_product_type'] ) && filter_var( $_POST['wphpc_product_type'], FILTER_SANITIZE_STRING ) ? $_POST['wphpc_product_type'] : '';
		$events_meta['wphpc_product_url'] 		= isset( $_POST['wphpc_product_url'] ) ? sanitize_text_field($_POST['wphpc_product_url']) : '';


		foreach ( $events_meta as $key => $value ) {
			if ( 'revision' === $post->post_type ) {
				return;
			}
			if ( get_post_meta( $post_id, $key, false ) ) {
				update_post_meta( $post_id, $key, $value );
			} else {
				add_post_meta($post_id, $key, $value );
			}
			if ( ! $value ) {
				delete_post_meta( $post_id, $key );
			}
		}
	}

	function wphpc_settings() {
		require_once WPHPC_PATH . 'admin/view/' . $this->wphpc_assets_prefix . 'admin-settings.php';
	}

	function wphpc_get_help() {
		require_once WPHPC_PATH . 'admin/view/wphpc-help-usage.php';
	}

	function wphpc_display_notification( $type, $msg ) { 
		?>
		<div class="wphpc-alert <?php printf('%s', $type); ?>">
			<span class="wphpc-closebtn">&times;</span>
			<strong><?php esc_html_e(ucfirst($type), 'hm-product-catalog'); ?>!</strong>
			<?php esc_html_e($msg, 'hm-product-catalog'); ?>
		</div>
		<?php
	}
}
?>
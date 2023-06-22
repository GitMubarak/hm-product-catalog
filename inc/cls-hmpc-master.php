<?php
if ( ! defined('ABSPATH') ) exit;
/**
 * Our main plugin class
*/
class WPHPC_Master {

	protected $wphpc_loader;
	protected $wphpc_version;

	/**
	 * Class Constructor
	*/
	function __construct() {
		$this->wphpc_version = WPHPC_VERSION;
		add_action( 'plugins_loaded', array($this, 'hpc_load_plugin_textdomain') );
		$this->wphpc_load_dependencies();
		$this->wphpc_trigger_admin_hooks();
		$this->wphpc_trigger_front_hooks();
	}

	function hpc_load_plugin_textdomain(){
		load_plugin_textdomain( 'hm-product-catalog', FALSE, 'hm-product-catalog' . '/languages/' );
	}

	private function wphpc_load_dependencies() {
		require_once 'cls-hmpc-currency.php';
		require_once WPHPC_PATH . 'admin/' . WPHPC_CLS_PRFX . 'admin.php';
		require_once WPHPC_PATH . 'front/' . WPHPC_CLS_PRFX . 'front.php';
		require_once WPHPC_PATH . 'inc/' . WPHPC_CLS_PRFX . 'loader.php';
		$this->wphpc_loader = new WPHPC_Loader();
	}

	private function wphpc_trigger_admin_hooks() {
		$wphpc_admin = new WPHPC_Admin($this->wphpc_version());
		$this->wphpc_loader->add_action( 'admin_enqueue_scripts', $wphpc_admin, WPHPC_PRFX . 'enqueue_assets' );
		$this->wphpc_loader->add_action( 'init', $wphpc_admin, WPHPC_PRFX . 'custom_post_type', 0 );
		$this->wphpc_loader->add_action( 'init', $wphpc_admin, WPHPC_PRFX . 'taxonomy_for_products', 0 );
		$this->wphpc_loader->add_action( 'add_meta_boxes', $wphpc_admin, WPHPC_PRFX . 'product_details_metaboxes' );
		$this->wphpc_loader->add_action( 'save_post', $wphpc_admin, WPHPC_PRFX . 'save_product_meta', 1, 1 );
		$this->wphpc_loader->add_action( 'admin_menu', $wphpc_admin, WPHPC_PRFX . 'admin_menu', 0 );
		//$this->wphpc_loader->add_filter( 'plugin_row_meta', $wphpc_admin, 'wphpc_donation_link_to_plugin_active', 10, 2 );
	}

	function wphpc_trigger_front_hooks() {
		$wphpc_front = new WPHPC_Front($this->wphpc_version());
		$this->wphpc_loader->add_action( 'wp_enqueue_scripts', $wphpc_front, WPHPC_PRFX . 'front_assets' );
		$this->wphpc_loader->add_filter( 'single_template', $wphpc_front, WPHPC_PRFX . 'load_single_template', 99 );
		$wphpc_front->wphpc_load_shortcode();
	}

	function wphpc_run() {
		$this->wphpc_loader->wphpc_run();
	}

	function wphpc_version(){
		return $this->wphpc_version;
	}
}
?>

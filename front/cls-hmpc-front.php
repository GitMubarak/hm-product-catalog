<?php
if ( ! defined('ABSPATH') ) exit;

/**
*	Front Parent Class
*/
class WPHPC_Front {

	use WPHPC_Currency;

	private $wphpc_version;

	function __construct( $version ) {
		$this->wphpc_version = $version;
		$this->wphpc_assets_prefix = substr(WPHPC_PRFX, 0, -1) . '-';
	}
	
	function wphpc_front_assets() {
		
		wp_enqueue_style(	'wphpc-jquery-ui',
							WPHPC_ASSETS . 'css/jquery-ui.css',
							array(),
							$this->wphpc_version,
							FALSE );
		wp_enqueue_style(	'wphpc-front-style',
							WPHPC_ASSETS . 'css/' . $this->wphpc_assets_prefix . 'front-style.css',
							array(),
							$this->wphpc_version,
							FALSE );

		if ( ! wp_script_is( 'jquery' ) ) {
			wp_enqueue_script('jquery');
		}
		
		wp_enqueue_script(  'wphpc-jquery-ui',
							WPHPC_ASSETS . 'js/jquery-ui.js',
							array('jquery'),
							$this->wphpc_version,
							TRUE );

		wp_enqueue_script(  'wphpc-front-script',
							WPHPC_ASSETS . 'js/wphpc-front-script.js',
							array('jquery'),
							$this->wphpc_version,
							TRUE );
	}

	function wphpc_load_shortcode() {
		add_shortcode( 'hm_product_catalog', array( $this, 'wphpc_load_shortcode_view' ) );
	}
	
	function wphpc_load_shortcode_view( $attr ) {
		
		// Assign all shortcode params
		$wphpcCatelog 		= isset( $attr['catalog']) ? $attr['catalog'] : '';
		$wphpcDisplay		= isset( $attr['display']) ? $attr['display'] : '';
		$wphpcPagination	= isset( $attr['pagination']) ? $attr['pagination'] : false;

		$output = '';
		ob_start();
		include WPHPC_PATH . 'front/view/wphpc-front-view.php';
		$output .= ob_get_clean();
		return $output;
	}

	function wphpc_load_single_template( $template ) {

		global $post;
		
		if ( 'products' === $post->post_type && locate_template( array( 'wphpc-single-product.php' ) ) !== $template ) {
			return WPHPC_PATH . 'front/view/wphpc-single-product.php';
		}

		return $template;
	}

	function get_currency_symbol( $currency ) {

		$wphpcCurrency = $this->hm_get_all_currency();
		$symbol = '';

		foreach ( $wphpcCurrency as $wpsdcurr ) {
			
			if ( $currency === $wpsdcurr->currency ) {
				$symbol = $wpsdcurr->symbol;
			}

		}

		return $symbol;
	}
}
?>
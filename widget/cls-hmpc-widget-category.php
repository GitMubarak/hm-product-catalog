<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Widget Master Class
*/
class Hmpc_Widget_Category extends WP_Widget {

	function __construct() {
		parent::__construct('hmpc-widget-category', __('Product Category', 'hm-product-catalog'), array('description' => __('Product Category', 'hm-product-catalog')));

		add_action( 'load-widgets.php', array(&$this, 'hmpc_color_picker_load') );
	}

	function hmpc_color_picker_load() {    
		wp_enqueue_style( 'wp-color-picker' );        
		wp_enqueue_script( 'wp-color-picker' );    
	}
	
	/**
	* Front-end display of widget.
	*
	* @see WP_Widget::widget()
	*
	* @param array $args Widget arguments.
	* @param array $instance Saved values from database.
	*/
	function widget( $args, $instance ) {

		echo $args['before_widget'];
		
		if ( ! empty( $instance['hmpc_title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['hmpc_title'] ). $args['after_title'];
		}

        $product_categories  = get_terms( array( 'taxonomy' => 'product_category', 'hide_empty' => true, 'order' => 'ASC',  'parent' => 0 ) );
        
		foreach ( $product_categories as $p_cat ) { 
            ?>
            <a href="<?php echo get_permalink(); ?>" class="hmpc-widget-category-a-id" data-category="<?php esc_attr_e( urlencode( $p_cat->name ) ); ?>">
                <label for="hmpc-category-<?php esc_attr_e( $p_cat->slug ); ?>"><?php esc_html_e( $p_cat->name ); ?></label>
            </a>
            <?php
        }
		//==========================
		echo $args['after_widget'];
	}
	
	/**
	* Widget Form
	*
	* @see WP_Widget::form()
	*
	* @param array $instance Previously saved values from database.
	*/
	public function form( $instance ) {

		$hmpc_title = isset( $instance['hmpc_title'] ) ? $instance['hmpc_title'] : __( 'Product Category', 'hm-product-catalog' );
		//$wbg_wl_dnld_btn_font_clr 	= isset( $instance['wbg_wl_dnld_btn_font_clr'] ) ? sanitize_text_field( $instance['wbg_wl_dnld_btn_font_clr'] ) : '#FFFFFF';
		?>
		<p>
			<label><?php _e( 'Title', 'hm-product-catalog' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('hmpc_title'); ?>" name="<?php echo $this->get_field_name('hmpc_title'); ?>" type="text" value="<?php esc_attr_e( $hmpc_title ); ?>">
		</p>
		<?php
	}
	
	/*
	* Update Widget Value
	*
	* @see WP_Widget::update()
	*
	* @param array $new_instance Values just sent to be saved.
	* @param array $old_instance Previously saved values from database.
	*
	* @return array Updated safe values to be saved.
	*/
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['hmpc_title'] 					= isset( $new_instance['hmpc_title'] ) ? strip_tags( $new_instance['hmpc_title'] ) : __( 'Product Category', 'hm-product-catalog' );
		//$instance['wbg_wl_dnld_btn_font_clr'] 	= isset( $new_instance['wbg_wl_dnld_btn_font_clr'] ) ? sanitize_text_field( $new_instance['wbg_wl_dnld_btn_font_clr'] ) : '#FFFFFF';
		return $instance;
	}
}
?>
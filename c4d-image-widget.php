<?php
/*
Plugin Name: C4D Image Widget
Plugin URI: http://coffee4dev.com/
Description: Simple Image Widget
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-image-widget
Version: 2.0.0
*/

define('C4DIMGWIDGET_PLUGIN_URI', plugins_url('', __FILE__));

add_action( 'widgets_init', 'c4d_image_widget_register' );
add_action( 'admin_enqueue_scripts', 'c4d_image_widget_load_scripts' );
add_filter( 'plugin_row_meta', 'c4d_image_widget_plugin_row_meta', 10, 2 );

function c4d_image_widget_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'forum' => '<a href="http://coffee4dev.com/forums/">Forum</<a>',
            'premium' => '<a href="http://coffee4dev.com">Premium Support</<a>'
        );
        
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}

function c4d_image_widget_load_scripts($hook) {
	if ( 'widgets.php' == $hook ) {
    	wp_enqueue_script( 'c4d-mega-menu-admin-js', C4DIMGWIDGET_PLUGIN_URI . '/assets/admin.js' );    
    	wp_enqueue_style( 'c4d-mega-menu-admin-style', C4DIMGWIDGET_PLUGIN_URI.'/assets/admin.css' );
    }
}

function c4d_image_widget_register() {
    register_widget( 'C4DIMAGEWIDGET_Widget' );
}

class C4DIMAGEWIDGET_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'c4d-image-widget', // Base ID
			esc_html__( 'Image Widget', 'c4d-image-widget' ), // Name
			array( 'description' => esc_html__( 'Simple Widget to Display Image - C4D', 'c4d-image-widget' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$suffix = !empty($instance['suffix']) ? 'class="'.$instance['suffix'].'"' : '';
		echo '<div '.$suffix.'>';
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		
		if ( ! empty( $instance['image'] ) ) {
			$image = wp_get_attachment_image_src($instance['image'], $instance['size']);
			if ($image) {
				if ( ! empty( $instance['link'] ) ) {
					$newWindow = !empty($instance['link_open']) ? 'target="blank"' : '';
 					echo '<a class="link" '.$newWindow.' href="'.$instance['link'].'">';
				}
				echo '<img alt="'.$instance['alt'].'" src="'.$image[0].'">';	
				if ( ! empty( $instance['link'] ) ) {
					echo '</a>';
				}
			}
		}
		if ( ! empty( $instance['title'] ) ) {
			echo '<h3 class="title">'.$instance['title'].'</h3>';
		}
		if ( ! empty( $instance['desc'] ) ) {
			echo '<div class="description">'.$instance['desc'].'</div>';	
		}
		echo $args['after_widget'];
		echo '</div>';
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title 		= ! empty( $instance['title'] ) ? $instance['title'] : '';
		$image 		= ! empty( $instance['image'] ) ? $instance['image'] : '';
		$size 		= ! empty( $instance['size'] ) ? $instance['size'] : '';
		$titleImage = ! empty( $instance['title_image'] ) ? $instance['title_image'] : '';
		$alt 		= ! empty( $instance['alt'] ) ? $instance['alt'] : $titleImage;
		$desc 		= ! empty( $instance['desc'] ) ? $instance['desc'] : '';
		$link 		= ! empty( $instance['link'] ) ? $instance['link'] : '';
		$linkOpen 	= ! empty( $instance['link_open'] ) ? $instance['link_open'] : '';
		$suffix 	= ! empty( $instance['suffix'] ) ? $instance['suffix'] : '';
		?>
		<p>
			<label><?php esc_attr_e( 'Widget Title:', 'c4d-image-widget' ); ?></label> 
			<input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>">
		</p>
		<p>
			<div class="c4d-image-widget-select-image">
				<div class="image-display">
					<?php if ($image) : ?>
						<img src="<?php echo wp_get_attachment_url($image); ?>">
					<?php endif; ?>
				</div>
				<span class="button upload"><?php esc_html_e('Select Image', 'c4d-image-widget'); ?></span>
				<span class="button remove hidden"><?php esc_html_e('Remove Image', 'c4d-image-widget'); ?></span>
				<input class="image-value widefat" type="hidden" value="<?php echo $image; ?>" name="<?php echo $this->get_field_name('image'); ?>"/>
			</div>
		</p>
		<p>
			<label><?php esc_attr_e( 'Size:', 'c4d-image-widget' ); ?></label> 
			<?php 
				$images = get_intermediate_image_sizes();
			?>
			<select class="widefat" name="<?php echo $this->get_field_name('size'); ?>">
				<?php foreach($images as $image ): ?>
					<option <?php selected($size, $image); ?> value="<?php echo $image; ?>"><?php echo $image; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label><?php esc_attr_e( 'Alt Text:', 'c4d-image-widget' ); ?></label> 
			<input class="widefat" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo $alt; ?>" />
		</p>
		<p>
			<label><?php esc_attr_e( 'Image Title:', 'c4d-image-widget' ); ?></label> 
			<input class="widefat" name="<?php echo $this->get_field_name('title_image'); ?>" type="text" value="<?php echo $titleImage; ?>" />
		</p>
		<p>
			<label><?php esc_attr_e( 'Description:', 'c4d-image-widget' ); ?></label> 
			<textarea class="widefat" name="<?php echo $this->get_field_name('desc'); ?>"><?php echo $desc; ?></textarea>
		</p>
		<p>
			<label><?php esc_attr_e( 'Link:', 'c4d-image-widget' ); ?></label> 
			<input class="widefat" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
			<input class="checkbox" id="<?php echo $this->get_field_id('link_open'); ?>" name="<?php echo $this->get_field_name('link_open'); ?>" type="checkbox" value="0" <?php checked( $linkOpen, 0 ); ?> />
						<label for="<?php echo $this->get_field_id('link_open'); ?>"><?php esc_html_e('Open in new window', 'c4d-image-widget'); ?></label>
		</p>
		<p>
			<label><?php esc_attr_e( 'Suffix Class:', 'c4d-image-widget' ); ?></label> 
			<input class="widefat" name="<?php echo $this->get_field_name('suffix'); ?>" type="text" value="<?php echo $suffix; ?>" />
		</p>
		<?php 
	}
} 

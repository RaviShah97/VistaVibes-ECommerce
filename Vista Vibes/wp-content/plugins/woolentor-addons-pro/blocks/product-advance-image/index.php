<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
	$areaClasses = array( $uniqClass, 'woolentor-advance-product-image-area', 'wlpro-product-thumbnails images' );

	!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
	!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
	!empty( $settings['thumbnailsPosition'] ) ? $areaClasses[] = 'thumbnails-tab-position-'.$settings['thumbnailsPosition'] : '';
	!empty( $settings['layoutStyle'] ) ? $areaClasses[] = 'thumbnails-layout-'.$settings['layoutStyle'] : '';
	!empty( $settings['layoutStyle'] ) && ( $settings['layoutStyle'] == 'tabslider' ) ? $areaClasses[] = 'woolentor-block-slider-navforas' : '';

	// Slider Options
	if( $settings['layoutStyle'] == 'tabslider' ){

		$main_slider_settings = [
			'arrows' => ( true === $settings['mainArrows'] ),
			'dots' => ( true === $settings['mainDots'] )
		];
		$thumbnail_slider_settings = [
			'slider_items' => $settings['thumbSliderItems'],
			'arrows' => ( true === $settings['thumbnaiArrows'] ),
			'slidertype' => ( $settings['thumbnailsPosition'] == 'right' || $settings['thumbnailsPosition'] == 'left' ) ? true : false
		];

		$slider_settings = [
			'mainslider' => $main_slider_settings,
			'thumbnailslider' => $thumbnail_slider_settings
		];

	}else if( $settings['layoutStyle'] == 'slider' ){
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		$slider_settings = [
			'arrows' => ( true === $settings['arrows'] ),
			'dots' => ( true === $settings['dots'] ),
			'autoplay' => ( true === $settings['autoplay'] ),
			'autoplay_speed' => absint( $settings['autoplaySpeed'] ),
			'animation_speed' => absint( $settings['animationSpeed'] ),
			'pause_on_hover' => ( true === $settings['pauseOnHover'] ),
			'rtl' => $is_rtl,
		];

		$slider_responsive_settings = [
			'product_items' => $settings['sliderItems'],
			'scroll_columns' => $settings['scrollColumns'],
			'tablet_width' => $settings['tabletWidth'],
			'tablet_display_columns' => $settings['tabletDisplayColumns'],
			'tablet_scroll_columns' => $settings['tabletScrollColumns'],
			'mobile_width' => $settings['mobileWidth'],
			'mobile_display_columns' => $settings['mobileDisplayColumns'],
			'mobile_scroll_columns' => $settings['mobileScrollColumns'],

		];
		$slider_settings = array_merge( $slider_settings, $slider_responsive_settings );

	}else{
		$slider_settings = [];
	}

	if( $block['is_editor'] ){
		$post = '';
		$product = wc_get_product( woolentor_get_last_product_id() );
	} else{
		global $post;
		$product = wc_get_product();
	}
	if ( empty( $product ) ) { return; }

	$gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
	if ( $product->get_image_id() ){
		$gallery_images_ids = array( 'wlthumbnails_id' => $product->get_image_id() ) + $gallery_images_ids;
	}

	// Placeholder image set
	if( empty( $gallery_images_ids ) ){
		$gallery_images_ids = array( 'wlthumbnails_id' => get_option( 'woocommerce_placeholder_image', 0 ) );
	}
		
?>
<?php if( $block['is_editor'] ){ echo '<div class="product">'; } ?>
<div class="<?php echo implode(' ', $areaClasses ); ?>" data-settings='<?php echo wp_json_encode( $slider_settings );  ?>'>
	<div class="wl-thumbnails-image-area">

		<?php if( $settings['layoutStyle'] == 'tab' ): ?>

			<?php if( $settings['thumbnailsPosition'] == 'left' || $settings['thumbnailsPosition'] == 'top' ): ?>
				<ul class="woolentor-thumbanis-image">
					<?php
						foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
							echo '<li data-wlimage="'.wp_get_attachment_image_url( $gallery_attachment_id, 'woocommerce_single' ).'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</li>';
						}
					?>
				</ul>
			<?php endif; ?>
			<div class="woocommerce-product-gallery__image">
				<?php
					if( $settings['saleBadgeHide'] != true ){
						if( $block['is_editor'] ){
							if ( $product->is_on_sale() ) { 
								echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
							}
						}else{
							woolentor_show_product_sale_flash();
						}
					}

					if(function_exists('woolentor_custom_product_badge') && $settings['customSaleBadgeHide'] != true ){
						woolentor_custom_product_badge();
					}
					echo wp_get_attachment_image( reset( $gallery_images_ids ), 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) );
				?>
			</div>
			<?php if( $settings['thumbnailsPosition'] == 'right' || $settings['thumbnailsPosition'] == 'bottom' ): ?>
				<ul class="woolentor-thumbanis-image">
					<?php
						foreach ( $gallery_images_ids as $gallery_attachment_id ) {
							echo '<li data-wlimage="'.wp_get_attachment_image_url( $gallery_attachment_id, 'woocommerce_single' ).'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</li>';
						}
					?>
				</ul>
			<?php endif; ?>
		
		<?php elseif( $settings['layoutStyle'] == 'tabslider' ):

			$arrow_style = $settings['thumbArrowStyle'] === 'two' ? 'wl-thumb-nav-style-1' : '';

			if( $settings['saleBadgeHide'] != true ){
				if( $block['is_editor'] ){
					if ( $product->is_on_sale() ) { 
						echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
					}
				}else{
					woolentor_show_product_sale_flash();
				}
			}
			if( function_exists('woolentor_custom_product_badge') && $settings['customSaleBadgeHide'] != true ){
				woolentor_custom_product_badge();
			}
			if( $settings['thumbnailsPosition'] == 'left' || $settings['thumbnailsPosition'] == 'top' ){
				echo '<div class="woolentor-thumbnails '.$arrow_style.'" style="display:none;">';
					foreach ( $gallery_images_ids as $gallery_attachment_id ) {
						echo '<div class="woolentor-thumb-single">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</div>';
					}
				echo '</div>';
			}

			echo '<div class="woolentor-learg-img woocommerce-product-gallery__image" style="display:none;">';
				$i = 0;
				foreach ( $gallery_images_ids as $gallery_attachment_id ) {
					$i++;
					if( $i == 1 ){
						echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) ).'</div>';
					}else{
						echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' ).'</div>';
					}
				}
			echo '</div>';
			if( $settings['thumbnailsPosition'] == 'right' || $settings['thumbnailsPosition'] == 'bottom' ){
				echo '<div class="woolentor-thumbnails '.$arrow_style.'" style="display:none;">';
					foreach ( $gallery_images_ids as $gallery_attachment_id ) {
						echo '<div class="woolentor-thumb-single">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</div>';
					}
				echo '</div>';
			}
		?>

		<?php elseif( $settings['layoutStyle'] == 'gallery' ): ?>
			<div class="woocommerce-product-gallery__image wl-single-gallery">
				<?php
					if( $settings['saleBadgeHide'] != true ){
						if( $block['is_editor'] ){
							if ( $product->is_on_sale() ) { 
								echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
							}
						}else{
							woolentor_show_product_sale_flash();
						}
					}
					if( function_exists('woolentor_custom_product_badge') && $settings['customSaleBadgeHide'] != true ){
						woolentor_custom_product_badge();
					}
					echo wp_get_attachment_image( reset( $gallery_images_ids ), 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) );
				?>
			</div>
			<?php
				$imagecount = 1;
				foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
					if( $imagecount == 1 ){
						$imagecount++;
						continue;
					}else{
						echo '<div class="wl-single-gallery">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' ).'</div>';
					}
				}
			?>
		
		<?php elseif( $settings['layoutStyle'] == 'slider' ): ?>
			<div class="product-slider wl-thumbnails-slider woocommerce-product-gallery__image" data-settings='<?php echo wp_json_encode( $slider_settings );  ?>' style="display:none;">
				<?php
					$j = 0;
					foreach ( $gallery_images_ids as $gallery_attachment_id ) {
						$j++;
						if( $j == 1 ){
							echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) ).'</div>';
						}else{
							echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' ).'</div>';
						}
					}
				?>
			</div>
		
		<?php else: ?>
			<div class="woocommerce-product-gallery__image">
				<?php
					if( $settings['saleBadgeHide'] != true ){
						if( $block['is_editor'] ){
							if ( $product->is_on_sale() ) { 
								echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
							}
						}else{
							woolentor_show_product_sale_flash();
						}
					}
					if(function_exists('woolentor_custom_product_badge')  && $settings['customSaleBadgeHide'] != true){
						woolentor_custom_product_badge();
					}
					echo wp_get_attachment_image( reset( $gallery_images_ids ), 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) );
				?>
			</div>
			
		<?php endif; ?>

		<?php if( $settings['layoutStyle'] == 'tabslider' || $settings['layoutStyle'] == 'slider' ): ?>
            <script>
                ;jQuery(document).ready(function($) {
                    'use strict';
                    $( '.single_variation_wrap' ).on( 'show_variation', function ( event, variation ) {
                        $('.wlpro-product-thumbnails').find('.woolentor-learg-img').slick('slickGoTo', 0);
                    });

                });
            </script>
        <?php endif; ?>

	</div>
</div>
<?php if( $block['is_editor'] ){ echo '</div>'; } ?>
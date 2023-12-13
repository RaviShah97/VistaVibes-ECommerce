<?php
/**
 * Terms.
 */

namespace WLPF\Admin;

/**
 * Class.
 */
class Terms {

    /**
     * Constructor.
     */
    public function __construct() {
        $post_type = ( isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : null );
        $taxonomy = ( isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : null );

        if ( ( 'product' === $post_type ) && ! empty( $taxonomy ) ) {
            add_action( $taxonomy . '_add_form_fields', array( $this, 'add_form_fields' ), 14, 1 );
            add_action( $taxonomy . '_edit_form_fields', array( $this, 'edit_form_fields' ), 14, 2 );
        }

        add_action( 'created_term', array( $this, 'save_values' ), 10, 3 );
        add_action( 'edit_term', array( $this, 'save_values' ), 10, 3 );
    }

    /**
     * Add form fields.
     */
    public function add_form_fields( $taxonomy = '' ) {
        $product_taxonomies = get_object_taxonomies( 'product' );

        if ( empty( $taxonomy ) || ! in_array( $taxonomy, $product_taxonomies ) ) {
            return;
        }
        ?>
        <div class="form-field term-wlpf-color-wrap">
            <label><?php esc_html_e( 'Product Filter Color', 'woolentor-pro' ); ?></label>
            <?php $this->color_field(); ?>
        </div>
        <div class="form-field term-wlpf-image-wrap">
            <label><?php esc_html_e( 'Product Filter Image', 'woolentor-pro' ); ?></label>
            <?php $this->image_field(); ?>
        </div>
        <?php
    }

    /**
     * Edit form fields.
     */
    public function edit_form_fields( $term = null, $taxonomy = '' ) {
        $term_id = ( ( is_object( $term ) && isset( $term->term_id ) ) ? absint( $term->term_id ) : 0 );
        $product_taxonomies = get_object_taxonomies( 'product' );

        if ( empty( $term_id ) || empty( $taxonomy ) || ! in_array( $taxonomy, $product_taxonomies ) ) {
            return;
        }

        $color = get_term_meta( $term_id, 'wlpf_color', true );
        $image_id = get_term_meta( $term_id, 'wlpf_image_id', true );
        ?>
        <tr class="form-field term-wlpf-color-wrap">
            <th scope="row">
                <label><?php esc_html_e( 'Product Filter Color', 'woolentor-pro' ); ?></label>
            </th>
            <td><?php $this->color_field( $color ); ?></td>
        </tr>
        <tr class="form-field term-wlpf-image-wrap">
			<th scope="row" valign="top">
                <label><?php esc_html_e( 'Product Filter Image', 'woolentor-pro' ); ?></label>
            </th>
			<td><?php $this->image_field( $image_id ); ?></td>
		</tr>
        <?php
    }

    /**
     * Color field.
     */
    public function color_field( $color = '' ) {
        $color = sanitize_hex_color( $color );
        ?>
        <div class="wlpf-term-color-field">
            <input type="hidden" name="wlpf-color" class="wlpf-term-color-input" value="<?php echo esc_attr( $color ); ?>">
        </div>
        <p class="description" id="wlpf-color-description">Applicable to ShopLentor Product Filter module, when Terms type is Color.</p>
        <?php
    }

    /**
     * Image field.
     */
    public function image_field( $image_id = 0 ) {
        $image_id = absint( $image_id );
        $image_url = ( ! empty( $image_id ) ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src() );

        $attributes = 'data-wlpt-title="' . esc_attr( 'Choose an image', 'woolentor-pro' ) . '"';
        $attributes .= ' data-wlpt-button-text="' . esc_attr( 'Use image', 'woolentor-pro' ) . '"';
        $attributes .= ' data-wlpt-placeholder-url="' . esc_url( wc_placeholder_img_src() ) . '"';
        ?>
        <div class="wlpf-term-image-field" <?php echo wp_kses_post( $attributes ); ?>>
            <div class="wlpf-term-image-preview" style="float: left; margin-right: 10px;">
                <img src="<?php echo esc_url( $image_url ); ?>" width="60px" height="60px" />
            </div>
            <div style="line-height: 60px;">
                <input type="hidden" name="wlpf-image-id" class="wlpf-term-image-id-input" value="<?php echo esc_attr( $image_id ); ?>" />
                <button type="button" class="button wlpf-term-image-upload-button"><?php esc_html_e( 'Upload/Add image', 'woolentor-pro' ); ?></button>
                <?php
                if ( ! empty( $image_id ) ) {
                    ?>
                    <button type="button" class="button wlpf-term-image-remove-button"><?php esc_html_e( 'Remove image', 'woolentor-pro' ); ?></button>
                    <?php
                } else {
                    ?>
                    <button type="button" class="button wlpf-term-image-remove-button" style="display: none;"><?php esc_html_e( 'Remove image', 'woolentor-pro' ); ?></button>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="clear"></div>
        <p class="description" id="wlpf-color-description">Applicable to ShopLentor Product Filter module, when Terms type is Image.</p>
        <?php
    }

    /**
     * Save values.
     */
    public function save_values( $term_id, $tt_id = '', $taxonomy = '' ) {
        $product_taxonomies = get_object_taxonomies( 'product' );

        if ( empty( $taxonomy ) || ! in_array( $taxonomy, $product_taxonomies ) ) {
            return;
        }

		if ( isset( $_POST['display_type'] ) ) {
            $display_type = sanitize_text_field( $_POST['display_type'] );
			update_term_meta( $term_id, 'display_type', esc_attr( $display_type ) );
		}

		if ( isset( $_POST['wlpf-color'] ) ) {
            $wlpf_color = sanitize_hex_color( $_POST['wlpf-color'] );
			update_term_meta( $term_id, 'wlpf_color', $wlpf_color );
		}

		if ( isset( $_POST['wlpf-image-id'] ) ) {
            $wlpf_image_id = absint( $_POST['wlpf-image-id'] );
			update_term_meta( $term_id, 'wlpf_image_id', $wlpf_image_id );
		}
	}

}
<?php
/**
 * Updater.
 */

namespace WLPF;

/**
 * Class.
 */
class Updater {

	/**
     * Constructor.
     */
    public function __construct() {
        $this->transfer_term_meta();
    }

	/**
     * Transfer term meta.
     */
    public function transfer_term_meta() {
        $transferred = rest_sanitize_boolean( get_option( 'wlpf_terms_meta_transferred', '0' ) );

        if ( true === $transferred ) {
            return;
        }

        $settings = get_option( 'woolentor_product_filter_settings' );
        $settings = wlpf_cast( $settings, 'array' );

        if ( ! empty( $settings ) ) {
            $filters = ( isset( $settings['filters'] ) ? wlpf_cast( $settings['filters'], 'array' ) : array() );

            if ( ! empty( $filters ) ) {
                foreach ( $filters as $index => $filter ) {
                    if ( ! empty( $filter ) ) {
                        foreach ( $filter as $key => $value ) {
                            $term_type = '';

                            if ( preg_match( '/^filter_.*_color$/', $key ) ) {
                                $term_key = str_replace( '_color', '', $key );
                                $term_type = 'color';
                            } elseif ( preg_match( '/^filter_.*_image$/', $key ) ) {
                                $term_key = str_replace( '_image', '', $key );
                                $term_type = 'image';
                            }

                            if ( empty( $term_type ) ) {
                                continue;
                            }

                            $offset = strrpos( $term_key, '_term_' );

                            if ( false === $offset ) {
                                continue;
                            }

                            $term_id = absint( substr( $term_key, ( $offset + 6 ) ) );

                            if ( ! empty( $term_id ) && ! empty( $value ) ) {
                                if ( 'color' === $term_type ) {
                                    $value = sanitize_hex_color( $value );

                                    if ( ! empty( $value ) ) {
                                        update_term_meta( $term_id, 'wlpf_color', $value );
                                    }
                                } else {
                                    $value = esc_url( $value );
                                    $value = attachment_url_to_postid( $value );

                                    if ( ! empty( $value ) ) {
                                        update_term_meta( $term_id, 'wlpf_image_id', $value );
                                    }
                                }
                            }

                            unset( $filter[ $key ] );
                        }

                        $filters[ $index ] = $filter;
                    }
                }

                $settings['filters'] = $filters;
            }

            update_option( 'woolentor_product_filter_settings', $settings );
        }

        update_option( 'wlpf_terms_meta_transferred', '1' );
    }

}
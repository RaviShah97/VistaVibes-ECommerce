<?php
    class WooLentor_MyAccount{

    private $itemsorder = [];
    private $userinfo = [];

    public function __construct( $menuorders = array(), $userinfo = array( 'status'=>'no','image'=>'') ) {
        $this->itemsorder = $menuorders;
        $this->userinfo   = $userinfo;
        if( $userinfo['status'] == 'yes' ){
            add_action( 'woocommerce_before_account_navigation', [ $this, 'navigation_user' ] );
        }

        add_filter( 'woocommerce_account_menu_items',[ $this, 'navigation_items' ], 15, 2 );
        add_filter( 'woocommerce_get_endpoint_url', [ $this, 'navigation_endpoint_url' ], 15, 4 );

        $this->custom_content();

    }

    /**
     * Initialize action
     *
     * @return void
     */
    public static function init(){
        add_action( 'init', [ __CLASS__, 'add_custom_endpoints' ] );
        add_action( 'init', [ __CLASS__, 'rewrite_rules' ] );
    }

    /**
     * Add custom endpoints.
     */
    public static function add_custom_endpoints() {
        
        $custom_menu_slugs = self::get_items_custom_key();
        if ( empty( $custom_menu_slugs ) || ! is_array( $custom_menu_slugs ) ) {
            return;
        }

        $mask = WC()->query->get_endpoints_mask();

        foreach ( $custom_menu_slugs as $key => $slug ) {
            if ( apply_filters( 'woolentor_skip_add_rewrite_endpoint', false, $key ) || 'dashboard' === $key || isset( WC()->query->query_vars[ $key ] ) ) {
                continue;
            }

            WC()->query->query_vars[ $key ] = $key;
            add_rewrite_endpoint( $key, $mask );
        }

    }

    /**
     * Flash rewrite rules.
     * @return void
     */
    public static function rewrite_rules( $flash = false ) {
        $is_flush = get_option( 'woolentor_flush_rewrite_rules', 0 );

        if ( ! $is_flush || $flash ) {
            // Change option.
            update_option( 'woolentor_flush_rewrite_rules', 1 );
            // Flush rewrite rules.
            flush_rewrite_rules();
        }

    }

    /**
     * Get custom menu key.
     *
     * @return array
     */
    public static function get_items_custom_key() {

        $myaccount_page = method_exists( 'Woolentor_Woo_Custom_Template_Layout_Pro', 'my_account_page_manage' ) ? Woolentor_Woo_Custom_Template_Layout_Pro::instance()->my_account_page_manage() : '0';

        if( Woolentor_Template_Manager::instance()->edit_with_gutenberg( $myaccount_page ) ){
            $blocks_setting = woolentorBlocks_get_settings_by_blockName( $myaccount_page, 'woolentor/my-account' );
            $widget_setting = isset( $blocks_setting['navigationItemList'] ) ? $blocks_setting['navigationItemList'] : [];
        }else{
            $widget_setting_data = woolentor_pro_get_settings_by_widget_name( $myaccount_page, 'wl-myaccount-account' );
            $widget_setting = isset( $widget_setting_data['settings']['navigation_list'] ) ? $widget_setting_data['settings']['navigation_list'] : [];
        }

        if( empty( $widget_setting ) ){
            return;
        }

        $widget_setting = array_filter( $widget_setting, function( $item ){
            if( isset( $item['menu_items'] ) && $item['menu_items'] == 'customadd' ){
                return true;
            }else{
                if( isset( $item['menuKey'] ) && $item['menuKey'] == 'customadd' ){
                    return true;
                }
            }
        } );

        $get_custom_keys = maybe_unserialize( get_option( 'woolentor_myaccount_custom_menu_key', [] ) );

        $custom_menu_block = array_column( $widget_setting, 'menuKey', 'menuCusKey' );
        $custom_menu_widget = array_column( $widget_setting, 'menu_items', 'menu_key' );
        $custom_menu_keys = empty( $custom_menu_block ) ? $custom_menu_widget : $custom_menu_block;

        if( $custom_menu_keys !== $get_custom_keys ){
            update_option( 'woolentor_myaccount_custom_menu_key', serialize( $custom_menu_keys ) );
            update_option( 'woolentor_flush_rewrite_rules', 0 );
        }
        
        return $custom_menu_keys;
    }

    // My account navigation Item
    public function navigation_items( $items, $endpoints ){
        $items = array();
        foreach ( $this->itemsorder as $key => $item ) {
            $items[$key] = $item['title'];
        }
        return $items;
    }

    // My account navigation URL
    public function navigation_endpoint_url( $url, $endpoint, $value, $permalink ){
        foreach ( $this->itemsorder as $key => $item ) {
            if( ( 'customadd' === $item['type'] && 'yes' === $item['external_url']) && ( $key === $endpoint ) ){
                $url = $item['url'];
            }
        }
        return $url;
    }

    // My Account User Info
    public function navigation_user(){
        $current_user = wp_get_current_user();
        if ( $current_user->display_name ) {
            $name = $current_user->display_name;
        } else {
            $name = esc_html__( 'Welcome!', 'woolentor-pro' );
        }
        $name = apply_filters( 'woolentor_profile_name', $name );
        ?>
            <div class="woolentor-user-area">
                <div class="woolentor-user-image">
                    <?php
                        if( $this->userinfo['image'] ){
                            echo wp_kses_post( $this->userinfo['image'] );
                        }else{
                            echo get_avatar( $current_user->user_email, 125 );
                        }
                    ?>
                </div>
                <div class="woolentor-user-info">
                    <span class="woolentor-username"><?php echo esc_attr( $name ); ?></span>
                    <span class="woolentor-logout"><a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>"><?php echo esc_html__( 'Logout', 'woolentor-pro' ); ?></a></span>
                </div>
            </div>
        <?php

    }


    /*
    * For Custom Endpoint
    * Add Custom Content
    */
    public function custom_content() {
        foreach ( $this->itemsorder as $key => $item ) {
            if( isset( $item['content'] ) && 'dashboard' !== $key ){
                add_action( 'woocommerce_account_' . $key . '_endpoint', [ $this, 'render_custom_content' ] );
            }else{
                if( isset( $item['content'] ) && 'dashboard' === $key ){
                    add_action( 'woocommerce_account_' . $key, [ $this, 'render_custom_content' ] );
                }
            }
        }
    }

    public function render_custom_content(){
        global $wp_embed;

        foreach ( $this->itemsorder as $key => $item ) {
            $urlsegments = explode('/', trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/') );
            
            $css = '';
            if( $item['remove_content'] == 'yes' ){
                if( 'dashboard' === $key && !is_wc_endpoint_url() ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > p,.woocommerce-MyAccount-content > p{display: none;}';
                }elseif( 'orders' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > table.woocommerce-orders-table,.woocommerce-MyAccount-content > table.woocommerce-orders-table,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}body.woocommerce-account .woocommerce-MyAccount-content .woocommerce-pagination{display:none;}';
                }elseif( 'downloads' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > .woocommerce-order-downloads,.woocommerce-MyAccount-content > .woocommerce-order-downloads,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}';
                }elseif( 'edit-address' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > p,.woocommerce-MyAccount-content > p,body.woocommerce-account .woocommerce-MyAccount-content > .woocommerce-Addresses,.woocommerce-MyAccount-content > .woocommerce-Addresses,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}';
                }elseif( 'edit-account' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > form.woocommerce-EditAccountForm,.woocommerce-MyAccount-content > form.woocommerce-EditAccountForm{display: none;}';
                }
                elseif( 'payment-methods' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > .button,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}';
                }
                echo '<style>'.$css.'</style>';
            }
            
            if( 'dashboard' === $key && !is_wc_endpoint_url() ){
                if( $item['content'] ){
                    if( 'elementor' === $item['content_source'] ){
                        echo '<div class="woolentor-dash-content">'.( function_exists('woolentor_build_page_content') ? woolentor_build_page_content( $item['content'] ) : '' ).'</div>';
                    }else{
                        echo '<div class="woolentor-dash-content">'.do_shortcode( $wp_embed->autoembed( $item['content'] ) ).'</div>';
                    }
                }
            }else{
                if( $item['content'] && ( $urlsegments[count($urlsegments)-1] == $key ) ){
                    if( 'elementor' === $item['content_source'] ){
                        echo '<div class="woolentor-dash-content">'.( function_exists('woolentor_build_page_content') ? woolentor_build_page_content( $item['content'] ) : '' ).'</div>';
                    }else{
                        echo '<div class="woolentor-dash-content">'.do_shortcode( $wp_embed->autoembed( $item['content'] ) ).'</div>';
                    }
                }
            }

        }


    }

    

}
add_action('after_switch_theme', function(){ update_option( 'woolentor_flush_rewrite_rules', 0 ); });
if( ! is_admin() ){
    WooLentor_MyAccount::init();
}
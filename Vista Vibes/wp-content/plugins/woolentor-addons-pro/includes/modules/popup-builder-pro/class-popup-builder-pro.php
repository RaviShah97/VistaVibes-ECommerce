<?php
namespace Woolentor\Modules\Popup_Builder_Pro;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Popup_Builder_Pro{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    function __construct(){
        $this->define_constants();
        $this->includes();

        // Remove pro badge from the fields.
        add_filter('wlpb_module_fields', array($this, 'module_fields_cb'), 10, 1);

        // Remove pro texts from the condition type options.
        add_filter('wlpb_condition_type_options', array($this, 'condition_type_options_cb'), 10, 1);
    }

    /**
     * Define the required constants.
     */
    private function define_constants() {
        define( 'Woolentor\Modules\Popup_Builder_Pro\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\Popup_Builder_Pro\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\Popup_Builder_Pro\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\Popup_Builder_Pro\MODULE_ASSETS', MODULE_URL . '/assets' );
    }

    public function module_fields_cb($fields){
        foreach( $fields['general_fields'] as $key => $field ){
            if( isset($field['wlpb_is_pro']) && $field['wlpb_is_pro'] ){
                $fields['general_fields'][$key]['class'] = 'wlpb-field';
            }
        }
        
        foreach( $fields['customization_fields'] as $key => $field ){
            if( isset($field['wlpb_is_pro']) && $field['wlpb_is_pro'] ){
                $fields['customization_fields'][$key]['class'] = 'wlpb-field';
            }
        }
        
        return $fields;
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    private function includes() {
        spl_autoload_register( array( $this, 'autoloader' ) );
    }

    /**
     * Autoloader.
     */
    private function autoloader( $class ) {
        if ( 0 === strpos( $class, 'Woolentor\Modules\Popup_Builder_Pro' ) ) {

            // Replace the namespace prefix with includes directory and change the _ to -.
            $file = str_replace( array('Woolentor\Modules\Popup_Builder_Pro', '_'), array('includes', '-'), $class );

            // Add class- prefix to the filename.
            $file_arr = explode('\\', $file);
            if( !empty($file_arr) ){
                $file = str_replace( end($file_arr), 'class-'. end($file_arr),  $file);
            }

            // Convert the filename to lowercase and replace the namespace separator with directory separator.
            $file = str_replace( array( '\\', ), array( DIRECTORY_SEPARATOR, ), strtolower($file) );
            $file = sprintf( '%1$s%2$s.php', trailingslashit( MODULE_PATH ), $file );
            
            $file = realpath( $file );

            // If the file exists, require it.
            if ( false !== $file && file_exists( $file ) ) {
                require $file;
            }
        }
    }

    public function condition_type_options_cb( $options ){

        $options['archives']    = __( 'Archives', 'woolentor' );
        $options['woocommerce'] = __( 'WooCommerce', 'woolentor' );

        return $options;
    }
}

Popup_Builder_Pro::get_instance();
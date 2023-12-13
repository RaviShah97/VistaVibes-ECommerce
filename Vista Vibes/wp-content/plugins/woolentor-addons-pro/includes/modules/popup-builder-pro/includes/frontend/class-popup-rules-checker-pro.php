<?php
namespace Woolentor\Modules\Popup_Builder_Pro\Frontend;
use Woolentor\Modules\Popup_Builder\Frontend\Popup_Rules_Checker;
use Woolentor\Modules\Popup_Builder_Pro\Frontend\Mobile_Detect;
use Woolentor\Modules\Popup_Builder\Admin\Manage_Metabox;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists('Woolentor\Modules\Popup_Builder\Frontend\Popup_Rules_Checker') ){
    class Popup_Rules_Checker_Pro extends Popup_Rules_Checker{

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
         * Check Archives Rules (except WooCommerce).
         * 
         * @param string $rule_sub_name
         * @param string $rule_sub_id
         * 
         * @return bool
         */
        public function check_archives_rule( $rule_sub_name, $rule_sub_id ){
            $return_value = false;
            $rule_sub_ids = explode(',', $rule_sub_id);
    
            if( !is_archive() ){
                return $return_value;
            }
    
            // Exclude WooCommerce archives since they are available in separated rule.
            if( function_exists('is_product_taxonomy') && is_product_taxonomy() ){
                return $return_value;
            }
    
            $current_archive_taxonomy   = get_queried_object()->taxonomy;
            $current_archive_post_type  = get_taxonomy($current_archive_taxonomy)->object_type[0];
            $current_term_id            = get_queried_object()->term_id;
    
            // All Archives.
            if( empty($rule_sub_name) ){
                $return_value = true;
            }
    
            // All Archives of a specific post type.
            $selected_post_type = substr($rule_sub_name, 0, -8);
    
            // Check if the post type is valid.
            if(  post_type_exists($selected_post_type) ){
                // If the current archive is of the $selected_post_type.
                if( $selected_post_type == $current_archive_post_type ){
                    $return_value = true;
                }
            } else {
    
                // Specific taxonomy, all term archives.
                if( $rule_sub_name && empty($rule_sub_id) ){
    
                    // if $current_archive_taxonomy is same as $rule_sub_name.
                    if( $current_archive_taxonomy == $rule_sub_name ){
                        $return_value = true;
                    }
    
                }
    
                // Specific taxonomy, specific term archives.
                if( $rule_sub_name && $rule_sub_id ){
    
                    // if $current_term_id is in $rule_sub_ids.
                    if( in_array($current_term_id, $rule_sub_ids) ){
                        $return_value = true;
                    }
    
                }
    
            }
    
            return $return_value;
        }
    
        /**
         * Check WooCommerce Rules.
         * 
         * @return bool
         */
        public function check_woocommerce_rule( $rule_sub_name, $rule_sub_id ){
            $return_value = false;
            $rule_sub_ids = explode(',', $rule_sub_id);
    
            // Make sure woocommerce is active.
            if( !function_exists('is_woocommerce') ){
                return $return_value;
            }
    
            // Entire Shop.
            if( empty($rule_sub_name) ){
                if( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ){
                    $return_value = true;
                }
            }
    
            // All Product taxonomy terms Archives (Tags,Categories).
            if( $rule_sub_name == 'product_archive' ){
                if( is_shop() || is_product_taxonomy() ){
                    $return_value = true;
                }
            }
    
            // All terms archive of product_cat taxonomy.
            if( $rule_sub_name == 'product_cat' && empty($rule_sub_id) ){
                if( is_product_category() ){
                    $return_value = true;
                }
            }
    
            // All terms archive of product_tag taxonomy.
            if( $rule_sub_name == 'product_tag' && empty($rule_sub_id) ){
                if( is_product_tag() ){
                    $return_value = true;
                }
            }
    
            // Specific terms archive of product_cat taxonomy.
            if( $rule_sub_name == 'product_cat' && !empty($rule_sub_id) ){
                // if is_product_category() and $current_term_id is in $rule_sub_ids.
                if( is_product_category() && in_array(get_queried_object()->term_id, $rule_sub_ids) ){
                    $return_value = true;
                }
            }
    
            // Specific terms archive of product_tag taxonomy.
            if( $rule_sub_name == 'product_tag' && !empty($rule_sub_id) ){
                // if is_product_tag() and $current_term_id is in $rule_sub_ids.
                if( is_product_tag() && in_array(get_queried_object()->term_id, $rule_sub_ids) ){
                    $return_value = true;
                }
            }
    
            // All Products.
            if( $rule_sub_name == 'product' && empty($rule_sub_id) ){
                if( is_product() ){
                    $return_value = true;
                }
            }
    
            // Specific Products.
            if( $rule_sub_name == 'product' && !empty($rule_sub_id) ){
                // if is_product() and $current_product_id is in $rule_sub_ids.
                if( is_product() && in_array( get_queried_object_id(), $rule_sub_ids) ){
                    $return_value = true;
                }
            }
    
            // Specific Products in a specific category.
            if( $rule_sub_name == 'in_product_cat' ){
                if( is_product() && $this->check_post_is_in_any_of_the_categories( 'product_cat', $rule_sub_id, get_queried_object_id() ) ){
                    $return_value = true;
                }
            }
    
            // Specific Products in a specific tag.
            if( $rule_sub_name == 'product_tag' && !empty($rule_sub_id) ){
                if( is_product() && $this->check_post_is_in_any_of_the_categories( 'product_tag', $rule_sub_id, get_queried_object_id() ) ){
                    $return_value = true;
                }
            }
    
            return $return_value;
        }

        /**
        * Requirements that have to be met for the popup to be shown.
        *
        * @param int $popup_id The popup post id.
        * 
        * @return bool
        */
        public function check_advanced_rules( $popup_id = 0 ){
            if( !$popup_id ){
                return false;
            }

            $popup_meta               = get_post_meta($popup_id, '_wlpb_popup_seetings', true);
            $advanced_rules_defaults  = Manage_Metabox::get_instance()->get_default_values('advanced_fields');
            $popup_meta_advaced_rules =  array();
            $current_url              = sanitize_url($_SERVER['REQUEST_URI']);

            $url_match_type           = '';
            $url_match_input          = '';
            $url_match_value          = '';

            $url_match_status         = null;
            $device_rule_status       = null;
            $browser_rule_status      = null;

            $return_value             = null;

            if (isset($popup_meta['advanced']) && !empty($popup_meta['advanced'])) {
                $popup_meta_advaced_rules = wp_parse_args($popup_meta['advanced'], $advanced_rules_defaults);
            } else {
                $popup_meta_advaced_rules = $advanced_rules_defaults;
            }

            if ($popup_meta_advaced_rules['url_match']) {
                $url_match_type   = $popup_meta_advaced_rules['url_match_type'];
                $url_match_input  = $popup_meta_advaced_rules['url_match_input'];
                $url_match_value  = $popup_meta_advaced_rules['url_match_value'];

                if ($url_match_type == 'parameter') {
                    $url_match_status = $this->check_url_query_parameter($current_url, $url_match_input, $url_match_value);
                }

                if ($url_match_type == 'query_string') {
                    $url_match_status = $this->check_url_query_string($url_match_value);
                }

                if ($url_match_type == 'exact_match') {
                    $url_match_status = $this->check_url_exact_match($current_url, $url_match_value);
                }

                if ($url_match_type == 'contains') {
                    $url_match_status = $this->check_url_contains($current_url, $url_match_value);
                }

                if ($url_match_type == 'starts_with') {
                    $url_match_status = $this->check_url_starts_with($current_url, $url_match_value);
                }

                if ($url_match_type == 'ends_with') {
                    $url_match_status = $this->check_url_ends_with($current_url, $url_match_value);
                }
            }


            if ($popup_meta_advaced_rules['show_on_devices'] && !empty($popup_meta_advaced_rules['devices'])) {
                $device_rule_status = $this->check_device_rule($popup_meta_advaced_rules['devices']);
            }

            if ($popup_meta_advaced_rules['show_on_browsers'] && !empty($popup_meta_advaced_rules['browsers'])) {
                $browser_rule_status = $this->check_browser_rule($popup_meta_advaced_rules['browsers']);
            }

            // Check url match status, device rule status and browser rule status with and operator. If any of them is false then return false.
            if ($url_match_status === false || $device_rule_status === false || $browser_rule_status === false) {
                $return_value = false;
            } else {
                $return_value = true;
            }

            return $return_value;
        }

        /**
         * Check if the url has specific query parameter.
         * 
         * @param string $query_string_name The query parameter names separated by comma (,) or pipe (|).
         * @param string $query_string_value The query parameter values separated by comma (,) or pipe (|).
         * 
         * @return bool
         */
        public function check_url_query_parameter( $url, $parameter_to_check, $parameter_value_to_check ){
            $return_value       = false;
            $found_query_string              = !empty($_GET[$parameter_to_check]) ? true : false;
            $current_page_query_string_value = !empty($_GET[$parameter_to_check]) ? sanitize_text_field($_GET[$parameter_to_check]) : '';

            // Only true when both $parameter_to_check and $parameter_value_to_check is provided and matched.
            if( $found_query_string && $parameter_value_to_check == $current_page_query_string_value ){
                $return_value = true;
            }

            // If both $query_string_name and $parameter_value_to_check is provided then check the query parameter name and value to the current url.
            return $return_value;
        }

        /**
         * Check if the url has specific query string.
         * 
         * @param string $string_to_match The query string to match. Example: if the URL is /test-page/?xx=hello&y=somthing the string_to_match will be xx=hello&y=somthing
         * 
         * @return bool
         */
        public function check_url_query_string( $string_to_match = '' ){
            $return_value              = false;
            $current_page_query_string = !empty($_SERVER['QUERY_STRING']) ? sanitize_text_field($_SERVER['QUERY_STRING']) : '';

            if ( $current_page_query_string === $string_to_match ) {
                $return_value = true;
            }

            return $return_value;
        }

        /**
         * Check if the url is exact match.
         * 
         * @param string $url The url to check. Example: /post-2/?x=hello
         * @param string $input_url The url to match. Example: /post-2/?x=hello
         * 
         * @return bool
         */
        public function check_url_exact_match( $url = '', $input_url = '' ){
            $return_value   = false;

            if ( $url === $input_url ) {
                $return_value = true;
            }

            return $return_value;
        }

        /**
         * Check if the url contains a string.
         * 
         * @param string $url The url to check. Example: /test-page/?xx=hello&y=somthing
         * @param string $string_to_check The string to check. Example: xx=hello
         * 
         * @return bool
         */
        public function check_url_contains( $url = '', $string_to_check = '' ){
            $return_value   = false;

            if ( strpos($url, $string_to_check) !== false ) {
                $return_value = true;
            }

            return $return_value;
        }

        /**
         * Check if the url starts with a string.
         * 
         * @param string $url The url to check. Example: /test-page/?xx=hello&y=somthing
         * @param string $string_to_check The string to check. Example: test-page == false, /test-page or /test-page/ == true
         * 
         * @return bool
         */
        public function check_url_starts_with( $url = '', $string_to_check = '' ){
            $return_value   = false;

            if ( strpos($url, $string_to_check) === 0 ) {
                $return_value = true;
            }

            return $return_value;
        }

        /**
         * Check if the url ends with a string.
         * 
         * @param string $url The url to check. 1. /test-page/?xx=hello&y=somthing 2. /contact-us/
         * @param string $string_to_check The string to check. 1. somthing == false, /somthing or /somthing/ == true 2. /contact-us == false, /contact-us/ or contact-us/ == true
         * 
         * @return bool
         */
        public function check_url_ends_with( $url = '', $string_to_check = '' ){
            $return_value   = false;

            if ( substr($url, -strlen($string_to_check)) === $string_to_check ) {
                $return_value = true;
            }

            return $return_value;
        }

        /**
         * Check if the allowed devices is matched with the current user device.
         * 
         * @param array $allowed_devices The allowed devices. Example: array('desktop', 'mobile', 'tablet')
         * 
         * @return bool
         */
        public function check_device_rule( $allowed_devices = array() ){
            $return_value = false;

            if( class_exists('Woolentor\Modules\Popup_Builder_Pro\Frontend\Mobile_Detect') ){
                $mobile_detector = new Mobile_Detect();

                if( $mobile_detector->isTablet() ){
                    $current_user_device = 'tablet';
                } elseif( $mobile_detector->isMobile() ){
                    $current_user_device = 'mobile';
                } else {
                    $current_user_device = 'desktop';
                }

                if( in_array($current_user_device, $allowed_devices) ){
                    $return_value = true;
                }
            }

            return $return_value;
        }

        /**
         * Check if the allowed browsers is matched with the current user browser.
         * 
         * @param array $allowed_browsers The allowed browsers. Example: array('chrome', 'firefox', 'safari')
         * 
         * @return bool
         */
        public function check_browser_rule( $allowed_browsers = array() ){
            $return_value = false;

            $visitor_browser = $this->get_visitor_browser();
            if ( in_array($visitor_browser, $allowed_browsers) ) {
                $return_value = true;
            }

            return $return_value;
        }

        /**
         * Get the current user browser.
         * 
         * @link 
         * @return string
         */
        public function get_visitor_browser(){
            $return_value = 'Unknown';

            $browser_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? htmlspecialchars($_SERVER['HTTP_USER_AGENT'])  : '';

            $browser_array = array(
                '/msie/i' => 'Internet Explorer',
                '/firefox/i' => 'Firefox',
                '/safari/i' => 'Safari',
                '/chrome/i' => 'Chrome',
                '/edge/i' => 'Edge',
                '/opera/i' => 'Opera',
                '/netscape/i' => 'Netscape',
                '/maxthon/i' => 'Maxthon',
                '/konqueror/i' => 'Konqueror',
                '/mobile/i' => 'Handheld Browser'
            );

            foreach ($browser_array as $regex => $value){
                if ( preg_match($regex, $browser_user_agent) ){

                    switch ($value) {
                        case 'Internet Explorer':
                            $return_value = 'ie';
                            break;

                        case 'Firefox':
                            $return_value = 'firefox';
                            break;

                        case 'Safari':
                            $return_value = 'safari';
                            break;

                        case 'Chrome':
                            $return_value = 'chrome';
                            break;

                        case 'Edge':
                            $return_value = 'edge';
                            break;

                        case 'Opera':
                            $return_value = 'opera';
                            break;

                        case 'Netscape':
                            $return_value = 'netscape';
                            break;

                        case 'Maxthon':
                            $return_value = 'maxthon';
                            break;

                        case 'Konqueror':
                            $return_value = 'konqueror';
                            break;

                        case 'Handheld Browser':
                            $return_value = 'mobile';
                            break;
                        
                        default:
                            $return_value = 'Unknown';
                            break;
                    }
                }
            }

            return $return_value;
        }
    }
}

<?php
/**
 * Admin.
 */

namespace WLPF;

/**
 * Class.
 */
class Admin {

	/**
     * Constructor.
     */
    public function __construct() {
        if ( WLPF_ENABLED ) {
            new Admin\Terms();
        }

        new Admin\Fields();
    }

}
<?php

// Create a helper function for easy SDK access.

if ( !function_exists( 'ssaa_fs' ) ) {
    function ssaa_fs()
    {
        global  $ssaa_fs ;
        if ( !isset( $ssaa_fs ) ) {
            $ssaa_fs = fs_dynamic_init( array(
                'id'             => '2881',
                'slug'           => 'seo-site-auditor-agency',
                'type'           => 'plugin',
                'public_key'     => 'pk_209e78a7644bfad10100913fb5eca',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'slug'       => 'wpsewcc_welcome_page',
                'first-path' => 'admin.php?page=wpsewcc_welcome_page',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        return $ssaa_fs;
    }
    
    // Init Freemius.
    ssaa_fs();
    // Signal that SDK was initiated.
    do_action( 'ssaa_fs_loaded' );
}

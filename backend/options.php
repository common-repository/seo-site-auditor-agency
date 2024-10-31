<?php

if ( !class_exists( 'WP_Site_Audit_Options' ) ) {
    class WP_Site_Audit_Options
    {
        private static  $instance = false ;
        var  $settings = null ;
        var  $sections = array() ;
        private function __construct()
        {
        }
        
        function init()
        {
            add_action( 'init', array( $this, 'late_init' ) );
        }
        
        function late_init()
        {
            $this->set_sections();
            $args = array(
                'sections'         => $this->sections,
                'opt_name'         => 'site_audit_settings',
                'display_name'     => __( 'Settings', "SDT" ),
                'page_permissions' => 'manage_options',
                'enable_wpmu_mode' => false,
                'sdk'              => SDT_Site_Audit::get_instance()->vg_plugin_sdk,
            );
            $this->settings = new VGFP_SDK_Settings_Page( $args );
            add_action( 'admin_menu', array( $this, 'register_menu_page' ), 99 );
        }
        
        function get_settings_page_url()
        {
            $out = admin_url( 'admin.php?page=site_audit_settings' );
            return $out;
        }
        
        function register_menu_page()
        {
            add_submenu_page(
                'wpsewcc_welcome_page',
                $this->settings->args['display_name'],
                $this->settings->args['display_name'],
                $this->settings->args['page_permissions'],
                $this->settings->args['opt_name'],
                array( $this->settings, 'render_settings_page' )
            );
        }
        
        public function set_sections()
        {
            $pages_query = new WP_Query( array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
            ) );
            $options = array(
                array(
                'id'    => 'sdt_logo',
                'type'  => 'image_select',
                'url'   => true,
                'title' => __( 'Logo', "SDT" ),
            ),
                array(
                'id'      => 'sdt_pdf_main_color',
                'type'    => 'color',
                'title'   => __( 'Main color', "SDT" ),
                'desc'    => __( 'Pick a color for the pdf report (default: #000).', "SDT" ),
                'default' => '#000000',
            ),
                array(
                'id'      => 'sdt_mail_bcc',
                'type'    => 'text',
                'title'   => __( 'Email: Send results copy (bcc) to these addresses', "SDT" ),
                'desc'    => __( 'Enter the admin email. Separate each email with a comma.', "SDT" ),
                'default' => get_option( 'admin_email' ),
            ),
                array(
                'id'      => 'sdt_mail_subject',
                'type'    => 'text',
                'title'   => __( 'Email subject', "SDT" ),
                'desc'    => __( 'Write the message that will appear in the mail', "SDT" ),
                'default' => __( 'Here\'s your report', "SDT" ),
            ),
                array(
                'id'      => 'sdt_mail_message',
                'type'    => 'editor',
                'title'   => __( 'Email message', "SDT" ),
                'desc'    => __( 'Write the message that will appear in the mail. Use {result_url} as the URL to open the report.', "SDT" ),
                'default' => '<p>We completed the site audit on your site, here are the results.</p><a href="{result_url}">View results</a>',
            ),
                array(
                'id'      => 'sdt_googleapi_key',
                'type'    => 'text',
                'title'   => __( 'Google PageSpeed Insights API key', "SDT" ),
                'default' => '',
                'desc'    => __( 'Get your API key here: <a href="https://console.cloud.google.com" target="_blank">Get key</a> - <a href="https://greenjaymedia.com/wp-site-auditor/how-to-get-api-keys/" target="_blank">Tutorial</a>', "SDT" ),
            ),
                array(
                'id'      => 'sdt_mozapi_accessid',
                'type'    => 'text',
                'title'   => __( 'Moz API: Access ID', "SDT" ),
                'default' => '',
                'desc'    => __( 'Get your access id and secret key here: <a href="https://moz.com/products/api/keys" target="_blank">Get key</a> - <a href="https://greenjaymedia.com/wp-site-auditor/how-to-get-api-keys/" target="_blank">Tutorial</a>', "SDT" ),
            ),
                array(
                'id'      => 'sdt_mozapi_secretkey',
                'type'    => 'text',
                'title'   => __( 'Moz API: Secret ID Key', "SDT" ),
                'default' => '',
            ),
                array(
                'id'      => 'sdt_results_page',
                'type'    => 'select',
                'options' => wp_list_pluck( $pages_query->posts, 'post_title', 'ID' ),
                'title'   => __( 'Results page', "SDT" ),
                'desc'    => __( 'Set the page where the shortcode results is located. This page must be published.', "SDT" ),
            )
            );
            $this->sections[] = array(
                'icon'   => 'el-icon-cogs',
                'title'  => __( 'General', "SDT" ),
                'fields' => $options,
            );
        }
        
        /**
         * Creates or returns an instance of this class.
         */
        static function get_instance()
        {
            
            if ( null == WP_Site_Audit_Options::$instance ) {
                WP_Site_Audit_Options::$instance = new WP_Site_Audit_Options();
                WP_Site_Audit_Options::$instance->init();
            }
            
            return WP_Site_Audit_Options::$instance;
        }
        
        function __set( $name, $value )
        {
            $this->{$name} = $value;
        }
        
        function __get( $name )
        {
            return $this->{$name};
        }
    
    }
}
if ( !function_exists( 'WP_Site_Audit_Options_Obj' ) ) {
    function WP_Site_Audit_Options_Obj()
    {
        return WP_Site_Audit_Options::get_instance();
    }

}
WP_Site_Audit_Options_Obj();
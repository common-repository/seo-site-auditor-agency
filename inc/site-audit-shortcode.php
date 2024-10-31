<?php

if ( !class_exists( 'SDT_Shortcode' ) ) {
    class SDT_Shortcode
    {
        private  $sdt_nonce_key = 'sdt_nonce' ;
        public function __construct()
        {
            $this->init();
        }
        
        public function form( $args = array() )
        {
            global  $site_audit_settings ;
            $results_page_id = $site_audit_settings['sdt_results_page'];
            ob_start();
            if ( current_user_can( 'manage_options' ) && (empty($site_audit_settings['sdt_googleapi_key']) || empty($site_audit_settings['sdt_mozapi_accessid']) || empty($site_audit_settings['sdt_mozapi_secretkey']) || empty($results_page_id) || get_post_status( $results_page_id ) !== 'publish') ) {
                echo  '<p style="color: red; background: white;">' . sprintf( __( 'Attention administrator: You need to complete the setup on the <a href="%s" target="_blank">settings page</a>. The plugin wont work properly without those settings.', "SDT" ), esc_url( admin_url( 'admin.php?page=site_audit_settings' ) ) ) . '</p>' ;
            }
            wp_enqueue_script( 'site_audit_js', SDT_URL . 'assets/js/site-audit-shortcode-js.js', array( 'jquery' ) );
            wp_enqueue_script( 'html2canvas', SDT_URL . 'assets/vendor/html2canvas/html2canvas.min.js' );
            wp_enqueue_style( 'wpsa-audit-form', SDT_URL . 'assets/css/audit-form.css' );
            wp_localize_script( 'site_audit_js', 'sdt', array(
                'base_plugin_url'                   => SDT_URL,
                'get_pdf_ajax'                      => 'get_pdf_html',
                'save_pdf_ajax'                     => 'save_pdf',
                'ajax_url'                          => admin_url( 'admin-ajax.php' ),
                'get_check_section'                 => 'wpsa_get_check',
                'get_header_iframe'                 => 'wpsa_get_header_iframe',
                'results_sent_to_email_text'        => __( 'We sent you an email with the results', "SDT" ),
                'is_premium'                        => ssaa_fs()->can_use_premium_code__premium_only(),
                'sdt_nonce'                         => wp_create_nonce( $this->sdt_nonce_key ),
                'dont_show_report_after_submission' => !empty($site_audit_settings['dont_show_report_after_submission']),
            ) );
            sdt_shortcode_render_form( $args );
            return ob_get_clean();
        }
        
        public function get_pdf_html()
        {
            check_ajax_referer( $this->sdt_nonce_key, 'sdt_nonce' );
            $args = array(
                'url'     => sanitize_text_field( $_POST['sdt_website'] ),
                'keyword' => sanitize_text_field( $_POST['sdt_keyword'] ),
            );
            sdt_render_pdf_html( $args );
            die;
        }
        
        public function get_header_iframe()
        {
            check_ajax_referer( $this->sdt_nonce_key, 'sdt_nonce' );
            sdt_render_header();
            die;
        }
        
        public function save_pdf()
        {
            // get url from form
            $url = esc_url( $_POST['sdt_website'] );
            $keyword = sanitize_text_field( $_POST['sdt_keyword'] );
            $name = ( isset( $_POST['sdt_name'] ) ? sanitize_text_field( $_POST['sdt_name'] ) : '' );
            $email = sanitize_email( $_POST['sdt_email'] );
            if ( empty($url) || empty($keyword) || empty($email) ) {
                wp_send_json_success( array(
                    'message' => __( '', "SDT" ),
                ) );
            }
            $encryptSimpleUrl = md5( $url );
            $today = current_time( 'Y-m-d-H-i-s' );
            wp_mkdir_p( SDT_RESULTS_PATH );
            $fileName = SDT_RESULTS_PATH . '/' . $encryptSimpleUrl . '_' . $today;
            // We won't delete the previous results to be able to view them on the lead details page in wp-admin
            
            if ( strpos( $_POST['sdt_pdf_image'], '<img' ) !== false ) {
                $img = wp_kses_post( $_POST['sdt_pdf_image'] );
                $img = str_replace( 'src="image/jpeg', 'src="data:image/jpeg', $img );
                $fileName .= '.txt';
            } else {
                $img = base64_decode( $_POST['sdt_pdf_image'] );
                $fileName .= '.jpg';
            }
            
            // create file image
            file_put_contents( $fileName, $img );
            $email_args = array(
                'user_email' => $email,
                'site_url'   => $url,
                'fileName'   => $fileName,
            );
            $this->send_email_with_results( $email_args );
            wp_send_json_success( array(
                'message' => __( 'img created', "SDT" ),
            ) );
            die;
        }
        
        public function get_check()
        {
            global  $html, $errorMessages, $countSuccess ;
            $url = esc_url( $_POST['sdt_website'] );
            $hook = sanitize_text_field( $_POST['hook'] );
            $keyword = sanitize_text_field( $_POST['sdt_keyword'] );
            $name = ( isset( $_POST['sdt_name'] ) ? sanitize_text_field( $_POST['sdt_name'] ) : '' );
            $email = sanitize_email( $_POST['sdt_email'] );
            if ( empty($url) || empty($keyword) || empty($email) ) {
                wp_send_json_success( array(
                    'fields_are_valid' => false,
                ) );
            }
            if ( !isset( $_POST['error_messages'] ) ) {
                $_POST['error_messages'] = '';
            }
            if ( !isset( $_POST['count_success'] ) ) {
                $_POST['count_success'] = '';
            }
            $error_messages = ( is_array( $_POST['error_messages'] ) ? array_map( 'sanitize_text_field', $_POST['error_messages'] ) : sanitize_text_field( $_POST['error_messages'] ) );
            $count_success = ( is_array( $_POST['count_success'] ) ? array_map( 'sanitize_text_field', $_POST['count_success'] ) : sanitize_text_field( $_POST['count_success'] ) );
            $body = '';
            if ( empty($error_messages) ) {
                $error_messages = array();
            }
            if ( empty($count_success) ) {
                $count_success = array();
            }
            $transient_key = 'vgsa_html_' . md5( $url );
            $body = get_transient( $transient_key );
            
            if ( !$body ) {
                $response = wp_remote_get( $url, array(
                    'timeout'    => 5,
                    'user-agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                ) );
                if ( is_array( $response ) ) {
                    $body = $response['body'];
                }
                set_transient( $transient_key, $body, DAY_IN_SECONDS );
            }
            
            
            if ( empty($body) ) {
                $html = $html->file_get_html( $url );
            } else {
                $html = $html->str_get_html( $body );
            }
            
            // extract($response, EXTR_OVERWRITE);
            check_ajax_referer( $this->sdt_nonce_key, 'sdt_nonce' );
            $settings = get_option( 'site_audit_settings', __( 'This option not found', "SDT" ) );
            $dataForm = array(
                'url'     => $url,
                'keyword' => $keyword,
            );
            $args = array(
                'url'             => $url,
                'keyword'         => $keyword,
                'dataGoogleSpeed' => std_google_page_speed_insights_api( $dataForm ),
                'mozAPI_data'     => std_moz_API( $dataForm ),
                'setting'         => $settings,
                'html'            => $html,
                'error_messages'  => $error_messages,
                'count_success'   => $count_success,
                'domainData'      => sdt_domain_data( $dataForm ),
            );
            ob_start();
            do_action( $hook, $args );
            $check_html_result = ob_get_clean();
            wp_send_json_success( array(
                'check_html_result' => $check_html_result,
                'error_messages'    => array_merge( $error_messages, $errorMessages ),
                'count_success'     => array_merge( $count_success, $countSuccess ),
                'fields_are_valid'  => true,
            ) );
            // die();
        }
        
        public function send_email_with_results( $args )
        {
            extract( $args, EXTR_OVERWRITE );
            global  $site_audit_settings ;
            $results_page_id = $site_audit_settings['sdt_results_page'];
            $results_page_link = get_permalink( $results_page_id );
            $final_results_link = add_query_arg( 'su_report', str_replace( array( '.jpg', '.txt' ), '', basename( $fileName ) ), $results_page_link );
            if ( empty($email_template_string) ) {
                $email_template_string = file_get_contents( SDT_PATH . 'views/frontend/email-template.html' );
            }
            $site_audit_settings['sdt_mail_message'] .= '<br/>Powered by <a href="https://greenjaymedia.com/wp-site-auditor/" target="_blank">Green Jay Media</a>';
            
            if ( is_array( $site_audit_settings['sdt_logo'] ) ) {
                $logo_url = $site_audit_settings['sdt_logo']['url'];
            } else {
                $logo_url = wp_get_attachment_url( $site_audit_settings['sdt_logo'] );
            }
            
            $email_template_string = str_replace( array( '{sdt_logo}', '{result_url}', '{sdt_message}' ), array( $logo_url, $final_results_link, str_replace( '{result_url}', $final_results_link, $site_audit_settings['sdt_mail_message'] ) ), $email_template_string );
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
            $bcc_addresses = array_map( 'trim', explode( ',', $site_audit_settings['sdt_mail_bcc'] ) );
            if ( !empty($bcc_addresses) ) {
                foreach ( $bcc_addresses as $bcc_address ) {
                    $headers[] = 'BCC: ' . $bcc_address;
                }
            }
            wp_mail(
                $user_email,
                $site_audit_settings['sdt_mail_subject'],
                $email_template_string,
                $headers
            );
            delete_transient( 'vgsa_google_' . md5( $site_url ) );
            delete_transient( 'vgsa_html_' . md5( $site_url ) );
            // We dont delete the moz transient because it auto expires in 1
            // day, the moz data doesnt change frequently
        }
        
        public function init()
        {
            add_shortcode( 'site_audit', array( $this, 'form' ) );
            add_action( 'wp_ajax_nopriv_get_pdf_html', array( $this, 'get_pdf_html' ) );
            add_action( 'wp_ajax_get_pdf_html', array( $this, 'get_pdf_html' ) );
            add_action( 'wp_ajax_nopriv_wpsa_get_check', array( $this, 'get_check' ) );
            add_action( 'wp_ajax_wpsa_get_check', array( $this, 'get_check' ) );
            add_action( 'wp_ajax_nopriv_wpsa_get_header_iframe', array( $this, 'get_header_iframe' ) );
            add_action( 'wp_ajax_wpsa_get_header_iframe', array( $this, 'get_header_iframe' ) );
            add_action( 'wp_ajax_nopriv_save_pdf', array( $this, 'save_pdf' ) );
            add_action( 'wp_ajax_save_pdf', array( $this, 'save_pdf' ) );
        }
    
    }
    $sdt_shortcode = new SDT_Shortcode();
}

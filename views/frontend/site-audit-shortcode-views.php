<?php

if ( !function_exists( "sdt_shortcode_render_form" ) ) {
    function sdt_shortcode_render_form( $args = array() )
    {
        $options = get_option( 'site_audit_settings', array() );
        if ( !isset( $args['layout'] ) || empty($args['layout']) ) {
            $args['layout'] = 'vertical';
        }
        ?> 
		<form id = "audit-form" class="<?php 
        echo  ( !empty($args['layout']) ? sanitize_html_class( $args['layout'] ) : 'vertical' ) ;
        ?>">                      
			<div class="audit-form-field-wrapper">
				<label for="sdt_website">
					<?php 
        _e( "URL", "SDT" );
        ?> 
				</label> 

				<input id = "sdt_website" name ="sdt_website" type = "text" required pattern="https?://.+">
			</div>
			<div class="audit-form-field-wrapper">
				<label for="sdt_keyword">
					<?php 
        _e( "Keyword", "SDT" );
        ?>  
				</label> 

				<input id = "sdt_keyword" name ="sdt_keyword" type = "text" required>
			</div>
			<!--			<div class="audit-form-field-wrapper">
							<label for="sdt_name">
			<?php 
        _e( "Name", "SDT" );
        ?> 
							</label> 
			
							<input id = "sdt_name" name ="sdt_name" type = "text" required>
						</div>-->
			<div class="audit-form-field-wrapper">
				<label for="sdt_email">
					<?php 
        _e( "Email", "SDT" );
        ?> 
				</label> 

				<input id = "sdt_email" name ="sdt_email" type = "email" required>
			</div>

			<input id = "sdt_pdf_image" type = "hidden">
			<button type="submit"><?php 
        _e( "Submit", "SDT" );
        ?></button>

			<?php 
        ?>
				<p class="audit-powered-by">Powered by <img src="<?php 
        echo  SDT_Site_Audit::get_instance()->args['logo'] ;
        ?>"></p>
			<?php 
        ?>
		</form>       
		<iframe id = "sdt-pdf-iframe"></iframe>


		<?php 
        // Don't show the results on the website, only through the email
        $dont_display_results_after_submission = !empty($options['dont_show_report_after_submission']) || !ssaa_fs()->can_use_premium_code__premium_only();
        if ( $dont_display_results_after_submission ) {
            ?>
			<style>			
				#sdt-pdf-iframe {
					position: fixed;
					top: 100%;	
				}
			</style>
		<?php 
        }
        ?>

		<?php 
        
        if ( current_user_can( 'manage_options' ) && (empty($options) || empty($options['sdt_googleapi_key']) || empty($options['sdt_mozapi_secretkey']) || empty($options['sdt_mozapi_accessid'])) ) {
            ?>
			<p><?php 
            _e( 'Warning: Please add your API keys in the settings page. The form will not work without them.', "SDT" );
            ?></p>
			<?php 
        }
    
    }

}
if ( !function_exists( "sdt_render_header" ) ) {
    function sdt_render_header()
    {
        ?>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="<?php 
        echo  SDT_URL . "assets/css/audit-results.css" ;
        ?>">
		<title>Results</title>
		<?php 
    }

}
if ( !function_exists( "sdt_render_pdf_html" ) ) {
    function sdt_render_pdf_html( $args )
    {
        $GLOBALS['errorMessages'] = array();
        $GLOBALS['countSuccess'] = array();
        $dataGoogleSpeed = std_google_page_speed_insights_api( $args );
        //count issues and good signal
        $dataMozApi = std_moz_API( $args );
        $domainData = sdt_domain_data( $args );
        global  $errorMessages, $countSuccess ;
        $args['dataGoogleSpeed'] = $dataGoogleSpeed;
        $args['mozAPI_data'] = $dataMozApi;
        $args['domainData'] = $domainData;
        //get logo
        $settings = get_option( 'site_audit_settings', __( 'This option not found', "SDT" ) );
        global  $site_audit_settings ;
        ?>
		<body>
			<header>
				<div class="header-container">
					<div class="logo">
						<?php 
        sdt_get_logo( $settings );
        ?>
					</div>

					<?php 
        ?>
				</div> 
			</header>

			<section>
				<article class="evaluation">
					<div class="url-site">
						<h3><?php 
        _e( "Landing Page Audit", "SDT" );
        ?></h3>
						<i><?php 
        echo  $args['url'] ;
        ?></i>
					</div>
					<div class="keyword">
						<h3><?php 
        _e( 'Keyword', "SDT" );
        ?></h3>
						<i><?php 
        echo  ucwords( $args['keyword'] ) ;
        ?></i>
					</div>
					<div class="circle light-green">
						<h2 class="txt-white score"></h2>
					</div>
				</article>
				<br>
				<br>

				<article class="info-details">
					<div class="circles-info txt-white">
						<div class="circle light-blue">
							<h2 id="corrects"></h2>
							<span><?php 
        _e( "Good Signals", "SDT" );
        ?></span>
						</div>
						<div class="circle light-red">
							<h2 id="issuesresutl"></h2>
							<span><?php 
        _e( "Issues Found", "SDT" );
        ?></span>
						</div>
						<div class="circle light-grey">
							<h2 class="score"></h2>
							<span><?php 
        _e( "Page Grade", "SDT" );
        ?></span>
						</div>
					</div>
					<div id="sdt_screenShot"></div>
				</article>
			</section>
			<br>
			<br>
			<br>                

			<?php 
        echo  "sdt_checks_go_here" ;
        ?>

			<div class="footer">
				<?php 
        ?>

				<?php 
        ?>
					<p class="audit-powered-by">Powered by <img src="<?php 
        echo  SDT_Site_Audit::get_instance()->args['logo'] ;
        ?>"></p>
					<?php 
        ?>
			</div>

		</body>
		<?php 
    }

}
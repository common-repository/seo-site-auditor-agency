<?php

global  $SDT_plugin ;
?>
<p><?php 
_e( 'Thank you for installing our plugin. <a href="https://greenjaymedia.com/contact-us/" target="_blank">Contact us</a> if you need help with the setup or have any questions.', "SDT" );
?></p>

<?php 
$steps = array();
$steps['open_settings_page'] = '<p>' . sprintf( __( 'Check the plugin settings. <a href="%s" target="_blank" class="button">Open settings page</a>', "SDT" ), esc_url( admin_url( 'admin.php?page=site_audit_settings' ) ) ) . '</p>';
$steps['create_page'] = '<p>' . __( 'Use these shortcodes: <br/>[site_audit layout="horizontal"] to display the site audit form with horizontal layout<br/>[site_audit layout="vertical"] to display the site audit form with vertical layout<br/>[site_audit_result] to display the site audit results. The results page must be a separate page, not on same page as form.', "SDT" ) . '</p>';
$steps['get_api_keys'] = '<p>' . sprintf( __( 'Get API keys: <br/>To show audit results, you need api keys from Moz and Google.  Add your keys to the <a href="%s" target="_blank">settings page</a>.  <a href="https://greenjaymedia.com/wp-site-auditor/how-to-get-api-keys/" target="_blank">Find out how to get your api keys here</a>', "SDT" ), esc_url( admin_url( 'admin.php?page=site_audit_settings' ) ) ) . '</p>';
$steps['change_designs'] = '<p>' . __( '<a href="https://greenjaymedia.com/wp-site-auditor/change-form-design-wp-site-auditor/" target="_blank">Find out how to change form designs</a>', "SDT" ) . '</p>';
$steps['emails_not_sending'] = '<p>' . __( '<a href="https://greenjaymedia.com/wp-site-auditor/why-emails-are-not-sending/" target="_blank">Why are emails not sending?</a>', "SDT" ) . '</p>';
$limits = '<p>' . sprintf( __( 'You are using the Free plugin. Available features:<br>You can show the site audit form<br>When the user fills the form, we send the site audit results to his email address<br>We show our "powered by" logo (no link) below the site audit form and below the email containing the results.', "SDT" ) ) . '</p>';
$limits .= sprintf(
    __( '<h3>Go Premium</h3><p>White Label Reports<br>You can save all the site audit submissions and contact the users later<br>No powered-by logo<br/>You can generate leads for your business<br/>You can view the submissions, filter by status, and contact each user<br/>You can export the submissions to a CSV<br/>You can import the leads into your email list/newsletter<br/>Your users can view the site audit results on your website after submitting the form<br/>And more.</p><a href="%s" class="button button-primary">%s</a> - <a href="%s" class="button" target="_blank">Need help? Contact us</a></p><p>Try the plugin without worries.</p>', "SDT" ),
    $SDT_plugin->args['buy_link'],
    $SDT_plugin->args['buy_link_text'],
    ssaa_fs()->contact_url()
);
$steps['allowed_urls'] = $limits;
$steps = apply_filters( 'vg_site_audit/welcome_steps', $steps );

if ( !empty($steps) ) {
    echo  '<ol class="steps">' ;
    foreach ( $steps as $key => $step_content ) {
        ?>
		<li><?php 
        echo  $step_content ;
        ?></li>		
		<?php 
    }
    echo  '</ol>' ;
}

?>
<style>
	img.vg-logo {
		width: 220px;
	}
</style>
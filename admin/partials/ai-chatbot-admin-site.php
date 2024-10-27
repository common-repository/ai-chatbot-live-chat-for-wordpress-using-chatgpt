<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$ai_chatbot_siteurl = site_url($path = '', $scheme = null);
$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo
$ai_chatbot_data     = get_option( 'ai_chatbot_adminsettings' );
$ai_chatbot_data     = json_decode( $ai_chatbot_data );
$ai_chatbot_data = sanitize_option(  "ai_chatbot_adminsettings",$ai_chatbot_data );
if($ai_chatbot_data != ""){
    $ai_chatbot_username = $ai_chatbot_data->ai_chatbot_username;
    $ai_chatbot_password = $ai_chatbot_data->ai_chatbot_password;
    $ai_chatbot_emailid  = $ai_chatbot_data->ai_chatbot_email;
    $ai_chatbot_default_siteurl = $ai_chatbot_data->ai_chatbot_default_siteurl;
    $ai_chatbot_cron_time = $ai_chatbot_data->ai_chatbot_cron_time;
    $ai_chatbot_accountid = $ai_chatbot_data->ai_chatbot_accountid;
    $ai_chatbot_websiteid = $ai_chatbot_data->ai_chatbot_websiteid;
} else {
    $ai_chatbot_username = "";
    $ai_chatbot_password = "";
    $ai_chatbot_emailid  = "";
    $ai_chatbot_default_siteurl = "";
    $ai_chatbot_cron_time = "";
    $ai_chatbot_accountid="";
    $ai_chatbot_websiteid="";
}
if ( isset( $_POST['ai_chatbot_siteurl_button'] ) ) {
    $ai_chatbot_flag="1";
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_siteurl_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_siteurl_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_siteurl_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_user_siteurl' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
    }
    $ai_chatbot_siteurl             = isset( $_POST['ai_chatbot_url'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_url'] ) ) : '';
    if ( empty( $ai_chatbot_siteurl ) ) {
        $ai_chatbot_flag           = 0;
        $ai_chatbot_error_mobileno = '';
        $ai_chatbot_error_mobileno .= '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error_mobileno .= '<p>' . esc_html( 'Please Enter Production URL.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
        $ai_chatbot_error_mobileno .= '</div>';
        echo wp_kses_post( $ai_chatbot_error_mobileno );
    }
    if(!filter_var($ai_chatbot_siteurl, FILTER_VALIDATE_URL)){
        $ai_chatbot_flag           = 0;
        $ai_chatbot_error_mobileno = '';
        $ai_chatbot_error_mobileno .= '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error_mobileno .= '<p>' . esc_html( 'The provided URL is incorrect. Please ensure the URL is entered correctly and try again.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
        $ai_chatbot_error_mobileno .= '</div>';
        echo wp_kses_post( $ai_chatbot_error_mobileno );
    }
    if((strpos($ai_chatbot_siteurl, 'localhost'))!=""){
        $ai_chatbot_flag           = 0;
        $ai_chatbot_error_mobileno = '';
        $ai_chatbot_error_mobileno .= '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error_mobileno .= '<p>' . esc_html( 'The URL provided contains "localhost". Please enter a valid URL that does not reference "localhost".', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
        $ai_chatbot_error_mobileno .= '</div>';
        echo wp_kses_post( $ai_chatbot_error_mobileno );
    }
    if($ai_chatbot_flag==="1"){
        $ai_chatbot_update_option_arr = array(
            'ai_chatbot_email' => $ai_chatbot_emailid,
            'ai_chatbot_username' => $ai_chatbot_username,
            'ai_chatbot_password' => $ai_chatbot_password,
            'ai_chatbot_default_siteurl' => $ai_chatbot_siteurl,
            'ai_chatbot_cron_time'       => $ai_chatbot_cron_time,
            'ai_chatbot_accountid'       => $ai_chatbot_accountid,
            'ai_chatbot_websiteid'       => $ai_chatbot_websiteid,
        );
        $ai_chatbot_result            = update_option( 'ai_chatbot_adminsettings', wp_json_encode( $ai_chatbot_update_option_arr ) );
        $ai_chatbot_result1 = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_user_websiteid( "$ai_chatbot_siteurl" );

        if($ai_chatbot_result1 === false){
            $ai_chatbot_flag           = 0;
            $ai_chatbot_error_mobileno = '';
            $ai_chatbot_error_mobileno .= '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error_mobileno .= '<p>' . esc_html( 'Oops! The URL provided appears to be incorrect or contains an error. Please double-check the URL and try again.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
            $ai_chatbot_error_mobileno .= '</div>';
            echo wp_kses_post( $ai_chatbot_error_mobileno );
        }else if($ai_chatbot_result1 === true){
            $ai_chatbot_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
            $url = add_query_arg( 'page', $ai_chatbot_page, admin_url( 'admin.php' ) );
            echo '<script>window.location.replace("' . esc_url( $url ) . '");</script>';
        }
        else{
            $ai_chatbot_flag = 0;
            $ai_chatbot_error_mobileno = '';
            $ai_chatbot_error_mobileno .= '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error_mobileno .= '<p>' . $ai_chatbot_result1 . '</p>';
            $ai_chatbot_error_mobileno .= '</div>';
            echo wp_kses_post($ai_chatbot_error_mobileno);
        }
    }else{
        $ai_chatbot_flag           = 0;
        $ai_chatbot_error_mobileno = '';
        $ai_chatbot_error_mobileno .= '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error_mobileno .= '<p>' . esc_html( 'Oops! The URL provided appears to be incorrect or contains an error. Please double-check the URL and try again.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
        $ai_chatbot_error_mobileno .= '</div>';
        echo wp_kses_post( $ai_chatbot_error_mobileno );
    }
}
if (isset( $_POST['ai_chatbot_restart_btn'] )  ) {
    // Verify the nonce for the first button
if ( ! isset( $_POST['ai_chatbot_cancel_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ai_chatbot_cancel_nonce'] ) ), 'ai_chatbot_cancel_action' ) ) {
        die( 'Invalid nonce' );
    }
    delete_option('ai_chatbot_adminsettings');
    $ai_chatbot_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
    $url = add_query_arg( 'page', $ai_chatbot_page, admin_url( 'admin.php' ) );
    echo '<script>window.location.replace("' . esc_url( $url ) . '");</script>';

}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-2">
            <img src="<?php echo esc_url($ai_chatbot_logo,'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?>" class="ai_chatbot_imgclass">
        </div>
    </div>
    <div class="row text-center justify-content-center ai_chatbot_scren1_body">
        <form method="post" name="ai_chatbot_site" action="">
            <div class="row mb-3">
                <div class="col-12">
                    <label class="ai_chatbot-lbl mt-3" id="ai_chatbot_siteurl"><?php esc_html_e('Enter your website link for chatbot', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                </div>
                <div class="d-flex flex-nowrap flex-row w-100 m-auto align-items-center">
                    <input type="url" name="ai_chatbot_url" id="ai_chatbot_url" class="ai_chatbot_text_input w-100" value="<?php
                    printf(
                        esc_html__( ' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                        esc_html($ai_chatbot_siteurl)
                    );
                    ?>" required>
                </div>
            </div>
            <label id="ai_chatbot_error_url" class="ai_chatbot_error" ><?php esc_html_e( 'Please enter Valid URL.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>

            <?php wp_nonce_field('ai_chatbot_user_siteurl', 'ai_chatbot_user_siteurl_nonce'); ?>
            <div class="row mb-3">
                <div class="col-md-12 text-center ai_chatbot_submit_div ">
                    <button type="submit" class="btn ai_chatbot_btn-theme_border" id="ai_chatbot_siteurl_button" name="ai_chatbot_siteurl_button"><?php  esc_html_e('Submit', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></button>
                </div>
            </div>
        </form>
    </div>
    <form method="post" name="ai_chatbot_form3" action="" class=" mt-3">
        <div class="row justify-content-center">
            <?php
            $ai_chatbot_nonce = wp_create_nonce( 'ai_chatbot_cancel_action' );
            wp_nonce_field( 'ai_chatbot_cancel_action', 'ai_chatbot_cancel_nonce' );
            ?>
            <button type="submit" name="ai_chatbot_restart_btn" class="ai_chatbot_resend_btn" ><?php esc_html_e(  'Restart the onboarding process again', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></button>
        </div>
    </form>
</div>
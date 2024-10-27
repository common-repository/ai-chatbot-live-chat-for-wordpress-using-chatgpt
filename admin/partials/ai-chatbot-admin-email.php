<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$ai_chatbot_data  = get_option( 'ai_chatbot_adminsettings' );
$ai_chatbot_data  = json_decode( $ai_chatbot_data );
$ai_chatbot_data= sanitize_option(  "ai_chatbot_adminsettings",$ai_chatbot_data);

if ($ai_chatbot_data != "") {
    $ai_chatbot_email = $ai_chatbot_data->ai_chatbot_email;
} else {
    $ai_chatbot_email = "";
}
// Set email for admin on submit
if ( isset( $_POST['ai_chatbot_getbutton'] ) ) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_email_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_email_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_email_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_user_email' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }
    $ai_chatbot_email             = isset( $_POST['ai_chatbot_email'] ) ? sanitize_email( wp_unslash( $_POST['ai_chatbot_email'] ) ) : '';
    $ai_chatbot_update_option_arr = array(
        'ai_chatbot_email' => $ai_chatbot_email,
        'ai_chatbot_username' => "",
        'ai_chatbot_password' => "",
        'ai_chatbot_default_siteurl' => "",
        'ai_chatbot_cron_time'       => "",
        'ai_chatbot_accountid'       => "",
        'ai_chatbot_websiteid'       => "",
    );
    $ai_chatbot_result            = update_option( 'ai_chatbot_adminsettings', wp_json_encode( $ai_chatbot_update_option_arr ) );
    try {
        $ai_chatbot_result1 = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_user_credentials( "$ai_chatbot_email" );

        if($ai_chatbot_result1 == "false"){
            throw new Exception("Something went wrong!");
        }
    } catch(Exception $exception){
        printf(
            esc_html__( 'Exception message: %s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'),
            esc_html( $exception->getMessage() )
        );
    }
}
// Set credential of user on submit
if (array_key_exists('ai_chatbot_submitbutton', $_POST)) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_credentials_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_credentials_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_credentials_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_user_credentials' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }
    if ( ! empty( $_POST ) ) {

        $ai_chatbot_otp = isset( $_POST['ai_chatbot_otp'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_otp'] ) ) : '';
        $ai_chatbot_flag = 1;
        if ( $ai_chatbot_otp == "" ) {
            $ai_chatbot_flag  = 0;
            $ai_chatbot_error = '';
            $ai_chatbot_error .= '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error .= '<p>' . esc_html( 'Please enter OTP.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
            $ai_chatbot_error .= '</div>';
            echo wp_kses_post( $ai_chatbot_error );
        }
        if ( $ai_chatbot_flag == 1 ) {
            $ai_chatbot_otp_response = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_user_plan( $ai_chatbot_otp );
            $ai_chatbot_create_account= Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_accountid($ai_chatbot_otp);


            if ( $ai_chatbot_create_account == "true" ) {
                $ai_chatbot_update_option_arr = array(
                    'ai_chatbot_otp' => $ai_chatbot_otp,
                );

                $ai_chatbot_result            = update_option( 'ai_chatbot_otp', wp_json_encode( $ai_chatbot_update_option_arr ) );
                $ai_chatbot_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';

                $url = add_query_arg( 'page', $ai_chatbot_page, admin_url( 'admin.php' ) );
                echo '<script>window.location.replace("' . esc_url( $url ) . '");</script>';


            } else {
                $ai_chatbot_error = '';
                $ai_chatbot_error .= '<div class="notice notice-error is-dismissible">';
                $ai_chatbot_error .= '<p>' . esc_html( 'Please enter valid OTP.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
                $ai_chatbot_error .= '</div>';
                echo wp_kses_post( $ai_chatbot_error );
            }
        }
    }
}
//set on reset clicked
if ( isset( $_POST['ai_chatbot_restart_btn'] ) ) {
    $ai_chatbot_legit = true;

    if ( ! isset( $_POST['ai_chatbot_setup_mobile_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_setup_mobile_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_setup_mobile_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_setup_mobile' ) ) {
        $ai_chatbot_legit = false;
    }

    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }

    delete_option( 'ai_chatbot_startup' );

    $ai_chatbot_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
    $url = add_query_arg( 'page', $ai_chatbot_page, admin_url( 'admin.php' ) );
    echo '<script>window.location.replace("' . esc_url( $url ) . '");</script>';



}
$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-2">
            <img src="<?php echo esc_url( $ai_chatbot_logo,'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?>" class="ai_chatbot_imgclass">
        </div>
    </div>
    <div class="row text-center justify-content-center ai_chatbot_scren1_body">
        <div id="ai_chatbot_emailform" class="d-none">
            <form method="post" name="ai_chatbot_emailform"  action="" >
                <label class="ai_chatbot_label mt-4"> <?php esc_html_e( 'Let’s create a chatbot for your website in 2 minutes', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="ai_chatbot-lbl mt-3" id="ai_chatbot_emaillabel"><?php esc_html_e( 'Enter your email', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>
                    </div>
                    <div class="d-flex flex-nowrap flex-row w-100 m-auto align-items-center">
                        <input type="email" name="ai_chatbot_email" id="ai_chatbot_email" placeholder="<?php esc_attr_e( 'Enter email', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?>" autocomplete="off" maxlength="64" class="ai_chatbot_text_input w-100" value="<?php
                        printf(
                            esc_html__( ' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                            esc_html($ai_chatbot_email)
                        );
                        ?>" required>
                    </div>
                </div>
                <label id="ai_chatbot_error_email" class="ai_chatbot_error" ><?php esc_html_e( 'Please enter a valid email address.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>

                <?php wp_nonce_field( 'ai_chatbot_user_email', 'ai_chatbot_user_email_nonce' ); ?>
                <div class="row mb-3">
                    <div class="col-md-12 text-center ai_chatbot_submit_div " >
                        <button type="submit" class="btn ai_chatbot_btn-theme_border"  id="ai_chatbot_getbutton" name="ai_chatbot_getbutton"><?php echo esc_html_e( 'Next', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></button>
                    </div>
                </div>
                <div class="">
                    <label class="ai_chatbot-sublbl"><?php esc_html_e( 'We will send you a one-time password', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>
                </div>
            </form>
        </div>
        <div id="ai_chatbot_usernameform" class="d-none">
            <form method="post" id="ai_chatbot_usernameform" name="ai_chatbot_form1" action="" class="mb-3">
               <div class="row mb-3">
                    <div class="col-12">
                        <label class="ai_chatbot-lbl"><?php esc_html_e( 'Enter OTP', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>
                    </div>
                    <div class="col-12">
                        <label class="ai_chatbot-sublbl mt-3" id="ai_chatbot_emaillabel"><?php esc_html_e(  'OTP sent to ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' );
                            printf(
                                esc_html__( ' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                esc_html($ai_chatbot_email)
                            );
                             ?></label>
                    </div>
                    <div class="d-flex flex-nowrap flex-row m-auto align-items-center">
                        <input type="number" id="ai_chatbot_otp1" class=" ai_chatbot_otp_box" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
                        <input type="number" id="ai_chatbot_otp2" class=" ai_chatbot_otp_box" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
                        <input type="number"  id="ai_chatbot_otp3" class=" ai_chatbot_otp_box" maxlength="1"  oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
                        <input type="text" id="ai_chatbot_otp4" class=" ai_chatbot_otp_box" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  required>
                    </div>
                    <label id="ai_chatbot_error_email" class="ai_chatbot_error" ><?php esc_html_e( 'Please enter a valid email address  .', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>
                </div>
                <input type="hidden" id="ai_chatbot_otp" name="ai_chatbot_otp">
                <?php wp_nonce_field( 'ai_chatbot_user_credentials', 'ai_chatbot_user_credentials_nonce' ); ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn ai_chatbot_btn-theme" name="ai_chatbot_submitbutton" id="ai_chatbot_submitbutton" onclick="ai_chatbot_otpvalidation()"><?php
                            esc_html_e( 'Verify', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?>
                        </button>
                    </div>
                </div>
            </form>
            <form method="post" id="ai_chatbot_usernameform" name="ai_chatbot_form3" action="" class=" mt-3">
                <div class="row justify-content-center">
                    <div class="d-flex mt-3">
                        <p id="clock"></p>
                        <button type="button" id="ai_chatbot_resend_btn" class="ai_chatbot_resend_btn" onclick="ai_chatbot_backbtn()"><?php esc_html_e(  'Request new OTP', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

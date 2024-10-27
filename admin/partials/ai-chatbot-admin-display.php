<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo

if ( isset( $_POST['ai_chatbot_save_button'] ) ) {
    $ai_chatbot_flag="1";
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_settings_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_settings_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_settings_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_settings_form' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
    }
    if (isset($_POST['ai_chatbot_settings_form'])) {
        $ai_chatbot_ispublish = sanitize_text_field($_POST['ai_chatbot_settings_form']);
        $ai_chatbot_ispublish = 1;
        $ai_chatbot_success_message='';
        $ai_chatbot_success_message .= '<div class=" notice notice-success is-dismissible">';
        $ai_chatbot_success_message .= '<p>' . esc_html('Chatbot is enable.','ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
        $ai_chatbot_success_message .= '</div>';
        echo wp_kses_post($ai_chatbot_success_message);
    } else {
        $ai_chatbot_ispublish = 0;
        $ai_chatbot_error_message='';
        $ai_chatbot_error_message .= '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error_message .= '<p>' . esc_html('Chatbot is disable.','ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
        $ai_chatbot_error_message .= '</div>';
        echo wp_kses_post($ai_chatbot_error_message);
    }
    update_option('ai_chatbot_is_public', $ai_chatbot_ispublish);
}

$ai_chatbot_call_script= Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_script();
$ai_chatbot_ispublish=get_option('ai_chatbot_is_public');
$ai_chatbot_get_script  = get_option( 'ai_chatbot_get_script' );
$ai_chatbot_get_script= sanitize_option(  "ai_chatbot_get_script",$ai_chatbot_get_script);

//set on reset clicked
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
    <form method="post" action="">
        <div class="card ai_chatbot_card p-4 w-30">
            <div class="form-group">
                <div class="m-2"><label class="ai_chatbot_label mb-0" for="ai_chatbot_ispublish"><?php esc_html_e('Activate Chatbot on Website: ','ai-chatbot-live-chat-for-wordpress-using-chatgpt') ?></label><label class="ai_chatbot-label3">
                        <input type="checkbox" class="form-control mx-2" id="ai_chatbot_settings_form" name="ai_chatbot_settings_form" <?php if ($ai_chatbot_ispublish == 1 || $ai_chatbot_ispublish == "") { echo 'checked'; } ?>>
                    </label></div>
            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <?php
                    // Create a unique nonce token for the first button
                    $ai_chatbot_nonce = wp_create_nonce( 'ai_chatbot_settings_form' );
                    // Output the nonce field for the first button
                    wp_nonce_field( 'ai_chatbot_settings_form', 'ai_chatbot_settings_nonce' ); ?>
                    <input type="submit" value="<?php esc_attr_e( 'Save', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?>" id="ai_chatbot_save_button" name="ai_chatbot_save_button" class="btn ai_chatbot_btn-theme">
                </div>
            </div>
        </div>
    </form>
<br>
    <label for="ai_chatbot_widget_script"><?php esc_html_e( 'Note:  Please visit  ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?>
        <a href="<?php echo esc_url(ai_chatbot_site_link); ?>" target="_blank"><?php esc_html_e( 'https://www.robofy.ai', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?> </a><?php esc_html_e( 'for further chatbot customization and additional features.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></label>
    <form method="post" name="ai_chatbot_form3" action="" class=" mt-3">
        <div class="row justify-content-center">
            <?php
            $ai_chatbot_nonce = wp_create_nonce( 'ai_chatbot_cancel_action' );
            wp_nonce_field( 'ai_chatbot_cancel_action', 'ai_chatbot_cancel_nonce' );
            ?>
            <button type="submit" name="ai_chatbot_restart_btn" class="ai_chatbot_resend_btn btn ai_chatbot_btn-theme2 mx-2" ><?php esc_html_e(  'Log out and restart', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></button>
        </div>
    </form>
</div>

<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo
//fetch plan data
$ai_chatbot_fetch = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_check_user_credit();

// Check if the user has a valid plan and retrieve maximum crawl pages allowed
if (isset($ai_chatbot_fetch->Data) && isset($ai_chatbot_fetch->Data->MaxCrawlPages)) {
    $ai_chatbot_plan_name = $ai_chatbot_fetch->Data->PlanName;
    $ai_chatbot_max_crawl_pages = $ai_chatbot_fetch->Data->MaxCrawlPages;
    $ai_chatbot_substring = "free";
    if (stristr($ai_chatbot_plan_name, $ai_chatbot_substring)) {
        $ai_chatbot_plan_label = esc_html("You are currently using a free plan. ") . '<a href="' . esc_url("https://www.robofy.ai/pricing") . '" target="_blank">'.esc_html("Upgrade").'</a>' . esc_html(" Plan to add more content for chatbot.");
    } else {
        $ai_chatbot_plan_label="";
    }
}
$ai_chatbot_get_widget_status = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_botready_status();

$ai_chatbot_error_botstatus="";
$ai_chatbot_plan_name = "";
$ai_chatbot_max_crawl_pages = 0;
$ai_chatbot_confirm = "";
$ai_chatbot_get_website_id="";
if (!$ai_chatbot_get_widget_status || !isset($ai_chatbot_get_widget_status->responseStatusCode) || $ai_chatbot_get_widget_status->responseStatusCode != 200 || !isset($ai_chatbot_get_widget_status->Data->BotStatus)) {
    $ai_chatbot_error_botstatus .= '<div class="notice notice-error is-dismissible">';
    $ai_chatbot_error_botstatus .= '<p>' . esc_html('Something went wrong!', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
    $ai_chatbot_error_botstatus .= '</div>';
} else {
    $ai_chatbot_botstatus = $ai_chatbot_get_widget_status->Data->BotStatus;
    switch ($ai_chatbot_botstatus) {
        case 0:
            $ai_chatbot_error_botstatus .= '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error_botstatus .= '<p>' . esc_html('Your bot is not ready.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
            $ai_chatbot_error_botstatus .= '</div>';
            break;
        case 1:
            $ai_chatbot_error_botstatus .= '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error_botstatus .= '<p>' . esc_html('Select your blogposts, pages, and products to prepare the chatbot.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
            $ai_chatbot_error_botstatus .= '</div>';
            break;
        case 2:
            $ai_chatbot_error_botstatus .= '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error_botstatus .= '<p>' . esc_html('Your website links are being crawled and the bot will be ready in some time. Please check after some time.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
            $ai_chatbot_error_botstatus .= '</div>';
            break;
        case 3:
            if (isset($ai_chatbot_get_widget_status->Data->JsScript)) {
                $ai_chatbot_jscode = $ai_chatbot_get_widget_status->Data->JsScript;
                update_option('ai_chatbot_chatbot_widget', $ai_chatbot_jscode);
                $ai_chatbot_widget_script = $ai_chatbot_jscode;
                $ai_chatbot_error_botstatus .= '<div class="notice notice-success is-dismissible">';
                $ai_chatbot_error_botstatus .= '<p>' . esc_html('Your bot is ready to use.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
                $ai_chatbot_error_botstatus .= '</div>';
            } else {
                $ai_chatbot_error_botstatus .= '<div class="notice notice-error is-dismissible">';
                $ai_chatbot_error_botstatus .= '<p>' . esc_html('Your website links are being crawled and the bot will be ready in some time. Please check after some time.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
                $ai_chatbot_error_botstatus .= '</div>';
            }
            break;
        default:

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
            }
            $ai_chatbot_update_option_arr = array(
                'ai_chatbot_email' => $ai_chatbot_emailid,
                'ai_chatbot_username' => $ai_chatbot_username,
                'ai_chatbot_password' => $ai_chatbot_password,
                'ai_chatbot_cron_time'       => $ai_chatbot_cron_time,
            );
            $ai_chatbot_result            = update_option( 'ai_chatbot_adminsettings', wp_json_encode( $ai_chatbot_update_option_arr ) );
            $ai_chatbot_error_botstatus .= '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error_botstatus .= '<p>' . esc_html('Something went wrong!', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
            $ai_chatbot_error_botstatus .= '</div>';
            break;
    }
}
echo wp_kses_post($ai_chatbot_error_botstatus);
?>
<div class="container">
    <div class="row justify-content-between">
        <div class="col-2">
            <img src="<?php printf(
                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                esc_url($ai_chatbot_logo)
            ); ?>" class="ai_chatbot_imgclass" alt="<?php esc_attr('logo', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?>">

            <label class="text-capitalize ai_chatbot-label3">
                <b><?php esc_html_e('Robofy AI ChatBot', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></b>

            </label>
        </div>
        <div class="col-3">
<br>
            <label><?php printf(
                    '%s',
                    $ai_chatbot_plan_label
                ); ?></label>
        </div>
    </div>
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link ai_chatbot_nav_text active " aria-current="page" href="#Dashboard"><?php esc_html_e( 'Dashboard', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link ai_chatbot_nav_text " aria-current="page" href="#settings"><?php esc_html_e( 'Settings', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></a>
            </li>
        </ul>
    </div>
    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Dashboard Tab -->
        <div id="Dashboard" class="tab-pane fade in active show">
            <?php include( esc_html( plugin_dir_path( __FILE__ ) . 'ai-chatbot-admin-dashboard.php' ) ); ?>
        </div>
        <!-- Settings Tab -->
        <div id="settings" class="tab-pane fade">
            <?php include( esc_html( plugin_dir_path( __FILE__ ) . 'ai-chatbot-admin-display.php' ) ); ?>
        </div>
</div>
</div>


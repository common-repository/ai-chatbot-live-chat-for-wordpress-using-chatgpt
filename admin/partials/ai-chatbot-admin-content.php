<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo

if ( isset( $_POST['ai_chatbot_website_delete_btn'] ) ) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_content_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_content_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_content_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_user_content' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }
    $ai_chatbot_website_delete_id = $_POST['website_delete_id'];

    $ai_chatbot_delete = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_delete_website_link($ai_chatbot_website_delete_id);
    if($ai_chatbot_delete == '200'){

        $ai_chatbot_success_message='';
        $ai_chatbot_success_message .= '<div class=" notice notice-success is-dismissible">';
        $ai_chatbot_success_message .= '<p>' . esc_html('Website link deleted Successfully.','ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
        $ai_chatbot_success_message .= '</div>';
        echo wp_kses_post( $ai_chatbot_success_message );
    } else{
        $ai_chatbot_error = '';
        $ai_chatbot_error .= '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error .= '<p>' . esc_html( 'Something Went Wrong!!', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ) . '</p>';
        $ai_chatbot_error .= '</div>';
        echo wp_kses_post( $ai_chatbot_error );

    }



}
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
    // Handle bot status cases
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
//
$ai_chatbot_get_website_data=Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_api_data();

//if $ai_chatbot_get_website_data found
if (!empty($ai_chatbot_get_website_data['Data'])) {
    $ai_chatbot_show_flag=0;
    foreach ($ai_chatbot_get_website_data['Data'] as $ai_chatbot_item){
        $ai_chatbot_get_website_id=$ai_chatbot_item['Id'];
    }
    if($ai_chatbot_get_website_id==""||empty($ai_chatbot_get_website_id)){
        $ai_chatbot_show_flag=1;
    }
}else {
    $ai_chatbot_show_flag=1;
}
if (isset($_POST['ai_chatbot_add_content'])) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_addcontent_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_addcontent_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_addcontent_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_user_addcontent' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }
    $ai_chatbot_show_flag=1;
}
$ai_chatbot_fetch = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_check_user_credit();

// Check if the user has a valid plan and retrieve maximum crawl pages allowed
if (isset($ai_chatbot_fetch->Data) && isset($ai_chatbot_fetch->Data->MaxCrawlPages)) {
    $ai_chatbot_plan_name = $ai_chatbot_fetch->Data->PlanName;
    $ai_chatbot_max_crawl_pages = $ai_chatbot_fetch->Data->MaxCrawlPages;
}
if (isset($_POST['submit'])) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_submit_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_submit_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_submit_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_submit_action' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }

    // Retrieve selected ai_chatbot_pages, posts and products
    $ai_chatbot_selected_pages = isset($_POST['ai_chatbot_selected_pages']) ? $_POST['ai_chatbot_selected_pages'] : array();
    $ai_chatbot_selected_posts = isset($_POST['post']) ? $_POST['post'] : array();
    $ai_chatbot_selected_products = isset($_POST['items']) ? $_POST['items'] : array();

    // Combine all selected items into a single array
    $ai_chatbot_all_selected_items = array_merge($ai_chatbot_selected_pages, $ai_chatbot_selected_posts, $ai_chatbot_selected_products);

    // Update plugin option with the selected IDs
    $ai_chatbot_update_option_arr = array('ai_chatbot_selected_ids' => $ai_chatbot_all_selected_items);
    $ai_chatbot_result = update_option('ai_chatbot_botdata_ids', wp_json_encode($ai_chatbot_update_option_arr));

    // Check if the selected ai_chatbot_pages exceed the maximum crawl ai_chatbot_pages
    $ai_chatbot_fetch = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_check_user_credit();

    $ai_chatbot_max_crawl_pages = 0;
    $ai_chatbot_flag = 0;

    if (isset($ai_chatbot_fetch->Data) && isset($ai_chatbot_fetch->Data->MaxCrawlPages)) {
        $ai_chatbot_max_crawl_pages = $ai_chatbot_fetch->Data->MaxCrawlPages;
    }

    $ai_chatbot_selected_page_count = count($ai_chatbot_all_selected_items);
    if ($ai_chatbot_selected_page_count > $ai_chatbot_max_crawl_pages) {
        $ai_chatbot_remaining_pages = $ai_chatbot_selected_page_count - $ai_chatbot_max_crawl_pages;
        $ai_chatbot_flag = $ai_chatbot_remaining_pages;
    }


    if ($ai_chatbot_flag === 0) {
        // Store bot data
        $ai_chatbot_response = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_store_botdata($ai_chatbot_selected_pages, $ai_chatbot_selected_posts, $ai_chatbot_selected_products);
        if ( is_wp_error( $ai_chatbot_response ) ) {
            // Handle the error
            $ai_chatbot_error_message = $ai_chatbot_response->get_error_message();
            $ai_chatbot_error = '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error .= '<p>' . $ai_chatbot_error_message . '</p>';
            $ai_chatbot_error .= '</div>';
            // Display error message
            echo wp_kses_post($ai_chatbot_error);
            $ai_chatbot_show_flag=1;

        }
        // Handle response
        else if ($ai_chatbot_response) {
            $ai_chatbot_success = '<div class="notice notice-success is-dismissible">';
            $ai_chatbot_success .= '<p>' . esc_html('Your Details are updated successfully.This may take few minutes!!', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
            $ai_chatbot_success .= '</div>';
            // Display success message
            echo wp_kses_post($ai_chatbot_success);
            $ai_chatbot_upload = 1;
            foreach ($ai_chatbot_get_website_data['Data'] as $ai_chatbot_item){
                $ai_chatbot_get_website_id=$ai_chatbot_item['Id'];
            }
            if($ai_chatbot_get_website_id==""||empty($ai_chatbot_get_website_id)){
                $ai_chatbot_show_flag=1;
            }else{
                $ai_chatbot_show_flag=0;
            }

        } else {
            $ai_chatbot_error = '<div class="notice notice-error is-dismissible">';
            $ai_chatbot_error .= '<p>' . esc_html__('Error in bot data.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
            $ai_chatbot_error .= '</div>';
            // Display error message
            echo wp_kses_post($ai_chatbot_error);
            $ai_chatbot_show_flag=1;

        }
    } else {
        $ai_chatbot_confirm = 1;
        $ai_chatbot_show_flag=1;


    }

}
if ( isset( $_POST['ai_chatbot_cancel_btn'] ) ) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_cancel_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_cancel_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_cancel_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_cancel_action' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }


    printf(
        '%s',
        '<script>ai_chatbot_refreshPage();</script>'
    );
}
if (isset($_POST['ai_chatbot_confirm'])) {


    if ( ! isset( $_POST['ai_chatbot_confirm_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_confirm_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_confirm_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_confirm_action' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }

// Sanitize selected ai_chatbot_pages, posts, and products
    $ai_chatbot_selected_pages = isset($_POST['ai_chatbot_selected_pages']) ? array_map('htmlspecialchars', $_POST['ai_chatbot_selected_pages']) : [];
    $ai_chatbot_selected_posts = isset($_POST['post']) ? array_map('htmlspecialchars', $_POST['post']) : [];
    $ai_chatbot_selected_products = isset($_POST['items']) ? array_map('htmlspecialchars', $_POST['items']) : [];


    // Combine all selected items into a single array
    $ai_chatbot_all_selected_items = array_merge($ai_chatbot_selected_pages, $ai_chatbot_selected_posts, $ai_chatbot_selected_products);

    // Limit the number of items to the maximum crawl ai_chatbot_pages
    $ai_chatbot_all_selected_items = array_slice($ai_chatbot_all_selected_items, 0, $ai_chatbot_max_crawl_pages);

    // Update plugin option with the selected IDs
    $ai_chatbot_update_option_arr = ['ai_chatbot_selected_ids' => $ai_chatbot_all_selected_items];
    $ai_chatbot_result = update_option('ai_chatbot_botdata_ids', wp_json_encode($ai_chatbot_update_option_arr));

    // Filter selected ai_chatbot_pages, posts, and products by the limited item array
    $ai_chatbot_selected_pages = array_intersect($ai_chatbot_selected_pages, $ai_chatbot_all_selected_items);
    $ai_chatbot_selected_posts = array_intersect($ai_chatbot_selected_posts, $ai_chatbot_all_selected_items);
    $ai_chatbot_selected_products = array_intersect($ai_chatbot_selected_products, $ai_chatbot_all_selected_items);

    // Store bot data using the updated $ai_chatbot_all_selected_items array
    $ai_chatbot_response = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_store_botdata($ai_chatbot_selected_pages, $ai_chatbot_selected_posts, $ai_chatbot_selected_products);
    if ( is_wp_error( $ai_chatbot_response ) ) {
        // Handle the error
        $ai_chatbot_error_message = $ai_chatbot_response->get_error_message();
        $ai_chatbot_error = '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error .= '<p>' . $ai_chatbot_error_message . '</p>';
        $ai_chatbot_error .= '</div>';
        // Display error message
        echo wp_kses_post($ai_chatbot_error);
        $ai_chatbot_show_flag=1;

    }
    // Handle response
    else if ($ai_chatbot_response) {
        $ai_chatbot_success = '<div class="notice notice-success is-dismissible">';
        $ai_chatbot_success .= '<p>' . esc_html('Your Below Details are updated successfully.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
        $ai_chatbot_success .= '</div>';

        // Display success message
        echo wp_kses_post($ai_chatbot_success);
        $ai_chatbot_upload = 1;
        foreach ($ai_chatbot_get_website_data['Data'] as $ai_chatbot_item){
            $ai_chatbot_get_website_id=$ai_chatbot_item['Id'];
        }
        if($ai_chatbot_get_website_id==""||empty($ai_chatbot_get_website_id)){
            $ai_chatbot_show_flag=1;
        }else{
            $ai_chatbot_show_flag=0;
        }
    } else {
        $ai_chatbot_error = '<div class="notice notice-error is-dismissible">';
        $ai_chatbot_error .= '<p>' . esc_html__('Error in bot data.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
        $ai_chatbot_error .= '</div>';
        // Display error message
        echo wp_kses_post($ai_chatbot_error);
        $ai_chatbot_show_flag=1;

    }
}
if (isset($_POST['ai_chatbot_cancel'])) {


    if ( ! isset( $_POST['ai_chatbot_cancel_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_cancel_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_cancel_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_cancel_action' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }
    // Reset ai_chatbot_selected_ids to an empty string
    $ai_chatbot_update_option_arr = array('ai_chatbot_selected_ids' => "");
    $ai_chatbot_result = update_option('ai_chatbot_botdata_ids', wp_json_encode($ai_chatbot_update_option_arr));

    foreach ($ai_chatbot_get_website_data['Data'] as $ai_chatbot_item){
        $ai_chatbot_get_website_id=$ai_chatbot_item['Id'];
    }
    if($ai_chatbot_get_website_id==""||empty($ai_chatbot_get_website_id)){
        $ai_chatbot_show_flag=1;
    }else{
        $ai_chatbot_show_flag=0;
    }
}// Check user's credit and retrieve plan details

// Check if form was submitted
$ai_chatbot_data = get_option('ai_chatbot_botdata_ids');

// Decode JSON only if it is not false or null
if ($ai_chatbot_data !== false) {
    $ai_chatbot_data = json_decode($ai_chatbot_data);
}

// Sanitize the option
$ai_chatbot_data = sanitize_option('ai_chatbot_botdata_ids', $ai_chatbot_data);

// Check if $ai_chatbot_data is an object and has the property 'ai_chatbot_selected_ids'
$ai_chatbot_fetch_id = (is_object($ai_chatbot_data) && property_exists($ai_chatbot_data, 'ai_chatbot_selected_ids')) ? $ai_chatbot_data->ai_chatbot_selected_ids : '';
if($ai_chatbot_show_flag===0){
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

        </div>
        <form action="" method="post">
            <?php wp_nonce_field( 'ai_chatbot_user_addcontent', 'ai_chatbot_user_addcontent_nonce' ); ?>
            <div class="row-cols-4 text-right">
                <button type="submit" name="ai_chatbot_add_content" class="btn ai_chatbot_btn-theme2">Add content</button>
            </div>
        </form>
        <div class="card ai_chatbot_card w-100">
            <label><?php  esc_html_e('Website links', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
            <div class="table-responsive h-auto border">
                <table id="ai_chatbot_table_dashboard" class="table table-striped table-bordered w-100 mb-0">
                    <thead>
                    <tr>
                        <th><?php  esc_html_e('URL', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                        <th><?php  esc_html_e('Crawl Status', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                        <th><?php  esc_html_e('URL Added Date', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                        <th><?php  esc_html_e('Crawl Date', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                        <th><?php  esc_html_e('Action', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    function ai_chatbot_getCrawlStatusMessage($ai_chatbot_crawlStatus)
                    {
                        switch ($ai_chatbot_crawlStatus) {
                            case 0:
                            case 1:
                                return 'Crawling pending';
                            case 2:
                                return 'Crawling completed';
                            case 3:
                                return 'URL doesn\'t seem valid or content not available.';
                            case 4:
                                return 'URL has too little text content';
                            default:
                                return 'Unknown Crawl Status';
                        }
                    }
                    ?>
                    <?php
                    function ai_chatbot_formatDate($ai_chatbot_dateString)
                    {
                        $ai_chatbot_date = new DateTime($ai_chatbot_dateString);
                        return $ai_chatbot_date->format('d M Y H:i'); // Change the format as per your requirements
                    }
                    ?>
                    <?php foreach ($ai_chatbot_get_website_data['Data'] as $ai_chatbot_item) : ?>
                        <tr>
                            <td><?php printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html($ai_chatbot_item['URL'])); ?></td>
                            <td><?php printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html(ai_chatbot_getCrawlStatusMessage($ai_chatbot_item['CrawlStatus']))); ?></td>
                            <td><?php printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html(ai_chatbot_formatDate($ai_chatbot_item['URLAddedDate']))); ?></td>
                            <td><?php printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html(ai_chatbot_formatDate($ai_chatbot_item['CrawlDate']))); ?></td>

                            <td class="text-info">
                                <form method="post" action="">
                                    <?php wp_nonce_field( 'ai_chatbot_user_content', 'ai_chatbot_user_content_nonce' ); ?>
                                    <input type="hidden" name="website_delete_id" id="website_delete_id" value="<?php printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html($ai_chatbot_item['Id'])); ?>">
                                    <button type="button" name="ai_chatbot_website_delete_btn" class="btn mb-2 mt-0 text-info" onclick="confirmDelete('ai_chatbot_website_delete_btn')">
                                        <u><?php esc_html_e('Delete', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></u>
                                    </button> </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } else if($ai_chatbot_show_flag===1) {
// Set arguments for get_posts() and wc_get_products() functions
    $ai_chatbot_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $ai_chatbot_posts = get_posts($ai_chatbot_args);
    $ai_chatbot_is_wc_products_found=0;
    if ( function_exists( 'wc_get_products' ) ) {
        $ai_chatbot_products = wc_get_products(array(
            'status' => 'publish',
        ));
        // Rest of your code goes here
    }else{
        $ai_chatbot_products="";
    }
    ?>
    <div class="container">
        <form method="post" action="">
            <?php if ($ai_chatbot_confirm === 1) : ?>
                <div class="card ai_chatbot_card mb-3">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <label><?php printf(
                                    esc_html__('Attention: You have indicated that you wish to utilise %d items, however, the maximum allowable limit is %d items. Therefore, only the first %d items will be utilized. To proceed, kindly click on the \'Confirm\' button.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'),
                                    $ai_chatbot_selected_page_count,
                                    $ai_chatbot_max_crawl_pages,
                                    $ai_chatbot_max_crawl_pages
                                ); ?></label>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div>
                            <?php
                            $ai_chatbot_nonce = wp_create_nonce( 'ai_chatbot_cancel_action' );
                            wp_nonce_field( 'ai_chatbot_cancel_action', 'ai_chatbot_cancel_nonce' );
                            ?>
                            <button type="submit" class="btn ai_chatbot_btn-theme_border" name="ai_chatbot_cancel"><?php esc_html_e('Cancel'); ?></button>
                            <?php
                            $ai_chatbot_nonce = wp_create_nonce( 'ai_chatbot_confirm_action' );
                            wp_nonce_field( 'ai_chatbot_confirm_action', 'ai_chatbot_confirm_nonce' );
                            ?>
                            <button type="submit" class="btn ai_chatbot_btn-theme" name="ai_chatbot_confirm"><?php esc_html_e('Confirm'); ?></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="m-3 mt-0">
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

                </div>
                <label class="mt-2 ai_chatbot_label"><?php esc_html_e('Select Pages/Posts/Products:', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                <br>
                <label class="ai_chatbot_label"><?php printf(
                        esc_html__('You are on the %s. You can use a total of %d items from the below list. %s to add more pages.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'),
                        $ai_chatbot_plan_name,
                        $ai_chatbot_max_crawl_pages,
                        '<a target="_blank" href="' . esc_url('https://www.whatso.net/ai-chatbot#pricingBlock') . '">' . esc_html__('Upgrade now') . '</a>'
                    ); ?></label>
            </div>
            <div class="card ai_chatbot_card w-100">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="ai_chatbot_sub_label mt-3"> <?php  esc_html_e('Select the pages to build the AI Chatbot', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                    <label class="ai_chatbot_switch">
                        <input type="checkbox" id="ai_chatbot_checkbox_page" name="ai_chatbot_checkbox_page" class="ai_chatbot_switch_button" checked/>
                        <span class="ai_chatbot_slider ai_chatbot_round"></span>
                    </label>
                </div>
                <hr class="my-1">
                <div id="ai_chatbot_displaypage" class="d-none ai_chatbot_displaybox">
                    <div class="table-responsive table-container border">
                        <?php
                        $ai_chatbot_args_page = array(
                            'post_type' => 'page',
                            'post_status' => 'publish',
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'posts_per_page' => -1,
                        );
                        $ai_chatbot_pages = get_posts($ai_chatbot_args_page);

                        // Separate pages into two arrays: selected and remaining
                        $ai_chatbot_selected_pages = array();
                        $ai_chatbot_remaining_pages = array();
                        if (!empty($ai_chatbot_pages)) {
                            foreach ($ai_chatbot_pages as $ai_chatbot_page) {
                                if (is_array($ai_chatbot_fetch_id) && in_array($ai_chatbot_page->ID, $ai_chatbot_fetch_id)) {
                                    $ai_chatbot_selected_pages[] = $ai_chatbot_page;
                                } else {
                                    $ai_chatbot_remaining_pages[] = $ai_chatbot_page;
                                }
                            }
                        }

                        // Merge selected and remaining arrays
                        $ai_chatbot_all_pages = array_merge($ai_chatbot_selected_pages, $ai_chatbot_remaining_pages);
                        ?>

                        <table id="ai_chatbot_botdata" class="table table-striped table-bordered w-100">
                            <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"></th>
                                <th><?php  esc_html_e('Title', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                <th><?php  esc_html_e('Type', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                <th><?php  esc_html_e('Last modified on', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($ai_chatbot_all_pages)) {
                                foreach ($ai_chatbot_all_pages as $ai_chatbot_page) {
                                    $ai_chatbot_last_modified = get_post_modified_time('U', false, $ai_chatbot_page->ID);
                                    $ai_chatbot_last_modified = date('d M Y H:i:s', $ai_chatbot_last_modified);
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" class="ai_chatbot_child_checkbox" name="ai_chatbot_selected_pages[]" value="<?php printf(
                                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                esc_attr($ai_chatbot_page->ID)
                                            ); ?>"<?php if (is_array($ai_chatbot_fetch_id) && in_array($ai_chatbot_page->ID, $ai_chatbot_fetch_id)) { echo ' checked'; } ?>></td>
                                        <td><a target="_blank" href="<?php printf(
                                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                esc_url(get_permalink($ai_chatbot_page->ID))
                                            ); ?>"><?php printf(
                                                    esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                    esc_html($ai_chatbot_page->post_title)
                                                );?></a></td>
                                        <td><?php  esc_html_e($ai_chatbot_page->post_type); ?></td>
                                        <td><?php  esc_html_e($ai_chatbot_last_modified); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4"><?php  esc_html_e('No pages found.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php if($ai_chatbot_posts!="") {?>
                <div class="card ai_chatbot_card w-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="ai_chatbot_sub_label mt-3"><?php  esc_html_e('Select the posts to build the AI Chatbot', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                        <label class="ai_chatbot_switch">
                            <input type="checkbox" id="ai_chatbot_checkbox_post" name="ai_chatbot_checkbox_post" class="ai_chatbot_switch_button" checked/>
                            <span class="ai_chatbot_slider ai_chatbot_round"></span>
                        </label>
                    </div>
                    <hr class="my-1">
                    <div id="ai_chatbot_displaypost" class="d-none ai_chatbot_displaybox">
                        <div class="table-responsive table-container border">
                            <?php
                            $ai_chatbot_selected_posts = array(); // initialize array to hold selected posts
                            $ai_chatbot_unselected_posts = array(); // initialize array to hold unselected posts
                            foreach ($ai_chatbot_posts as $ai_chatbot_post) {
                                if (is_array($ai_chatbot_fetch_id) && in_array($ai_chatbot_post->ID, $ai_chatbot_fetch_id)) {
                                    // if post is selected, add to selected array
                                    $ai_chatbot_selected_posts[] = $ai_chatbot_post;
                                } else {
                                    // if post is not selected, add to unselected array
                                    $ai_chatbot_unselected_posts[] = $ai_chatbot_post;
                                }
                            }
                            // merge selected and unselected arrays
                            $ai_chatbot_posts = array_merge($ai_chatbot_selected_posts, $ai_chatbot_unselected_posts);
                            ?>
                            <table id="ai_chatbot_botdata" class="table table-striped table-bordered w-100">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="check-all"></th>
                                    <th><?php  esc_html_e('Title', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                    <th><?php  esc_html_e('Type', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                    <th><?php  esc_html__('Last modified on', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($ai_chatbot_posts as $ai_chatbot_post) {
                                    $ai_chatbot_last_modified = get_post_modified_time('U', false, $ai_chatbot_post->ID);
                                    $ai_chatbot_last_modified = date('d M Y H:i:s', $ai_chatbot_last_modified);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="ai_chatbot_child_checkbox" name="post[]" value="<?php printf(
                                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                esc_attr($ai_chatbot_post->ID)
                                            ); ?>" <?php checked(is_array($ai_chatbot_fetch_id) && in_array($ai_chatbot_post->ID, $ai_chatbot_fetch_id)); ?>>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?php printf(
                                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                esc_url(get_permalink($ai_chatbot_post->ID))
                                            ); ?>"><?php printf(
                                                    esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                    esc_html($ai_chatbot_post->post_title)
                                                ); ?></a>
                                        </td>
                                        <td><?php  esc_html_e('Post'); ?></td>
                                        <td><?php printf(
                                                esc_html__( ' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                esc_html($ai_chatbot_last_modified)
                                            ); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if($ai_chatbot_products!="") {?>

                <div class="card ai_chatbot_card w-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="ai_chatbot_sub_label mt-3"><?php  esc_html_e('Select the products to build the AI Chatbot', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                        <label class="ai_chatbot_switch">
                            <input type="checkbox" id="ai_chatbot_checkbox_product" name="ai_chatbot_checkbox_product" class="ai_chatbot_switch_button" checked/>
                            <span class="ai_chatbot_slider ai_chatbot_round"></span>
                        </label>
                    </div>
                    <hr class="my-1">
                    <div id="ai_chatbot_displayproduct" class="d-none ai_chatbot_displaybox">
                        <?php if (count($ai_chatbot_products) > 0) : ?>
                            <?php
                            // Separate pages into two arrays: selected and remaining
                            $ai_chatbot_selected_products = array();
                            $ai_chatbot_remaining_products = array();
                            if (!empty($ai_chatbot_products)) {
                                foreach ($ai_chatbot_products as $ai_chatbot_product) {
                                    if (is_array($ai_chatbot_fetch_id) && in_array($ai_chatbot_product->get_id(), $ai_chatbot_fetch_id)) {
                                        $ai_chatbot_selected_products[] = $ai_chatbot_product;
                                    } else {
                                        $ai_chatbot_remaining_products[] = $ai_chatbot_product;
                                    }
                                }
                            }

                            // Merge selected and remaining arrays
                            $ai_chatbot_all_products = array_merge($ai_chatbot_selected_products, $ai_chatbot_remaining_products);
                            ?>
                            <div class="table-responsive table-container border">
                                <table id="ai_chatbot_botdata" class="table table-striped table-bordered w-100">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" class="check-all"></th>
                                        <th><?php  esc_html_e('Title', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                        <th><?php  esc_html_e('Type', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                        <th><?php  esc_html_e('Last modified on', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    if (!empty($ai_chatbot_all_products)) {
                                        foreach ($ai_chatbot_all_products as $ai_chatbot_product) { ?>
                                            <?php
                                            $ai_chatbot_last_modified = get_post_modified_time('U', false, $ai_chatbot_product->get_id());
                                            $ai_chatbot_last_modified = date('d M Y H:i:s', $ai_chatbot_last_modified);
                                            $ai_chatbot_product_id = esc_attr($ai_chatbot_product->get_id());
                                            $ai_chatbot_product_name = esc_html($ai_chatbot_product->get_name());
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" class="ai_chatbot_child_checkbox" name="items[]" value="<?php printf(
                                                        esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                        esc_html($ai_chatbot_product_id)
                                                    ); ?>" <?php checked(is_array($ai_chatbot_fetch_id) && in_array($ai_chatbot_product->get_id(), $ai_chatbot_fetch_id)); ?>></td>
                                                <td><a target="_blank" href="<?php printf(
                                                        esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                        esc_url(get_permalink($ai_chatbot_product->get_id()))
                                                    );
                                                    ?>"><?php printf(
                                                            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                            esc_html($ai_chatbot_product_name)
                                                        ); ?></a></td>
                                                <td><?php  esc_html_e('Product', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                                                <td><?php printf(
                                                        esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                                        esc_html($ai_chatbot_last_modified)
                                                    ); ?></td>
                                            </tr>
                                        <?php }
                                    }else{
                                        ?>
                                        <tr>
                                            <td colspan="4"><?php  esc_html_e('No products found.', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p><?php  esc_html_e('No products found', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>
            <?php
            // Create a unique nonce token for the first button
            $ai_chatbot_nonce = wp_create_nonce( 'ai_chatbot_submit_action' );
            // Output the nonce field for the first button
            wp_nonce_field( 'ai_chatbot_submit_action', 'ai_chatbot_submit_nonce' );
            ?>

                <div class="text-center">
                    <button type="submit" name="submit" class="btn ai_chatbot_btn-theme"><?php  esc_html_e( 'Submit', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); ?></button>
                    <?php wp_nonce_field( 'ai_chatbot_cancel_action', 'ai_chatbot_user_cancel_nonce' ); ?>
                    <button type="submit" name="ai_chatbot_cancel_btn" class="btn ai_chatbot_btn-theme2 mx-2"><?php esc_html_e('Back', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></button>



            </div>
        </form>
    </div>
    <?php
}
?>
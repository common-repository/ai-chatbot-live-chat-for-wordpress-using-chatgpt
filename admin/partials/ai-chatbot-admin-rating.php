<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo
$ai_chatbot_response_body=Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_rating_data();
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
// Decode the JSON ai_chatbot_response
$ai_chatbot_data = json_decode( $ai_chatbot_response_body, true );
if ( isset( $_POST['ai_chatbot_rating_edit_btn'] ) ) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_edit_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_edit_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_edit_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_edit_action' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }
    $ai_chatbot_questions_id = $_POST['rating_edit_id'];
    update_option('ai_chatbot_rating_action', $ai_chatbot_questions_id);
    $ai_chatbot_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
    $url = add_query_arg( 'page', $ai_chatbot_page, admin_url( 'admin.php?page=ai-chatbot-admin-editrating' ) );
    printf(
        '<script>window.location.replace("%s");</script>',
        esc_url($url)
    );
}
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
        <?php
        if ( ! empty( $ai_chatbot_data['Data'] ) ) {
            ?>
            <div class="card ai_chatbot_card w-100">
                <label><?php echo esc_html__('Ratings', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                <div class="table-responsive h-auto border">
                    <table id="ai_chatbot_table_dashboard" class="table table-striped ai_chatbot_rating_table table-bordered w-100 mb-0">
                        <thead>
                        <tr>
                            <th><?php  esc_html_e('Date', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                            <th><?php  esc_html_e('Website URL', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                            <th><?php  esc_html_e('Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                            <th><?php  esc_html_e('Bot Answer', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                            <th><?php  esc_html_e('', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php foreach ($ai_chatbot_data['Data'] as $ai_chatbot_item) : ?>
                            <tr>
                                <td><?php  esc_html_e(Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_formatDate_rating($ai_chatbot_item['Date'])); ?></td>
                                <td><?php  esc_html_e($ai_chatbot_item['WebsiteURL']); ?></td>
                                <td><?php  esc_html_e($ai_chatbot_item['QuestionQuery']); ?></td>
                                <td><?php  esc_html_e($ai_chatbot_item['BotAnswer']); ?></td>
                                <td class="text-info">
                                    <form method="post" action="">
                                        <?php wp_nonce_field( 'ai_chatbot_edit_action', 'ai_chatbot_user_edit_nonce' ); ?>


                                        <input type="hidden" name="rating_edit_id" value="<?php esc_html_e( $ai_chatbot_item['Id']); ?>">
                                        <button type="submit" class="btn mb-2 mt-0 text-info" name="ai_chatbot_rating_edit_btn"><u><?php  esc_html_e('Edit', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></u> </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php } else {
            ?>
            <div class="card ai_chatbot_card w-100">
                <label><?php  esc_html_e('No Down Rating till now', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
            </div>
            <?php
        }
        ?>
    </div>



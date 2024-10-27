<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo
$ai_chatbot_get_question_option = get_option("ai_chatbot_rating_action");
$ai_chatbot_action = ($ai_chatbot_get_question_option === 'add_question') ? 0 : 1; // add or edit

if (isset($_POST['ai_chatbot_edit_btn']) || isset($_POST['ai_chatbot_cancel_btn'])) {
    if (isset($_POST['ai_chatbot_edit_btn'])) {
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
        $ai_chatbot_rating_id = $_POST['ai_chatbot_rating_edit_id'];
        $ai_chatbot_question = isset($_POST['ai_chatbot_main_q']) ? sanitize_text_field(wp_unslash($_POST['ai_chatbot_main_q'])) : '';
        $ai_chatbot_ans = isset($_POST['ai_chatbot_answer']) ? sanitize_textarea_field(wp_unslash($_POST['ai_chatbot_answer'])) : '';
        $ai_chatbot_response = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_update_rating_data($ai_chatbot_question, $ai_chatbot_ans);
        delete_option('ai_chatbot_rating_action');
    }

    if (isset($_POST['ai_chatbot_cancel_btn'])) {
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
        delete_option('ai_chatbot_rating_action');
    }

    $ai_chatbot_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
    $url = add_query_arg( 'page', $ai_chatbot_page, admin_url( 'admin.php' ) );
    printf(
        '<script>window.location.replace("%s");</script>',
        esc_url($url)
    );

}
?>

<div class="container">
    <div class="text-center">
        <img src="<?php  printf(
            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
            esc_url($ai_chatbot_logo)
        ); ?>" class="ai_chatbot_imgclass" alt="<?php esc_attr('logo', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?>">
        <br>
        <label class="text-capitalize ai_chatbot-label3">
            <b><?php esc_html_e('Robofy AI ChatBot', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></b>
        </label>
        <h3><?php esc_html_e('Edit Rating', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></h3>
    </div>
    <?php
    $ai_chatbot_response_body = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_rating_data();
    $ai_chatbot_data = json_decode($ai_chatbot_response_body, true);
    if (!empty($ai_chatbot_data['Data'])) {
        foreach ($ai_chatbot_data['Data'] as $ai_chatbot_item) {
            if ($ai_chatbot_item['Id'] == $ai_chatbot_get_question_option) {
                ?>
                <div class="card ai_chatbot_card mx-auto w-75 p-5">
                    <form action="" method="post">
                        <input type="hidden" name="ai_chatbot_rating_edit_id" value="<?php
                        printf(
                            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                            esc_html($ai_chatbot_item['Id'])
                        );
                       ?>">
                        <div class="form-group">
                            <label for="ai_chatbot_main_q"><?php esc_html_e('Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                            <input type="text" class="form-control" id="ai_chatbot_main_q" name="ai_chatbot_main_q" placeholder="Type Question" value="<?php
                            printf(
                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                esc_html($ai_chatbot_item['QuestionQuery'])
                            );
                           ?>">
                        </div>
                        <div class="form-group">
                            <label for="ai_chatbot_answer"><?php esc_html_e('Bot Answer', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                            <textarea class="form-control" id="ai_chatbot_answer" rows="3" name="ai_chatbot_answer" placeholder="Type Answer"><?php
                                printf(
                                    esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                    esc_html($ai_chatbot_item['BotAnswer'])
                                );
                                 ?></textarea>
                        </div>
                        <div class="text-center">
                            <?php wp_nonce_field( 'ai_chatbot_edit_action', 'ai_chatbot_user_edit_nonce' ); ?>

                            <button type="submit" name="ai_chatbot_edit_btn" class="btn ai_chatbot_btn-theme mx-2"><?php esc_html_e('Submit', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></button>
                            <?php wp_nonce_field( 'ai_chatbot_cancel_action', 'ai_chatbot_user_cancel_nonce' ); ?>

                            <button type="submit" name="ai_chatbot_cancel_btn" class="btn ai_chatbot_btn-theme2 mx-2"><?php esc_html_e('Back', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></button>

                        </div>
                    </form>
                </div>
 <?php
            }
        }
    }
?>
</div>
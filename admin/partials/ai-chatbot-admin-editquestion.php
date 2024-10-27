<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$ai_chatbot_logo = ai_chatbot_logo; // Define robofy basic logo
$ai_chatbot_fetch = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_check_user_credit();

// Check if the user has a valid plan and retrieve maximum crawl pages allowed
if (isset($ai_chatbot_fetch->Data) && isset($ai_chatbot_fetch->Data->MaxCrawlPages)) {
    $ai_chatbot_plan_name = $ai_chatbot_fetch->Data->PlanName;
    $ai_chatbot_max_crawl_pages = $ai_chatbot_fetch->Data->MaxCrawlPages;
    $ai_chatbot_substring = "free";
    if (stristr($ai_chatbot_plan_name, $ai_chatbot_substring)) {
        $ai_chatbot_plan_label =esc_html("You are currently using a free plan. ") . '<a href="' . esc_url("https://www.robofy.ai/pricing") . '" target="_blank">' . esc_html("Upgrade") . '</a>' . esc_html(" Plan to add more content for chatbot.");
    } else {
        $ai_chatbot_plan_label="";
    }
}
$ai_chatbot_get_question_option = get_option("ai_chatbot_question_action");
if ($ai_chatbot_get_question_option === 'add_question') {
    $ai_chatbot_action = 0;//add
} else {
    $ai_chatbot_action = 1;//edit
}

if(isset($_POST['ai_chatbot_add_btn'])){
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_add_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_add_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_add_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_add_action' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }

    $ai_chatbot_display_que= isset( $_POST['ai_chatbot_display_q'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_display_q'] ) ) : '';
    $ai_chatbot_question= isset( $_POST['ai_chatbot_main_q'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_main_q'] ) ) : '';
    $ai_chatbot_ans= isset( $_POST['ai_chatbot_answer'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ai_chatbot_answer'] ) ) : '';
    $ai_chatbot_response=Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_add_questions_data($ai_chatbot_display_que,$ai_chatbot_question,$ai_chatbot_ans);
    $ai_chatbot_data = json_decode( $ai_chatbot_response, true );
    delete_option('ai_chatbot_question_action');
    printf(
        '%s',
        '<script>ai_chatbot_refreshPage();</script>'
    );
}
if ( isset( $_POST['ai_chatbot_edit_btn'] ) ) {
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
    $ai_chatbot_que_id =  $_POST['ai_chatbot_question_edit_id'] ;
    $ai_chatbot_display_que= isset( $_POST['ai_chatbot_display_q'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_display_q'] ) ) : '';
    $ai_chatbot_question= isset( $_POST['ai_chatbot_main_q'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_main_q'] ) ) : '';
    $ai_chatbot_ans= isset( $_POST['ai_chatbot_answer'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ai_chatbot_answer'] ) ) : '';
    $ai_chatbot_response=Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_edit_questions_data($ai_chatbot_que_id,$ai_chatbot_display_que,$ai_chatbot_question,$ai_chatbot_ans);
    $ai_chatbot_data = json_decode( $ai_chatbot_response, true );
    delete_option('ai_chatbot_question_action');
    printf(
        '%s',
        '<script>ai_chatbot_refreshPage();</script>'
    );
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
    delete_option('ai_chatbot_question_action');

    printf(
        '%s',
        '<script>ai_chatbot_refreshPage();</script>'
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
        <div class="text-center">
            <img src="<?php printf(
                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                esc_url($ai_chatbot_logo)
            ); ?>" class="ai_chatbot_imgclass" alt="<?php esc_attr('logo', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?>">
            <br>
            <label class="text-capitalize ai_chatbot-label3">
                <b><?php esc_html_e('Robofy AI ChatBot', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></b>
            </label>
            <h3>
                <?php if ($ai_chatbot_action == 0) { ?>
                    <?php esc_html_e('Add Quick Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?>
                <?php }else{ ?>
                    <?php esc_html_e('Edit Quick Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?>
                <?php }?>
            </h3></div>
        <form method="post" action="">
            <div class="text-center">
            </div>
        </form>
        <div class="card ai_chatbot_card mx-auto w-75 p-5">
            <?php if ($ai_chatbot_action == 0) { ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="ai_chatbot_display_q"><?php esc_html_e('Display Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                        <input type="text" class="form-control" id="ai_chatbot_display_q" placeholder="Type Question"
                               name="ai_chatbot_display_q">
                    </div>
                    <div class="form-group">
                        <label for="ai_chatbot_main_q"><?php esc_html_e('Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                        <input type="text" class="form-control" id="ai_chatbot_main_q" placeholder="Type Question"
                               name="ai_chatbot_main_q">
                    </div>
                    <div class="form-group">
                        <label for="ai_chatbot_answer"><?php esc_html_e('Answer', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                        <textarea class="form-control" id="ai_chatbot_answer" rows="3" placeholder="Type Answer"
                                  name="ai_chatbot_answer"></textarea>
                    </div>
                    <div class="text-center">
                        <?php wp_nonce_field( 'ai_chatbot_add_action', 'ai_chatbot_user_add_nonce' ); ?>
                        <button type="submit" name="ai_chatbot_add_btn" class="btn ai_chatbot_btn-theme mx-2"><?php esc_html_e('Submit', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></button>
                        <?php wp_nonce_field( 'ai_chatbot_cancel_action', 'ai_chatbot_user_cancel_nonce' ); ?>
                        <button type="submit" name="ai_chatbot_cancel_btn" class="btn ai_chatbot_btn-theme2 mx-2"><?php esc_html_e('Back', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></button>

                    </div>
                </form>
            <?php } else if ($ai_chatbot_action == 1) {
                $ai_chatbot_response_body = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_questions_data();
                $ai_chatbot_data = json_decode($ai_chatbot_response_body, true);
                if (!empty($ai_chatbot_data['Data'])) {
                    foreach ($ai_chatbot_data['Data'] as $ai_chatbot_item) {
                        if ($ai_chatbot_item['Id'] == $ai_chatbot_get_question_option) {
                            ?>
                            <form action="" method="post">
                                <input type="hidden" name="ai_chatbot_question_edit_id" value="<?php
                                printf(
                                    esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                    esc_html($ai_chatbot_item['Id'])
                                );?>">

                                <div class="form-group">
                                    <label for="ai_chatbot_display_q"><?php esc_html_e('Display Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                                    <input type="text" class="form-control" id="ai_chatbot_display_q" name="ai_chatbot_display_q" placeholder="Type Question" value="<?php printf(
                                        esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                        esc_html($ai_chatbot_item['DisplayQuestion'])
                                    ); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ai_chatbot_main_q"><?php esc_html_e('Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                                    <input type="text" class="form-control" id="ai_chatbot_main_q" name="ai_chatbot_main_q" placeholder="Type Question" value="<?php
                                    printf(
                                        esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                        esc_html($ai_chatbot_item['Question'])
                                    );
                                    ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ai_chatbot_answer"><?php esc_html_e('Answer', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
                                    <textarea class="form-control" id="ai_chatbot_answer" rows="3" name="ai_chatbot_answer" placeholder="Type Answer"><?php
                                        printf(
                                            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                            esc_html($ai_chatbot_item['Answer'])
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
                        <?php }
                    }
                }
                ?>
            <?php } ?>
        </div>
    </div>


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
        $ai_chatbot_plan_label = "";
    }
}
$ai_chatbot_response_body=Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_questions_data();

// Decode the JSON response
$ai_chatbot_data = json_decode( $ai_chatbot_response_body, true );
if ( isset( $_POST['ai_chatbot_add'] ) ) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_add_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_add_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_add_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_add' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }

    update_option('ai_chatbot_question_action', "add_question");
    printf(
        '%s',
        '<script>ai_chatbot_refreshPage();</script>'
    );
}
if ( isset( $_POST['ai_chatbot_edit'] ) ) {
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
    $ai_chatbot_questions_id = $_POST['question_edit_id'];
    update_option('ai_chatbot_question_action', $ai_chatbot_questions_id);
    printf(
        '%s',
        '<script>ai_chatbot_refreshPage();</script>'
    );
}
if ( isset( $_POST['ai_chatbot_delete'] ) ) {
    $ai_chatbot_legit = true;
    if ( ! isset( $_POST['ai_chatbot_user_delete_nonce'] ) ) {
        $ai_chatbot_legit = false;
    }
    $ai_chatbot_nonce = isset( $_POST['ai_chatbot_user_delete_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ai_chatbot_user_delete_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $ai_chatbot_nonce, 'ai_chatbot_delete_action' ) ) {
        $ai_chatbot_legit = false;
    }
    if ( ! $ai_chatbot_legit ) {
        wp_safe_redirect( add_query_arg() );
        exit();
    }
    $ai_chatbot_question_id = $_POST['question_edit_id'];
    $ai_chatbot_question_delete = $_POST['question_delete_que'];
    $ai_chatbot_answer_delete = $_POST['question_delete_ans'];
    $ai_chatbot_answer_displayque = $_POST['question_delete_display'];


    $ai_chatbot_delete = Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_delete_questions_data($ai_chatbot_question_id,$ai_chatbot_answer_displayque,$ai_chatbot_question_delete,$ai_chatbot_answer_delete);
    printf(
        '%s',
        '<script>ai_chatbot_refreshPage();</script>'
    );
    if($ai_chatbot_delete == '200'){

        $ai_chatbot_success_message='';
        $ai_chatbot_success_message .= '<div class=" notice notice-success is-dismissible">';
        $ai_chatbot_success_message .= '<p>' . esc_html('FAQ deleted Successfully.','ai-chatbot-live-chat-for-wordpress-using-chatgpt') . '</p>';
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
?>
    <div class="container">
    <div class="row justify-content-between">
        <div class="col-2">
            <img src="<?php printf(
                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                esc_url($ai_chatbot_logo)
            );?>" class="ai_chatbot_imgclass" alt="<?php esc_attr('logo', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?>">

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
    <div class="card ai_chatbot_card w-100">

    <div class="d-flex justify-content-between">
        <label class=""><?php  esc_html_e('FAQ Builder', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
        <form method="post" action="">
            <input type="hidden" name="question" value="ai_chatbot_add_question">
            <?php wp_nonce_field( 'ai_chatbot_add', 'ai_chatbot_user_add_nonce' ); ?>

            <button type="submit" name="ai_chatbot_add" class="btn ai_chatbot_btn-theme2 mb-2 mt-0 "> <?php  esc_html_e('Add Questions', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?> </button>
        </form>
    </div>
<?php
if ( ! empty( $ai_chatbot_data['Data'] ) ) {
    ?>
    <div class="table-responsive h-auto border">
        <table id="ai_chatbot_table_dashboard" class="table table-striped ai_chatbot_rating_table table-bordered w-100 mb-0">
            <thead>
            <tr>
                <th ><?php  esc_html_e('Date', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                <th><?php  esc_html_e('Display Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                <th><?php  esc_html_e('Question', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                <th><?php  esc_html_e('Answer', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                <th><?php  esc_html_e('Action', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            function ai_chatbot_formatDate_questions($ai_chatbot_dateString)
            {
                $ai_chatbot_date = new DateTime($ai_chatbot_dateString);
                return $ai_chatbot_date->format('d M Y H:i'); // Change the format as per your requirements
            }
            ?>
            <?php foreach ($ai_chatbot_data['Data'] as $ai_chatbot_item) : ?>
                <tr>
                    <td ><?php printf(
                            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                            esc_html(ai_chatbot_formatDate_questions($ai_chatbot_item['QuestionDate']))
                        );?></td>
                    <td><?php printf(
                            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                            esc_html($ai_chatbot_item['DisplayQuestion'])
                        ); ?></td>
                    <td><?php printf(
                            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                            esc_html($ai_chatbot_item['Question'])
                        ); ?></td>
                    <td><?php printf(
                            esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                            esc_html($ai_chatbot_item['Answer'])
                        ); ?>
                    </td>

                    <td><form method="post" action="">
                            <input type="hidden" name="question_edit_id" value="<?php printf(
                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                esc_html($ai_chatbot_item['Id'])
                            ); ?>">
                            <input type="hidden" name="question_delete_que" value="<?php printf(
                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                esc_html($ai_chatbot_item['Question'])
                            );  ?>">
                            <input type="hidden" name="question_delete_ans" value="<?php
                            printf(
                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                esc_html($ai_chatbot_item['Answer'])
                            );
                          ?>">
                            <input type="hidden" name="question_delete_display" value="<?php
                            printf(
                                esc_html__( '%s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ),
                                esc_html($ai_chatbot_item['DisplayQuestion'])
                            );
                            ?>">
                            <input type="hidden" name="question" value="ai_chatbot_edit_question">
                            <input type="hidden" name="question" value="ai_chatbot_delete_question">
                            <?php wp_nonce_field( 'ai_chatbot_edit_action', 'ai_chatbot_user_edit_nonce' ); ?>

                            <button type="submit" class="btn mb-2 mt-0 text-info" name="ai_chatbot_edit"><u><?php  esc_html_e('Edit', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></u> </button>
                            <?php wp_nonce_field( 'ai_chatbot_delete_action', 'ai_chatbot_user_delete_nonce' ); ?>


                            <button type="button" class="btn mb-2 mt-0 text-info" name="ai_chatbot_delete" onclick="confirmDelete('ai_chatbot_delete')">
                                <u><?php esc_html_e('Delete', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></u>
                            </button>

                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php } else {
    ?>
    <label><?php  esc_html_e('No quick questions added! Please click on "Add Questions".', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></label>
    <?php
}
?></div>
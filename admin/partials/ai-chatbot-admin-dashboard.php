<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$ai_chatbot_data= Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_dashboard();

if (is_wp_error($ai_chatbot_data)) {
    echo 'Error: ' . $ai_chatbot_data->get_error_message();
} else {
    // Check if the response is an array and contains the necessary data
    if (is_array($ai_chatbot_data) && isset($ai_chatbot_data['responseStatusCode']) && $ai_chatbot_data['responseStatusCode'] == 200 && $ai_chatbot_data['Message'] == 'success') {
        // Extract the required values
        $ai_chatbot_replies_today = $ai_chatbot_data['Data']['RepliesToday'];
        $ai_chatbot_replies_month = $ai_chatbot_data['Data']['RepliesMonth'];
        $ai_chatbot_replies_all = $ai_chatbot_data['Data']['RepliesAll'];
        $ai_chatbot_rating = $ai_chatbot_data['Data']['Rating'];

        $ai_chatbot_chatbotMessageDetails = $ai_chatbot_data['Data']['chatbotMessageDetails'];

    } else {
        $ai_chatbot_replies_today = "0";
        $ai_chatbot_replies_month = "0";
        $ai_chatbot_replies_all = "0";
        $ai_chatbot_rating = "0";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Chatbot Dashboard</title>
</head>
<body>
<div class="container">
    <div class="row mt-2 row-eq-height">
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 pb-2 ai_chatbot_dashboard_card">
            <div class="container-fluid cursor-pointer">
                <a class="row ai_chatbot_widget text-dark">
                    <div class="col-auto p-0">
                        <span class="ai_chatbot_d_card_icon bg-white rounded-circle text-black">&#x1F5E8;</span>
                        <div class="ml-auto"></div>
                    </div>
                    <div class="col quick-category-content">
                        <h4>
                            <?php
                            printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html($ai_chatbot_replies_today));
                            ?></h4>
                        <p class="mb-0 font-weight-bold">Replies - Today</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 pb-2 ai_chatbot_dashboard_card">
            <div class="container-fluid cursor-pointer">
                <a class="row ai_chatbot_widget text-dark">
                    <div class="col-auto p-0">
                        <span class="ai_chatbot_d_card_icon bg-white rounded-circle">&#x1F4C6;</span>
                        <div class="ml-auto"></div>
                    </div>
                    <div class="col quick-category-content">
                        <h4>
                            <?php
                            printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html($ai_chatbot_replies_month));
                            ?>
                            </h4>
                        <p class="mb-0 font-weight-bold">Replies - This Month</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 pb-2 ai_chatbot_dashboard_card">
            <div class="container-fluid cursor-pointer">
                <a class="row ai_chatbot_widget text-dark">
                    <div class="col-auto p-0">
                        <span class="ai_chatbot_d_card_icon bg-white rounded-circle">&#9989;</span>
                        <div class="ml-auto"></div>
                    </div>
                    <div class="col quick-category-content">
                        <h4>
                            <?php
                            printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html($ai_chatbot_replies_all));
                            ?>
                            </h4>
                        <p class="mb-0 font-weight-bold">Replies - All Time</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 pb-2 ai_chatbot_dashboard_card">
            <div class="container-fluid cursor-pointer">
                <a class="row ai_chatbot_widget text-danger">
                    <div class="col-auto p-0">
                        <span class="ai_chatbot_d_card_icon bg-white rounded-circle">&#128078;</span>
                        <div class="ml-auto"></div>
                    </div>
                    <div class="col quick-category-content">
                        <h4> <?php
                            printf(esc_html__(' %s ', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'), esc_html($ai_chatbot_rating));
                            ?>
                           </h4>
                        <p class="mb-0 font-weight-bold">Down Rating</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="w-100 mt-5">
        <div class="row">
            <div class="col-12">
                <div class="h4 text-dark"><b>Last 7 Days Report</b></div>
            </div>
        </div>
        <div class="card ai_chatbot_card border-0 w-100">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive h-auto border">
                        <table class="table table-striped ai_chatbot_rating_table table-bordered w-100 mb-0">
                            <thead>
                            <tr>
                                <th><?php  esc_html_e('Date', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                <th><?php  esc_html_e('Total Messages Answered', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                <th><?php  esc_html_e('Successful Answers', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                <th><?php  esc_html_e('Not Found Answers', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>
                                <th><?php  esc_html_e('Down Ratings', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></th>

                            </tr>
                            </thead>
                            <tbody id="tableBody">
                            <?php
                            if (is_array($ai_chatbot_chatbotMessageDetails)) {
                                // Calculate the date 7 days ago
                                $ai_chatbot_seven_days_ago = (new DateTime())->modify('-7 days')->format('Y-m-d');
                                foreach ($ai_chatbot_chatbotMessageDetails as $ai_chatbot_detail) {
                                    $ai_chatbot_message_date = DateTime::createFromFormat('m/d/Y h:i:s A', $ai_chatbot_detail['Date'])->format('Y-m-d');
                                    if ($ai_chatbot_message_date >= $ai_chatbot_seven_days_ago) {
                                        ?>
                                        <tr>
                            <td><?php esc_html_e($ai_chatbot_detail['Date'], 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                            <td><?php esc_html_e($ai_chatbot_detail['TotalMessagesAnswered'], 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                            <td><?php esc_html_e($ai_chatbot_detail['SuccessfulAnswers'], 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                            <td><?php esc_html_e($ai_chatbot_detail['NotFoundAnswers'], 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                            <td><?php esc_html_e($ai_chatbot_detail['AnswerRatings'], 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></td>
                            </tr>
                                        <?php
                                     }
                                }
                            }else {
                                if($ai_chatbot_chatbotMessageDetails == null){?>
                                    <div class="container">
                                        <div class="card ai_chatbot_card">
                                            <h4><?php echo esc_html__('No activity till now', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); ?></h4>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>

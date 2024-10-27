<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.robofy.ai
 * @since      1.0.0
 *
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/public
 * @author     Robofy <hi@robofy.ai>
 */
class Robofy_Ai_Chatbot_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        add_action( 'wp_footer', array( $this, 'Robofy_Ai_Chatbot_addchatbottofooter') );
	}
    function Robofy_Ai_Chatbot_updatefooteradmin ($default ) {

        $ai_chatbot_post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_STRING);
        $ai_chatbot_post_type = isset($ai_chatbot_post_type) ? sanitize_text_field($ai_chatbot_post_type) : '';
        if ( ! $ai_chatbot_post_type ) {
            $ai_chatbot_post_type = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
            $ai_chatbot_post_type = get_post_type($ai_chatbot_post_type);
        }
        $ai_chatbot_valid_pages = array( "aichatbot_dashboard");
        $ai_chatbot_page        = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
        $ai_chatbot_url =esc_url("https://www.robofy.ai/");
        if ( in_array( $ai_chatbot_page, $ai_chatbot_valid_pages ) ) {
            echo ' ' . esc_html__('by', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . ' <a href="' . esc_url($ai_chatbot_url) . '" target="_blank">Robofy</a>' . esc_attr(ROBOFY_AI_CHATBOT_VERSION);

        }
    }

    public function Robofy_Ai_Chatbot_addchatbottofooter()
    {
        // Get the script from the plugin's settings
        $ai_chatbot_ispublish = get_option('ai_chatbot_is_public');
        if ($ai_chatbot_ispublish == 1) {
            $ai_chatbot_data = get_option('ai_chatbot_get_script');
            $ai_chatbot_data = stripslashes($ai_chatbot_data);
            $ai_chatbot_allowed_tags = array(
                'script' => array(
                    'async' => true,
                    'id' => true,
                    'src' => true,
                    'dataset' => true
                )
            );
            echo wp_kses($ai_chatbot_data, $ai_chatbot_allowed_tags);
        }
    }
}

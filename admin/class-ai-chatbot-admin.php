<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.robofy.ai
 * @since      1.0.0
 *
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/admin
 * @author     Robofy <hi@robofy.ai>
 */
define('ROBOFY_AI_CHATBOT_FILE_PATH', plugin_dir_url( __FILE__ ));  // Define Plugin Directory URL

class Robofy_Ai_Chatbot_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        $ai_chatbot_valid_pages = array( "aichatbot_dashboard","ai-chatbot-content","ai-chatbot-admin-question","ai-chatbot-admin-rating");
        $ai_chatbot_page        = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
        if ( in_array( $ai_chatbot_page, $ai_chatbot_valid_pages ) ) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ai-chatbot-admin.css', array(), $this->version, 'all');
            wp_enqueue_style('ai_chatbot-bootstrap-min',ROBOFY_AI_CHATBOT_FILE_PATH . 'css/bootstrap.min.css', array());

        }

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        $ai_chatbot_valid_pages = array("aichatbot_dashboard", "ai-chatbot-content", "ai-chatbot-admin-question");
        $ai_chatbot_page        = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
        if ( in_array( $ai_chatbot_page, $ai_chatbot_valid_pages ) ) {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ai-chatbot-admin.js', array('jquery'), $this->version, false);
            wp_enqueue_script('ai_chatbot-bootstrap-bundle-min', ROBOFY_AI_CHATBOT_FILE_PATH . 'js/bootstrap.bundle.min.js');
            wp_enqueue_script('ai_chatbot-bootstrap-min', ROBOFY_AI_CHATBOT_FILE_PATH . 'js/bootstrap.min.js');
            wp_enqueue_script('jquery');
        }
	}
    public function Robofy_Ai_Chatbot_plugin_menu() {
        add_menu_page( "AI Chatbot", "AI Chatbot", "manage_options", "aichatbot_dashboard", array($this, "Robofy_Ai_Chatbot_dashboard_menu"), plugin_dir_url(__FILE__) . 'images/aichatbot-new-logo.png', "90" );
        add_submenu_page( "aichatbot_dashboard", esc_html("Dashboard", 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ), esc_html("Dashboard", 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ), "manage_options", "aichatbot_dashboard", array($this, "Robofy_Ai_Chatbot_dashboard_menu") );
        // Adding Settings submenu
        add_submenu_page(
            "aichatbot_dashboard",
            "Add Content",
            "Add Content",
            "manage_options",
            "ai-chatbot-content",
            array($this, "Robofy_Ai_Chatbot_admin_content")
        );

        // Adding Quick Question submenu
        add_submenu_page(
            "aichatbot_dashboard",
            "FAQ Builder",
            "FAQ Builder",
            "manage_options",
            "ai-chatbot-admin-question",
            array($this, "Robofy_Ai_Chatbot_admin_question")
        );
        // Adding Rating submenu
        add_submenu_page(
            "aichatbot_dashboard",
            "Down Ratings",
            "Down Ratings",
            "manage_options",
            "ai-chatbot-admin-rating",
            array($this, "Robofy_Ai_Chatbot_admin_rating")
        );
    }
    public function Robofy_Ai_Chatbot_dashboard_menu() {
        ob_start(); // started buffer
        if (!get_option('ai_chatbot_adminsettings')) {
            include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-email.php");
        }else {
            $ai_chatbot_data = get_option('ai_chatbot_adminsettings');
            $ai_chatbot_data = json_decode($ai_chatbot_data);
            $ai_chatbot_data = sanitize_option("ai_chatbot_adminsettings", $ai_chatbot_data);
            if ($ai_chatbot_data != "") {
                $ai_chatbot_email = $ai_chatbot_data->ai_chatbot_email;
                $ai_chatbot_username = $ai_chatbot_data->ai_chatbot_username;
                $ai_chatbot_password = $ai_chatbot_data->ai_chatbot_password;
                $ai_chatbot_default_siteurl = $ai_chatbot_data->ai_chatbot_default_siteurl;
                $ai_chatbot_cron_time = $ai_chatbot_data->ai_chatbot_cron_time;
                $ai_chatbot_accountid = $ai_chatbot_data->ai_chatbot_accountid;
                $ai_chatbot_websiteid = $ai_chatbot_data->ai_chatbot_websiteid;
            } else {
                $ai_chatbot_email = "";
                $ai_chatbot_username = "";
                $ai_chatbot_password = "";
                $ai_chatbot_default_siteurl = "";
                $ai_chatbot_cron_time = "";
                $ai_chatbot_accountid = "";
                $ai_chatbot_websiteid = "";
            }
            if (($ai_chatbot_email === "") || ($ai_chatbot_username === "") || ($ai_chatbot_password === "")) {
                include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-email.php");
            }else if(($ai_chatbot_default_siteurl==="")||($ai_chatbot_websiteid==="")){
                include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-site.php");
            }
            else{

                if(get_option("ai_chatbot_question_action")){
                    include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-editquestion.php");
                }else if(get_option("ai_chatbot_rating_action")){
                    include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-editrating.php");
                }else {
                    include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-header.php");
                }
            }
        }

        $Robofy_Ai_Chatbot_Click_To_Chat_view = ob_get_contents(); // reading content
        ob_end_clean(); // closing and cleaning buffer
        echo wp_kses_post( $Robofy_Ai_Chatbot_Click_To_Chat_view );
    }
    public function Robofy_Ai_Chatbot_admin_content() {
        // Settings menu page content
        include plugin_dir_path(__FILE__).'partials/' . 'ai-chatbot-admin-content.php';
    }

    public function Robofy_Ai_Chatbot_admin_question() {
        // FAQ menu page content

        if(get_option("ai_chatbot_question_action")){
            include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-editquestion.php");
        }else{
            include plugin_dir_path(__FILE__).'partials/' .'ai-chatbot-admin-question.php';
        }
    }
    public function Robofy_Ai_Chatbot_admin_rating(){
        if(get_option("ai_chatbot_rating_action")){
            include_once(ROBOFY_AI_CHATBOT_PATH . "admin/partials/ai-chatbot-admin-editrating.php");
        }else{
            include plugin_dir_path(__FILE__).'partials/' .'ai-chatbot-admin-rating.php';
        }

    }
    function Robofy_Ai_Chatbot_updatefooteradmin ($default ) {

        $ai_chatbot_post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_STRING);
        $post_type = isset($ai_chatbot_post_type) ? sanitize_text_field($ai_chatbot_post_type) : '';
        if ( ! $post_type ) {
            $ai_chatbot_post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
            $post_type = get_post_type($ai_chatbot_post_id);
        }
        $aichatbot_valid_pages = array( "ai_chatbot_dashboard");
        $aichatbot_page        = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
        $ai_chatbot_url =esc_url("https://www.robofy.ai/");
        if ( in_array( $aichatbot_page, $aichatbot_valid_pages ) ) {
            echo ' ' . esc_html__('by', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt') . ' <a href="' . esc_url($ai_chatbot_url) . '" target="_blank">Robofy</a>' . esc_attr(ROBOFY_AI_CHATBOT_VERSION);

        }
    }
}

<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Fired during plugin activation
 *
 * @link       https://www.robofy.ai
 * @since      1.0.0
 *
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/includes
 * @author     Robofy <hi@robofy.ai>
 */
class Robofy_Ai_Chatbot_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        update_option('ai_chatbot_current_page', "botdata");
        $ai_chatbot_update_option_arr = array('ai_chatbot_selected_ids' => "");
        $ai_chatbot_result = update_option('ai_chatbot_botdata_ids', wp_json_encode($ai_chatbot_update_option_arr));

        // Creating table for bot data
        global $wpdb;
        $ai_chatbot_table_prefix = $wpdb->prefix;
        $ai_chatbot_table = 'ai_chatbot_botdata';
        $ai_chatbot_botdata_table = $ai_chatbot_table_prefix . "$ai_chatbot_table";
        $ai_chatbot_charset_collate = $wpdb->get_charset_collate();
        $ai_chatbot_db_result1 = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $ai_chatbot_botdata_table));
        if (strtolower($ai_chatbot_db_result1) !== strtolower($ai_chatbot_botdata_table)) {
            $ai_chatbot_tbl1 = "CREATE TABLE $ai_chatbot_botdata_table (
                `ai_chatbot_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `ai_chatbot_doc_id` INT(11),
                `ai_chatbot_type` VARCHAR(1000),
                `ai_chatbot_title` VARCHAR(1000),
                `ai_chatbot_url` VARCHAR(1000),
                `ai_chatbot_description_html` LONGTEXT,
                `ai_chatbot_tags` TEXT,
                `ai_chatbot_category` TEXT,
                `ai_chatbot_meta_title` VARCHAR(2000),
                `ai_chatbot_meta_description` TEXT,
                `ai_chatbot_DOM` DATETIME NOT NULL default '0000-00-00 00:00:00',
                `ai_chatbot_sent_date` DATETIME NOT NULL default '0000-00-00 00:00:00',
                `ai_chatbot_is_sent` TINYINT(1) NOT NULL DEFAULT 0,
                `ai_chatbot_api_request_json` LONGTEXT,
                `ai_chatbot_api_response_json` LONGTEXT
                ) $ai_chatbot_charset_collate";
            $wpdb->query($ai_chatbot_tbl1);
        }
	}

}

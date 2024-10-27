<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.robofy.ai
 * @since             1.0.0
 * @package           Ai_Chatbot
 *
 * @wordpress-plugin
 * Plugin Name:       AI Chatbot, Live Chat, & Lead Generation for WordPress using ChatGPT
 




Best Regards,
Team Robofy
* Plugin URI:        https://www.robofy.ai/wordpress-ai-chatbot
 * Description:       AI-powered chatbot reads your website content and creates a chatbot that can interact with your website visitors in a conversational manner. The chatbot can automatically answer questions, provide support, and make recommendations based on your website content.
 * Version:           2.0.0
 * Author:            Robofy
 * Author URI:        https://www.robofy.ai
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       'ai-chatbot-live-chat-for-wordpress-using-chatgpt'
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ROBOFY_AI_CHATBOT_VERSION', '2.0.0' );
define('ROBOFY_AI_CHATBOT_FILE', __FILE__); // Define Plugin FILE
define('ROBOFY_AI_CHATBOT_URL', plugins_url('/', __FILE__));  // Define Plugin URL
define('ROBOFY_AI_CHATBOT_PATH', plugin_dir_path(__FILE__));  // Define Plugin Directory Path
define('ROBOFY_AI_CHATBOT_DIR', plugin_dir_url(__DIR__));  // Define Plugin Directory URL
define('ROBOFY_AI_CHATBOT_DOMAIN', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' ); // Define Text Domain
define('ROBOFY_AI_CC_FLAG', false ); // Define Text Domain

//Robofy links
define('ai_chatbot_logo',ROBOFY_AI_CHATBOT_DIR.'ai-chatbot-live-chat-for-wordpress-using-chatgpt'. esc_url('/admin/images/aichatbot-LogoNew-black.png'));
define('ai_chatbot_blog_link',esc_url('https://www.robofy.ai/blog/how-to-get-javascript-code-for-robofy-chatbot/'));
define('ai_chatbot_site_link',esc_url('https://www.robofy.ai'));

if (! defined('ROBOFY_AI_CHATBOT_PLUGIN_URL') ) {
    define('ROBOFY_AI_CHATBOT_PLUGIN_URL', plugin_dir_url(__FILE__)); // Define plugin directory URL
}
global $allowedposttags;
$allowed_atts = array('align' => array(),'target'=>array(), 'class' => array(), 'id' => array(),'disabled'=>array(), 'dir' => array(), 'lang' => array(), 'style' => array(), 'xml:lang' => array(), 'src' => array(), 'alt' => array(), 'name' => array(), 'value' => array(), 'type' => array(),'height'=>array(), 'for' => array(), 'form' => array(), 'readonly' => array(),'rows'=>array(), 'required' => array(),'onclick' => array(), 'autocomplete' => array(),'oninput'=>array(), 'placeholder' => array(), 'maxlength' => array(), 'method' => array(),'selected'=>array(), 'action' => array(),'title'=>array(),'data-toggle'=>array(), 'checked' => array(),'href'=>array());
$allowedposttags['strong'] = $allowed_atts;
$allowedposttags['small'] = $allowed_atts;
$allowedposttags['span'] = $allowed_atts;
$allowedposttags['abbr'] = $allowed_atts;
$allowedposttags['sup'] = $allowed_atts;
$allowedposttags['form'] = $allowed_atts;
$allowedposttags['button'] = $allowed_atts;
$allowedposttags['label'] = $allowed_atts;
$allowedposttags['div'] = $allowed_atts;
$allowedposttags['img'] = $allowed_atts;
$allowedposttags['input'] = $allowed_atts;
$allowedposttags['textarea'] = $allowed_atts;
$allowedposttags['h1'] = $allowed_atts;
$allowedposttags['h2'] = $allowed_atts;
$allowedposttags['h3'] = $allowed_atts;
$allowedposttags['h4'] = $allowed_atts;
$allowedposttags['h5'] = $allowed_atts;
$allowedposttags['h6'] = $allowed_atts;
$allowedposttags['ol'] = $allowed_atts;
$allowedposttags['ul'] = $allowed_atts;
$allowedposttags['li'] = $allowed_atts;
$allowedposttags['em'] = $allowed_atts;
$allowedposttags['p'] = $allowed_atts;
$allowedposttags['a'] = $allowed_atts;
$allowedposttags['script'] = $allowed_atts;
$allowedposttags['b'] = $allowed_atts;
$allowedposttags['u'] = $allowed_atts;
$allowedposttags['table'] = $allowed_atts;
$allowedposttags['select'] = $allowed_atts;
$allowedposttags['option'] = $allowed_atts;
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ai-chatbot-activator.php
 */
function robofy_ai_chatbot_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-chatbot-activator.php';
	Robofy_Ai_Chatbot_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ai-chatbot-deactivator.php
 */
function robofy_ai_chatbot_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-chatbot-deactivator.php';
    Robofy_Ai_Chatbot_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'robofy_ai_chatbot_activate' );
register_deactivation_hook( __FILE__, 'robofy_ai_chatbot_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ai-chatbot.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function robofy_ai_chatbot_run() {

	$plugin = new Robofy_Ai_Chatbot();
	$plugin->run();

}
robofy_ai_chatbot_run();

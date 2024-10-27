<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.robofy.ai
 * @since      1.0.0
 *
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ai_Chatbot
 * @subpackage Ai_Chatbot/includes
 * @author     Robofy <hi@robofy.ai>
 */
class Robofy_Ai_Chatbot{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ai_Chatbot_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'AI_CHATBOT_VERSION' ) ) {
			$this->version = ROBOFY_AI_CHATBOT_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'ai-chatbot';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ai_Chatbot_Loader. Orchestrates the hooks of the plugin.
	 * - Ai_Chatbot_i18n. Defines internationalization functionality.
	 * - Ai_Chatbot_Admin. Defines all hooks for the admin area.
	 * - Ai_Chatbot_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ai-chatbot-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ai-chatbot-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ai-chatbot-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ai-chatbot-public.php';

		$this->loader = new Robofy_Ai_Chatbot_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ai_Chatbot_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Robofy_Ai_Chatbot_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Robofy_Ai_Chatbot_Admin( $this->get_plugin_name(), $this->get_version() );
        // Define the cron schedule to run the send_data function.
        add_filter('cron_schedules', array($this, 'Robofy_Ai_Chatbot_define_cron_schedule'));

        // Schedule the send_data function to run periodically.
        $this->Robofy_Ai_Chatbot_schedule_cron_job();

        // Hook the send_data function to the cron job.
        add_action('Robofy_Ai_Chatbot_send_data_cron_job', array($this, 'Robofy_Ai_Chatbot_send_data'));
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action('admin_menu', $plugin_admin, 'robofy_ai_chatbot_plugin_menu');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Robofy_Ai_Chatbot_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action('admin_footer_text', $plugin_public, 'Robofy_Ai_Chatbot_updatefooteradmin');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ai_Chatbot_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

    /**
     * Schedules the send_data function to run periodically.
     */
    public static function Robofy_Ai_Chatbot_schedule_cron_job()
    {
        if (!wp_next_scheduled('Robofy_Ai_Chatbot_send_data_cron_job')) {
            wp_schedule_event(time(), 'every_ten_seconds', 'Robofy_Ai_Chatbot_send_data_cron_job');
        }
    }
    /**
     * Defines the cron schedule interval.
     *
     * @param array $ai_chatbot_schedules The array of existing schedules.
     *
     * @return array The updated array of schedules.
     */
    public static function Robofy_Ai_Chatbot_define_cron_schedule($ai_chatbot_schedules)
    {
        $ai_chatbot_schedules['every_ten_seconds'] = array(
            'interval' => 5, // seconds
            'display' => __('Every 5 seconds'),
        );
        return $ai_chatbot_schedules;
    }
    /**
     * Retrieve user credentials from the API.
     *
     * @param string $ai_chatbot_email The user's email address.
     *
     * @return array|WP_Error The API response on success or WP_Error on failure.
     */
    public static function Robofy_Ai_Chatbot_get_user_credentials($ai_chatbot_email)
    {
        $ai_chatbot_url = add_query_arg(
            array(
                'Email' => $ai_chatbot_email,
                'source' => 'ai-chatbot',
            ),
            esc_url('http://robofy.ai/svc.asmx/getotpdetails', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt')
        );
        $ai_chatbot_response = wp_remote_get(esc_url_raw($ai_chatbot_url));

        // Check if API response was successful
        if (is_wp_error($ai_chatbot_response) || 200 !== wp_remote_retrieve_response_code($ai_chatbot_response)) {
            // Handle API errors
            $ai_chatbot_error_message = is_wp_error($ai_chatbot_response) ? $ai_chatbot_response->get_error_message() : 'API request failed';
            return new WP_Error('ai_chatbot_get_user_credentials_error', $ai_chatbot_error_message);
        }

        // Update the option with the API response
        update_option('ai_chatbot_getotp', wp_json_encode($ai_chatbot_response));

        return $ai_chatbot_response;
    }
    /**
     * Get account status for Robofy.
     *
     * @param string $ai_chatbot_email The email address of the user.
     *
     * @return array|WP_Error The response object or a WP_Error object if the request fails.
     *
     * @since 1.0.0
     */
    public static function Robofy_Ai_Chatbot_get_accountid($ai_chatbot_otp)
    {
        if (!empty(get_option('ai_chatbot_adminsettings'))) {
            $ai_chatbot_data = get_option('ai_chatbot_adminsettings');
            $ai_chatbot_data = json_decode($ai_chatbot_data);
            $ai_chatbot_data = sanitize_option("ai_chatbot_adminsettings", $ai_chatbot_data);
            if ($ai_chatbot_data != "") {
                $ai_chatbot_username = $ai_chatbot_data->ai_chatbot_username;
                $ai_chatbot_password = $ai_chatbot_data->ai_chatbot_password;
                $ai_chatbot_emailid = $ai_chatbot_data->ai_chatbot_email;
                $ai_chatbot_default_siteurl = $ai_chatbot_data->ai_chatbot_default_siteurl;
                $ai_chatbot_cron_time = $ai_chatbot_data->ai_chatbot_cron_time;
                $ai_chatbot_accountid = $ai_chatbot_data->ai_chatbot_accountid;
                $ai_chatbot_websiteid = $ai_chatbot_data->ai_chatbot_websiteid;
            } else {
                $ai_chatbot_username = "";
                $ai_chatbot_password = "";
                $ai_chatbot_emailid = "";
                $ai_chatbot_default_siteurl = "";
                $ai_chatbot_cron_time = "";
                $ai_chatbot_accountid = "";
                $ai_chatbot_websiteid = "";
            }
            $ai_chatbot_request_url = esc_url('https://www.robofy.ai/svc.asmx/ValidOtpDetails','ai-chatbot-live-chat-for-wordpress-using-chatgpt');
            $ai_chatbot_request_args = array(
                'Email' => $ai_chatbot_emailid,
                'OTP' => $ai_chatbot_otp,
                'source' => 'ai-chatbot'
            );
            $ai_chatbot_request_url = add_query_arg($ai_chatbot_request_args, $ai_chatbot_request_url);

            // Make the API request
            $ai_chatbot_response = wp_remote_get($ai_chatbot_request_url);
            // Check for errors
            if (is_wp_error($ai_chatbot_response)) {
                $ai_chatbot_error_message = $ai_chatbot_response->get_error_message();
                printf(
                    esc_html__( 'Error: %s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'),
                    esc_html( $ai_chatbot_error_message )
                );
            } else {
                // Request was successful
                $ai_chatbot_response_code = wp_remote_retrieve_response_code($ai_chatbot_response);
                $ai_chatbot_response_body = wp_remote_retrieve_body($ai_chatbot_response);
                $ai_chatbot_response_array = json_decode($ai_chatbot_response_body, true);
                $ai_chatbot_result1    = update_option( 'ai_chatbot_otpcheck', wp_json_encode( $ai_chatbot_response_array ) );

                if ($ai_chatbot_response_code == 200) {
                    $ai_chatbot_get_Message = $ai_chatbot_response_array['Message'];
                    $ai_chatbot_get_account_id = $ai_chatbot_response_array['AccountId'];

                    $ai_chatbot_admin_settings = array(
                        "ai_chatbot_username" => $ai_chatbot_username,
                        "ai_chatbot_password" => $ai_chatbot_password,
                        "ai_chatbot_email"    => $ai_chatbot_emailid,
                        'ai_chatbot_default_siteurl' => $ai_chatbot_default_siteurl,
                        'ai_chatbot_cron_time'       => $ai_chatbot_cron_time,
                        'ai_chatbot_accountid'       => $ai_chatbot_get_account_id,
                        'ai_chatbot_websiteid'       => $ai_chatbot_websiteid,
                    );

                    update_option('ai_chatbot_adminsettings', wp_json_encode($ai_chatbot_admin_settings));

                    if ( $ai_chatbot_get_Message == "Success" ) {

                        return "true";
                    }
                } else {
                    printf(
                        esc_html__( 'Unexpected HTTP status: %s', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'),
                        esc_html( $ai_chatbot_response_code )
                    );
                    return "false";
                }
            }
        }

    }
    /**
     * Check the user plan.
     *
     * @since     1.0.0
     */
    public static function Robofy_Ai_Chatbot_get_user_plan($ai_chatbot_otp) {

        // get admin settings
        if ( ! empty( get_option( 'ai_chatbot_adminsettings' ) ) ) {
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
            } else {
                $ai_chatbot_username = "";
                $ai_chatbot_password = "";
                $ai_chatbot_emailid  = "";
                $ai_chatbot_default_siteurl = "";
                $ai_chatbot_cron_time = "";
                $ai_chatbot_accountid="";
                $ai_chatbot_websiteid="";
            }

            if($ai_chatbot_cron_time == ""){
                $ai_chatbot_cron_time="1200";
            }

            $ai_chatbot_base_url  = site_url( $path = '', $scheme = null );

            $ai_chatbot_data_decoded = array(
                "emailId"  => $ai_chatbot_emailid,
                "password" => "" ,
                "username" => "",
                "otp"=> $ai_chatbot_otp,
                "isExtension"=> true,
                "isFromProduct"=> "aichatbot",
                "siteURL"=> $ai_chatbot_base_url
            );

            $ai_chatbot_data         = json_encode( $ai_chatbot_data_decoded );
            $ai_chatbot_url          = esc_url( "https://webapi.robofy.ai/api/UnAuthorized/get-plan", 'ai-chatbot-live-chat-for-wordpress-using-chatgpt' );
            $ai_chatbot_response     = wp_remote_post(
                $ai_chatbot_url,
                array(
                    'method'  => 'POST',
                    'headers' => array(
                        'Content-Type' => 'application/json; charset=utf-8',
                        'WPRequest'    => 'abach34h4h2h11h3h'
                    ),
                    'body'    => $ai_chatbot_data
                )
            );
            $ai_chatbot_result       = update_option( 'ai_chatbot_userplan', wp_json_encode( $ai_chatbot_response ) );

            if ( is_array( $ai_chatbot_response ) && isset( $ai_chatbot_response['body'] ) ) {
                $ai_chatbot_response_obj = json_decode( $ai_chatbot_response['body'] );
                $ai_chatbot_result       = update_option( 'ai_chatbot_userplan', wp_json_encode( $ai_chatbot_response_obj ) );
                if ( ! empty( get_option( 'ai_chatbot_userplan' ) ) ) {
                    $ai_chatbot_data           = get_option( 'ai_chatbot_userplan' );
                    $ai_chatbot_data           = json_decode( $ai_chatbot_data );
                    $ai_chatbot_data           = sanitize_option(  "ai_chatbot_userplan",$ai_chatbot_data );
                    if ($ai_chatbot_data != "") {
                        $ai_chatbot_username = $ai_chatbot_data->userName;
                        $ai_chatbot_password = $ai_chatbot_data->password;
                        $ai_chatbot_otpcheck = $ai_chatbot_data->validateOtpMesaage;
                    } else {
                        $ai_chatbot_username = "";
                        $ai_chatbot_password = "";
                        $ai_chatbot_otpcheck = "";
                    }
                    $ai_chatbot_admin_settings = array(
                        "ai_chatbot_username" => $ai_chatbot_username,
                        "ai_chatbot_password" => $ai_chatbot_password,
                        "ai_chatbot_email"    => $ai_chatbot_emailid,
                        'ai_chatbot_default_siteurl' => $ai_chatbot_default_siteurl,
                        'ai_chatbot_cron_time'       => $ai_chatbot_cron_time,
                        'ai_chatbot_accountid'       => $ai_chatbot_accountid,
                        'ai_chatbot_websiteid'       => $ai_chatbot_websiteid,
                    );

                    $ai_chatbot_result1    = update_option( 'ai_chatbot_adminsettings', wp_json_encode( $ai_chatbot_admin_settings ) );

                }
            }
        }

    }

    /**
     * Retrieves the website ID for a given site URL using the Robofy API.
     *  @since     1.0.0
     * @param string $ai_chatbot_user_site_url The URL of the website.
     * @return bool Whether the API call was successful or not.
     */
    public static function Robofy_Ai_Chatbot_get_user_websiteid($ai_chatbot_user_site_url)
    {
        // Retrieve admin settings from options
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
        $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);

        // Extract required values from admin settings
        $ai_chatbot_username = $ai_chatbot_admin_setting->ai_chatbot_username ?? '';
        $ai_chatbot_password = $ai_chatbot_admin_setting->ai_chatbot_password ?? '';
        $ai_chatbot_emailid = $ai_chatbot_admin_setting->ai_chatbot_email ?? '';
        $ai_chatbot_default_siteurl = $ai_chatbot_admin_setting->ai_chatbot_default_siteurl ?? '';
        $ai_chatbot_cron_time = $ai_chatbot_admin_setting->ai_chatbot_cron_time ?? '';
        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';

        // Sanitize values
        $ai_chatbot_email = sanitize_email($ai_chatbot_emailid,'ai-chatbot-live-chat-for-wordpress-using-chatgpt' );
        $ai_chatbot_user_name = esc_attr($ai_chatbot_username,'ai-chatbot-live-chat-for-wordpress-using-chatgpt' );
        $ai_chatbot_user_password = esc_attr($ai_chatbot_password,'ai-chatbot-live-chat-for-wordpress-using-chatgpt' );
        $ai_chatbot_site_url = esc_url($ai_chatbot_user_site_url,'ai-chatbot-live-chat-for-wordpress-using-chatgpt' );
        $ai_chatbot_url = esc_url('https://api.robofy.ai/v1/add-website-v2', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt');
        $request_body = array(
        'Email' => $ai_chatbot_email,
        'AccountId' => $ai_chatbot_accountid,
        'SiteURL' => $ai_chatbot_site_url,
        'Language' => 'English'
    );

    // Prepare API request arguments
        $ai_chatbot_args = array(
            'body' => $request_body,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'timeout' => 15 // Set timeout if needed
        );

        // Make the request
        $ai_chatbot_response = wp_remote_post($ai_chatbot_url, $ai_chatbot_args);

        update_option('ai_chatbot_add_website', wp_json_encode($ai_chatbot_response));

        // Handle API response
        if (is_wp_error($ai_chatbot_response)) {
            // Handle error
            return false;
        }
      $ai_chatbot_response = json_decode($ai_chatbot_response['body']);
        if (is_wp_error($ai_chatbot_response)) {
            // Handle error
            return false;
        }
     if ($ai_chatbot_response->responseStatusCode === 200 && $ai_chatbot_response->Message === 'success') {
            // Extract and save account ID and website ID from API response
          $ai_chatbot_accountId = $ai_chatbot_response->Data->AccountId;
          $ai_chatbot_websiteId = $ai_chatbot_response->Data->WebsiteId;

            $ai_chatbot_admin_settings = array(
                "ai_chatbot_username" => $ai_chatbot_username,
                "ai_chatbot_password" => $ai_chatbot_password,
                "ai_chatbot_email" => $ai_chatbot_emailid,
                'ai_chatbot_default_siteurl' => $ai_chatbot_default_siteurl,
                'ai_chatbot_cron_time' => $ai_chatbot_cron_time,
                'ai_chatbot_accountid' => $ai_chatbot_accountId,
                'ai_chatbot_websiteid' => $ai_chatbot_websiteId,
            );

            update_option('ai_chatbot_adminsettings', wp_json_encode($ai_chatbot_admin_settings));
            return true;
        } elseif($ai_chatbot_response->responseStatusCode === 201) {
            $ai_chatbot_error_message=$ai_chatbot_response->Message;
            return $ai_chatbot_error_message;
        }else{
            return false;
        }
    }

    public static function Robofy_Ai_Chatbot_get_script()
    {
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
        $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);


        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';

        $ai_chatbot_url = add_query_arg(
            array(
                'accountId' => $ai_chatbot_accountid,
                'websiteId' => $ai_chatbot_websiteid,
            ),
          esc_url('https://api.robofy.ai/v1/bot-ready-status-cartbox', 'ai-chatbot-live-chat-for-wordpress-using-chatgpt')
        );

        $ai_chatbot_response = wp_remote_get(esc_url_raw($ai_chatbot_url));
        update_option('ai_chatbot_test_get_res1', wp_json_encode($ai_chatbot_response));

        // Handle API response
        if (is_wp_error($ai_chatbot_response)) {
            // Handle error
            return false;
        }
        $ai_chatbot_response = json_decode($ai_chatbot_response['body']);

        if ($ai_chatbot_response->responseStatusCode === 200 && $ai_chatbot_response->Message === 'success') {
            $ai_chatbot_script = $ai_chatbot_response->Data->JsScript;
            update_option('ai_chatbot_get_script', addslashes($ai_chatbot_script));
        } elseif($ai_chatbot_response->responseStatusCode === 201) {
            $ai_chatbot_error_message=$ai_chatbot_response->Message;

            return $ai_chatbot_error_message;
        }else{
            return false;
        }


    }
    /**
     * retrive data for dashboard about reply counts.
     *
     * @since     2.0.0
     * @return mixed|WP_Error|void The response from the ai_chatbot API or a WP_Error if an error occurs
     */
    public static function Robofy_Ai_Chatbot_get_dashboard() {
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
        $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);


        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $date = new DateTime();
        $ai_chatbot_end_date = $date->format('Y-m-d H:i:s.v');
        $date = new DateTime('first day of this month');
        $ai_chatbot_start_date= $date->format('Y-m-d');
        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chabtbot_accountid or ai_chabtbot_websiteid not set');
        }
        // API URL and headers
        $ai_chatbot_url =   'https://api.robofy.ai/v1/get-chatbot_dashboard-details';
        $ai_chatbot_url = esc_url($ai_chatbot_url, 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); // Escape the URL

        $ai_chatbot_headers = array(
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
        );

        // API request body
        $ai_chatbot_body = array(
            'AccountId' => $ai_chatbot_accountid,
           'StartDate' =>$ai_chatbot_start_date,
            'EndDate'=> $ai_chatbot_end_date
        );

        // Send API request
        $ai_chatbot_response = wp_remote_post
        (
            $ai_chatbot_url,
            array(
                'headers' => $ai_chatbot_headers,
                'body' => wp_json_encode($ai_chatbot_body),
            )
        );


        // Check for request errors
        if (is_wp_error($ai_chatbot_response)) {
            return 'API request failed. Error: ' . $ai_chatbot_response->get_error_message();
        }

        // Check API response code
        $ai_chatbot_response_code = wp_remote_retrieve_response_code($ai_chatbot_response);
        if ($ai_chatbot_response_code !== 200) {
            return 'API request failed with status code: ' . $ai_chatbot_response_code;
        }

        // Retrieve API response body
        $ai_chatbot_data = wp_remote_retrieve_body($ai_chatbot_response);
        $ai_chatbot_decoded_data = json_decode($ai_chatbot_data, true);

        // Check if API response could be decoded
        if (!$ai_chatbot_decoded_data) {
            return 'API response could not be decoded.';
        }

        return $ai_chatbot_decoded_data;


    }
    /**
     * Formats the provided date string into a specific format.
     *
     * @since     2.0.0
     * @param string $ai_chatbot_dateString The date string to be formatted.
     * @return string The formatted date string.
     */
    public static function ai_chatbot_formatDate_rating($ai_chatbot_dateString) {
        // Validate and sanitize the date string
        $ai_chatbot_dateString = sanitize_text_field($ai_chatbot_dateString);

        // Check if the date string is empty or invalid
        if (empty($ai_chatbot_dateString)) {
            return ''; // Or handle the error accordingly
        }

        try {
            $ai_chatbot_date = new DateTime($ai_chatbot_dateString);
            return $ai_chatbot_date->format('d M Y'); // Change the format as per your requirements
        } catch (Exception $e) {
            return ''; // Or handle the exception accordingly
        }
    }
    /**
     * Returns the chatbot's ready status, including the JS script to be embedded on the website.
     *
     * @since     2.0.0
     * @return string|false The chatbot's JS script or false if an error occurred.
     */
    public static function Robofy_Ai_Chatbot_get_botready_status(){
        // Retrieve admin settings from options
        $ai_chatbot_admin_settings = get_option('ai_chatbot_adminsettings');

        if (!$ai_chatbot_admin_settings) {
            return false;
        }

        $ai_chatbot_admin_settings = json_decode($ai_chatbot_admin_settings);
        $ai_chatbot_admin_settings = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_settings);


        if (!$ai_chatbot_admin_settings) {
            return false;
        }

        $ai_chatbot_account_id = $ai_chatbot_admin_settings->ai_chatbot_accountid ?? '';
        $ai_chatbot_website_id = $ai_chatbot_admin_settings->ai_chatbot_websiteid ?? '';

        if (!$ai_chatbot_account_id || !$ai_chatbot_website_id) {
            return false;
        }

        $ai_chatbot_url = "https://api.robofy.ai/v1/bot-ready-status?accountId={$ai_chatbot_account_id}&websiteId={$ai_chatbot_website_id}";

        $ai_chatbot_response = wp_remote_get($ai_chatbot_url, array(
            'headers' => array(
                'accept' => 'application/json',
            ),
        ));

        if (is_wp_error($ai_chatbot_response)) {
            return false;
        }

        $ai_chatbot_body = wp_remote_retrieve_body($ai_chatbot_response);

        if (!$ai_chatbot_body) {
            return false;
        }

        $ai_chatbot_body_data = json_decode($ai_chatbot_body);
        return $ai_chatbot_body_data;

    }

    /**
     *
     * @since     2.0.0
     * Check user credit from the ai_chatbot API
     * @return mixed|WP_Error|void The response from the ai_chatbot API or a WP_Error if an error occurs
     */
    public static function Robofy_Ai_Chatbot_check_user_credit()
    {
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        if (!$ai_chatbot_admin_setting) {
            return new WP_Error('ai_chatbot_error', 'Unable to retrieve ai_chatbot_adminsettings option');
        }

        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
        if (!$ai_chatbot_admin_setting) {
            return new WP_Error('ai_chatbot_error', 'Unable to decode ai_chatbot_adminsettings option');
        }

        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }

        $ai_chatbot_url = 'https://api.robofy.ai/v1/get-chatbot-plan?accountId=' . urlencode($ai_chatbot_accountid) . '&email=' . urlencode($ai_chatbot_websiteid);
        $ai_chatbot_response = wp_remote_get($ai_chatbot_url, array(
            'headers' => array(
                'accept' => 'application/json',
            ),
        ));

        if (is_wp_error($ai_chatbot_response)) {
            return new WP_Error('ai_chatbot_error', 'Unable to retrieve data from ai_chatbot API');
        }

        $ai_chatbot_body = wp_remote_retrieve_body($ai_chatbot_response);
        $ai_chatbot_data = json_decode($ai_chatbot_body);

        if (!$ai_chatbot_data) {
            return new WP_Error('ai_chatbot_error', 'Unable to decode response from ai_chatbot API');
        }
        return $ai_chatbot_data;
    }

    /**
     * Retrieves API data from ai_chatbot.
     *
     * @since     2.0.0
     * @return mixed|array|WP_Error The decoded API response or an error message.
     */
    public static function Robofy_Ai_Chatbot_get_api_data()
    {
        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');

        $ai_chatbot_admin_settings = json_decode($ai_chatbot_admin_setting);

        $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_settings);

        // Extract account ID and website ID
        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';

        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }

        // API URL and headers
        $ai_chatbot_url =   'https://api.robofy.ai/v1/get-url-content-links';
        $ai_chatbot_url = esc_url($ai_chatbot_url, 'ai-chatbot-live-chat-for-wordpress-using-chatgpt'); // Escape the URL

        $ai_chatbot_headers = array(
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
        );

        // API request body
        $ai_chatbot_body = array(
            'AccountId' => $ai_chatbot_accountid,
            'WebsiteId' => $ai_chatbot_websiteid,
        );

        // Send API request
        $ai_chatbot_response = wp_remote_post(
            $ai_chatbot_url,
            array(
                'headers' => $ai_chatbot_headers,
                'body' => wp_json_encode($ai_chatbot_body),
            )
        );

        // Check for request errors
        if (is_wp_error($ai_chatbot_response)) {
            return 'API request failed. Error: ' . $ai_chatbot_response->get_error_message();
        }

        // Check API response code
        $ai_chatbot_response_code = wp_remote_retrieve_response_code($ai_chatbot_response);
        if ($ai_chatbot_response_code !== 200) {
            return 'API request failed with status code: ' . $ai_chatbot_response_code;
        }

        // Retrieve API response body
        $ai_chatbot_data = wp_remote_retrieve_body($ai_chatbot_response);
        $ai_chatbot_decoded_data = json_decode($ai_chatbot_data, true);

        // Check if API response could be decoded
        if (!$ai_chatbot_decoded_data) {
            return 'API response could not be decoded.';
        }

        return $ai_chatbot_decoded_data;
    }
    /**
     * Store bot data for pages, posts, and products.
     *
     * @param array $ai_chatbot_page_ids Array of page IDs to get bot data for.
     * @param array $ai_chatbot_post_ids Array of post IDs to get bot data for.
     * @param array $ai_chatbot_product_ids Array of product IDs to get bot data for.
     *
     * @return bool  True on success, false on failure.
     *
     * @since 2.0.0
     */
    public static function Robofy_Ai_Chatbot_store_botdata($ai_chatbot_page_ids, $ai_chatbot_post_ids, $ai_chatbot_product_ids)
    {
        global $wpdb;

        $ai_chatbot_botdata_pages = array_map('Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_botdata_pages', $ai_chatbot_page_ids);
        $ai_chatbot_botdata_posts = array_map('Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_botdata_posts', $ai_chatbot_post_ids);
        $ai_chatbot_botdata_products = array_map('Robofy_Ai_Chatbot::Robofy_Ai_Chatbot_get_botdata_products', $ai_chatbot_product_ids);

        $ai_chatbot_table_prefix = $wpdb->prefix;
        $ai_chatbot_table = 'ai_chatbot_botdata';
        $ai_chatbot_botdata_table = $ai_chatbot_table_prefix . "$ai_chatbot_table";

        $ai_chatbot_botdata = array_merge($ai_chatbot_botdata_pages, $ai_chatbot_botdata_posts, $ai_chatbot_botdata_products);

        try {
            $wpdb->query('START TRANSACTION');

            foreach ($ai_chatbot_botdata as $ai_chatbot_data) {
                // Check if an entry with the same ai_chatbot_doc_id and dom already exists
                $ai_chatbot_existing_entry = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT COUNT(*) FROM $ai_chatbot_botdata_table WHERE ai_chatbot_doc_id = %s AND ai_chatbot_DOM = %s",
                        $ai_chatbot_data['ai_chatbot_doc_id'],
                        $ai_chatbot_data['ai_chatbot_DOM']
                    )
                );

                if ($ai_chatbot_existing_entry == 0) {
                    $wpdb->insert($ai_chatbot_botdata_table, $ai_chatbot_data);
                }
            }

            $wpdb->query('COMMIT');
            return true;
        } catch (Exception $e) {
            $wpdb->query('ROLLBACK');
            return false;
        }
    }
    /**
     * Retrieves chatbot data for a page.
     *
     * @since     2.0.0
     * @param int $ai_chatbot_page_id ID of the page to retrieve data for.
     * @return array|false Chatbot data array on success, false on failure.
     */
    public static function Robofy_Ai_Chatbot_get_botdata_pages($ai_chatbot_page_id)
    {
        // Retrieve the post object for the given page ID.
        $ai_chatbot_page = get_post($ai_chatbot_page_id);

        // Bail early if the page does not exist or is not published.
        if (!$ai_chatbot_page || $ai_chatbot_page->post_status !== 'publish') {
            return false;
        }

        // Retrieve tags and categories associated with the page.
        $ai_chatbot_tags = wp_get_post_tags($ai_chatbot_page_id);
        $ai_chatbot_categories = wp_get_post_categories($ai_chatbot_page_id);

        // Convert tags and categories to arrays of names.
        $ai_chatbot_tags_arr = array_map(function ($ai_chatbot_tag) {
            return $ai_chatbot_tag->name;
        }, $ai_chatbot_tags);

        $ai_chatbot_categories_arr = array_map(function ($ai_chatbot_category_id) {
            $ai_chatbot_category = get_term_by('id', $ai_chatbot_category_id, 'category');
            return $ai_chatbot_category ? $ai_chatbot_category->name : null;
        }, $ai_chatbot_categories);
        $ai_chatbot_last_modified = get_post_modified_time('U', false, $ai_chatbot_page_id);
        $ai_chatbot_last_modified = date('Y-m-d H:i:s', $ai_chatbot_last_modified);

        $ai_chatbot_url[] = get_permalink($ai_chatbot_page_id);

        // Retrieve additional metadata for the page.
        $ai_chatbot_meta_title = get_post_meta($ai_chatbot_page_id, '_yoast_wpseo_title', true);
        $ai_chatbot_meta_description = get_post_meta($ai_chatbot_page_id, '_yoast_wpseo_metadesc', true);

        // Build an array of chatbot data for the page.
        $ai_chatbot_data = array(
            'ai_chatbot_doc_id' => $ai_chatbot_page_id,
            'ai_chatbot_type' => 'page',
            'ai_chatbot_title' => $ai_chatbot_page->post_title,
            'ai_chatbot_url' => serialize($ai_chatbot_url),
            'ai_chatbot_description_html' => $ai_chatbot_page->post_content,
            'ai_chatbot_tags' => serialize($ai_chatbot_tags_arr),
            'ai_chatbot_category' => serialize(array_filter($ai_chatbot_categories_arr)),
            'ai_chatbot_meta_title' => $ai_chatbot_meta_title,
            'ai_chatbot_meta_description' => $ai_chatbot_meta_description,
            'ai_chatbot_DOM' => $ai_chatbot_last_modified,
        );

        return $ai_chatbot_data;
    }

    /**
     * Retrieves data for a single post.
     *
     * @param int $ai_chatbot_post_id The post ID.
     *@since     2.0.0
     * @return array|null The post data, or null if the post doesn't exist.
     */
    public static function Robofy_Ai_Chatbot_get_botdata_posts($ai_chatbot_post_id)
    {
        // Retrieve the post object.
        $ai_chatbot_post = get_post($ai_chatbot_post_id);

        // Return null if the post doesn't exist.
        if (!$ai_chatbot_post) {
            return null;
        }

        // Retrieve the post tags and categories.
        $ai_chatbot_tags = wp_get_post_tags($ai_chatbot_post_id);
        $ai_chatbot_categories = wp_get_post_categories($ai_chatbot_post_id);

        // Extract tag and category names.
        $ai_chatbot_tags_arr = array_map(function ($ai_chatbot_tag) {
            return $ai_chatbot_tag->name;
        }, $ai_chatbot_tags);
        $ai_chatbot_categories_arr = array_map(function ($ai_chatbot_category_id) {
            $ai_chatbot_category = get_term_by('id', $ai_chatbot_category_id, 'category');
            return $ai_chatbot_category ? $ai_chatbot_category->name : null;
        }, $ai_chatbot_categories);
        $ai_chatbot_categories_arr = array_filter($ai_chatbot_categories_arr);

        // Format dates.
        $ai_chatbot_url[] = get_permalink($ai_chatbot_post_id);

        $ai_chatbot_last_modified = get_post_modified_time('U', false, $ai_chatbot_post_id);
        $ai_chatbot_last_modified = date('Y-m-d H:i:s', $ai_chatbot_last_modified);

        // Build the post data array.
        $ai_chatbot_data = array(
            'ai_chatbot_doc_id' => $ai_chatbot_post_id,
            'ai_chatbot_type' => 'post',
            'ai_chatbot_title' => $ai_chatbot_post->post_title,
            'ai_chatbot_url' => serialize($ai_chatbot_url),
            'ai_chatbot_description_html' => $ai_chatbot_post->post_content,
            'ai_chatbot_tags' => serialize($ai_chatbot_tags_arr),
            'ai_chatbot_category' => serialize($ai_chatbot_categories_arr),
            'ai_chatbot_meta_title' => get_post_meta($ai_chatbot_post_id, '_yoast_wpseo_title', true),
            'ai_chatbot_meta_description' => get_post_meta($ai_chatbot_post_id, '_yoast_wpseo_metadesc', true),
            'ai_chatbot_DOM' => $ai_chatbot_last_modified,
        );

        return $ai_chatbot_data;
    }

    /**
     * Returns the chatbot data for a product.
     * @since     2.0.0
     * @param int $ai_chatbot_product_id The ID of the product.
     * @return array|null The chatbot data or null if the product is not found.
     */
    public static function Robofy_Ai_Chatbot_get_botdata_products(int $ai_chatbot_product_id): ?array
    {
        $ai_chatbot_product = wc_get_product($ai_chatbot_product_id);
        if (!$ai_chatbot_product) {
            return null;
        }

        // Get the product categories.
        $ai_chatbot_category_names = [];
        $ai_chatbot_category_ids = $ai_chatbot_product->get_category_ids();
        foreach ($ai_chatbot_category_ids as $ai_chatbot_category_id) {
            $ai_chatbot_category = get_term_by('id', $ai_chatbot_category_id, 'product_cat');
            if ($ai_chatbot_category) {
                $ai_chatbot_category_names[] = $ai_chatbot_category->name;
            }
        }

        // Get the product tags.
        $ai_chatbot_tag_names = [];
        $ai_chatbot_tag_ids = $ai_chatbot_product->get_tag_ids();
        foreach ($ai_chatbot_tag_ids as $ai_chatbot_tag_id) {
            $ai_chatbot_tag = get_term_by('id', $ai_chatbot_tag_id, 'product_tag');
            if ($ai_chatbot_tag) {
                $ai_chatbot_tag_names[] = $ai_chatbot_tag->name;
            }
        }
        $ai_chatbot_url[] = get_permalink($ai_chatbot_product_id);
        $ai_chatbot_last_modified = get_post_modified_time('U', false, $ai_chatbot_product_id);

        // Build the chatbot data.
        $ai_chatbot_data = [
            'ai_chatbot_doc_id' => $ai_chatbot_product_id,
            'ai_chatbot_type' => 'product',
            'ai_chatbot_title' => $ai_chatbot_product->get_title(),
            'ai_chatbot_url' => serialize($ai_chatbot_url),
            'ai_chatbot_description_html' => $ai_chatbot_product->get_description(),
            'ai_chatbot_tags' => serialize($ai_chatbot_tag_names),
            'ai_chatbot_category' => serialize($ai_chatbot_category_names),
            'ai_chatbot_meta_title' => get_post_meta($ai_chatbot_product_id, '_yoast_wpseo_title', true),
            'ai_chatbot_meta_description' => get_post_meta($ai_chatbot_product_id, '_yoast_wpseo_metadesc', true),
            'ai_chatbot_DOM' => date('Y-m-d H:i:s', $ai_chatbot_last_modified),
        ];

        return $ai_chatbot_data;
    }
    /**
     * Retrieves API data from ai_chatbot.
     * @since     2.0.0
     * @return mixed|array|WP_Error The decoded API response or an error message.
     */
    public static function Robofy_Ai_Chatbot_get_rating_data()
    {
        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);

        $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);
        // Extract account ID and website ID
        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';

        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }
// Prepare the request body
        $ai_chatbot_request_body = array(
            'AccountId' => $ai_chatbot_accountid,
            'WebsiteId' => $ai_chatbot_websiteid
        );

// Set the request URL
        $ai_chatbot_request_url = 'https://api.robofy.ai/v1/get-chat-message-down-ratings';

// Set the request headers
        $ai_chatbot_request_headers = array(
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        );

// Make the API call
        $ai_chatbot_response = wp_remote_post($ai_chatbot_request_url, array(
            'headers' => $ai_chatbot_request_headers,
            'body' => wp_json_encode($ai_chatbot_request_body)
        ));
// Check for a successful API call
        if (!is_wp_error($ai_chatbot_response) && wp_remote_retrieve_response_code($ai_chatbot_response) === 200) {
            // Get the ai_chatbot_response body
            $ai_chatbot_response_body = wp_remote_retrieve_body($ai_chatbot_response);
            return $ai_chatbot_response_body;
        } else {
            return false;
        }
    }
    /**
     * Formats the provided date string into a specific format.
     *
     * @since     2.0.0
     * @param string $ai_chatbot_dateString The date string to be formatted.
     * @return string The formatted date string.
     */
    public static function Robofy_Ai_Chatbot_formatDate_rating($ai_chatbot_dateString) {
        // Validate and sanitize the date string
        $ai_chatbot_dateString = sanitize_text_field($ai_chatbot_dateString);

        // Check if the date string is empty or invalid
        if (empty($ai_chatbot_dateString)) {
            return ''; // Or handle the error accordingly
        }

        try {
            $ai_chatbot_date = new DateTime($ai_chatbot_dateString);
            return $ai_chatbot_date->format('d M Y'); // Change the format as per your requirements
        } catch (Exception $e) {
            return ''; // Or handle the exception accordingly
        }
    }

    /**
     * Retrieves API data from ai_chatbot.
     *
     * @since     2.0.0
     * @return mixed|array|WP_Error The decoded API response or an error message.
     */
    public static function Robofy_Ai_Chatbot_update_rating_data($ai_chatbot_question,$ai_chatbot_answer){
        $url = 'https://api.robofy.ai/v1/update-down-rate-chat-message';

        // Validate and sanitize input data
        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
       $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);
        // Extract account ID and website ID
        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';



        // Perform additional validation if required
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid) || empty($ai_chatbot_question) || empty($ai_chatbot_answer)) {
            // Handle validation error
            echo 'Invalid input data.';
            return;
        }

        $headers = array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        );

        $data = array(
            'AccountId'  => $ai_chatbot_accountid,
            'WebsiteId'  => $ai_chatbot_websiteid,
            'Question'   => $ai_chatbot_question,
            'Answer'     => $ai_chatbot_answer,
        );
        update_option('ai_chatbot_r_req', wp_json_encode($data));

        $args = array(
            'headers' => $headers,
            'body'    => wp_json_encode($data),
        );

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            // Handle error
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            // Process response
            $response_code    = wp_remote_retrieve_response_code($response);
            $response_message = wp_remote_retrieve_response_message($response);
            $response_body    = wp_remote_retrieve_body($response);

            return $response_body;
        }
    }
    /**
     * Retrieves API data from ai_chatbot.
     *
     * @since     2.0.0
     * @return mixed|array|WP_Error The decoded API response or an error message.
     */
    public static function Robofy_Ai_Chatbot_get_questions_data()
    {
        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);

        $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);

        // Extract account ID and website ID
        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';

        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }

// Prepare the request body
        $ai_chatbot_request_body = array(
            'Id' => '',
            'AccountId' => $ai_chatbot_accountid,
            'WebsiteId' => $ai_chatbot_websiteid,
            'DisplayQuestion' => '',
            'Question' => '',
            'QuestionDate' => '',
            'Answer' => '',
            'Action' => '0'
        );

// Set the request URL
        $ai_chatbot_request_url = 'https://api.robofy.ai/v1/chatbot-faq-crud';

// Set the request headers
        $ai_chatbot_request_headers = array(
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        );

// Make the API call
        $ai_chatbot_response = wp_remote_post($ai_chatbot_request_url, array(
            'headers' => $ai_chatbot_request_headers,
            'body' => wp_json_encode($ai_chatbot_request_body)
        ));

// Check for a successful API call
        if (!is_wp_error($ai_chatbot_response) && wp_remote_retrieve_response_code($ai_chatbot_response) === 200) {
            // Get the response body
            $ai_chatbot_response_body = wp_remote_retrieve_body($ai_chatbot_response);

            return $ai_chatbot_response_body;
        } else {
            return false;
        }
    }
    /**
     * Adds questions data to ai_chatbot API.
     *
     * @since     2.0.0
     * @param string $ai_chatbot_display_que The display question.
     * @param string $ai_chatbot_question The question.
     * @param string $ai_chatbot_ans The answer.
     * @return mixed|array|WP_Error The decoded API response or an error message.
     */
    public static function Robofy_Ai_Chatbot_add_questions_data($ai_chatbot_display_que, $ai_chatbot_question, $ai_chatbot_ans) {
        // Validate and sanitize the input data
        $ai_chatbot_display_que = sanitize_text_field($ai_chatbot_display_que);
        $ai_chatbot_question = sanitize_text_field($ai_chatbot_question);
        $ai_chatbot_ans = sanitize_textarea_field($ai_chatbot_ans);

        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
       $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);

        // Extract account ID and website ID
        $ai_chatbot_accountid = isset($ai_chatbot_admin_setting->ai_chatbot_accountid) ? sanitize_text_field($ai_chatbot_admin_setting->ai_chatbot_accountid) : '';
        $ai_chatbot_websiteid = isset($ai_chatbot_admin_setting->ai_chatbot_websiteid) ? sanitize_text_field($ai_chatbot_admin_setting->ai_chatbot_websiteid) : '';

        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }

        // Prepare the request body
        $ai_chatbot_request_body = array(
            'Id' => '',
            'AccountId' => $ai_chatbot_accountid,
            'WebsiteId' => $ai_chatbot_websiteid,
            'DisplayQuestion' => $ai_chatbot_display_que,
            'Question' => $ai_chatbot_question,
            'QuestionDate' => '',
            'Answer' => $ai_chatbot_ans,
            'Action' => '1'
        );

        // Set the request URL
        $ai_chatbot_request_url = 'https://api.robofy.ai/v1/chatbot-faq-crud';

        // Set the request headers
        $ai_chatbot_request_headers = array(
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        );

        // Make the API call
        $ai_chatbot_response = wp_remote_post($ai_chatbot_request_url, array(
            'headers' => $ai_chatbot_request_headers,
            'body' => wp_json_encode($ai_chatbot_request_body)
        ));

        // Check for a successful API call
        if (!is_wp_error($ai_chatbot_response) && wp_remote_retrieve_response_code($ai_chatbot_response) === 200) {
            // Get the response body
            $ai_chatbot_response_body = wp_remote_retrieve_body($ai_chatbot_response);

            return $ai_chatbot_response_body;
        } else {
            return false;
        }
    }
    /**
     * Edits questions data in ai_chatbot API.
     *
     * @since     2.0.0
     * @param string $ai_chatbot_que_id The question ID.
     * @param string $ai_chatbot_display_que The display question.
     * @param string $ai_chatbot_question The question.
     * @param string $ai_chatbot_ans The answer.
     * @return mixed|array|WP_Error The decoded API response or an error message.
     */
    public static function Robofy_Ai_Chatbot_edit_questions_data($ai_chatbot_que_id, $ai_chatbot_display_que, $ai_chatbot_question, $ai_chatbot_ans) {
        // Validate and sanitize the input data
        $ai_chatbot_que_id = sanitize_text_field($ai_chatbot_que_id);
        $ai_chatbot_display_que = sanitize_text_field($ai_chatbot_display_que);
        $ai_chatbot_question = sanitize_text_field($ai_chatbot_question);
        $ai_chatbot_ans = sanitize_textarea_field($ai_chatbot_ans);

        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);

        // Extract account ID and website ID
        $ai_chatbot_accountid = isset($ai_chatbot_admin_setting->ai_chatbot_accountid) ? sanitize_text_field($ai_chatbot_admin_setting->ai_chatbot_accountid) : '';
        $ai_chatbot_websiteid = isset($ai_chatbot_admin_setting->ai_chatbot_websiteid) ? sanitize_text_field($ai_chatbot_admin_setting->ai_chatbot_websiteid) : '';

        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }

        // Prepare the request body
        $ai_chatbot_request_body = array(
            'Id' => $ai_chatbot_que_id,
            'AccountId' => $ai_chatbot_accountid,
            'WebsiteId' => $ai_chatbot_websiteid,
            'DisplayQuestion' => $ai_chatbot_display_que,
            'Question' => $ai_chatbot_question,
            'QuestionDate' => '',
            'Answer' => $ai_chatbot_ans,
            'Action' => '2'
        );

        // Set the request URL
        $ai_chatbot_request_url = 'https://api.robofy.ai/v1/chatbot-faq-crud';

        // Set the request headers
        $ai_chatbot_request_headers = array(
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        );

        // Make the API call
        $ai_chatbot_response = wp_remote_post($ai_chatbot_request_url, array(
            'headers' => $ai_chatbot_request_headers,
            'body' => wp_json_encode($ai_chatbot_request_body)
        ));

        // Check for a successful API call
        if (!is_wp_error($ai_chatbot_response) && wp_remote_retrieve_response_code($ai_chatbot_response) === 200) {
            // Get the response body
            $ai_chatbot_response_body = wp_remote_retrieve_body($ai_chatbot_response);

            return $ai_chatbot_response_body;
        } else {
            return false;
        }
    }
    /**
     * Edits questions data in ai_chatbot API.
     *
     * @since     2.0.0
     * @param string $ai_chatbot_que_id The question ID.
     * @param string $ai_chatbot_display_que The display question.
     * @param string $ai_chatbot_question The question.
     * @param string $ai_chatbot_ans The answer.
     * @return mixed|array|WP_Error The decoded API response or an error message.
     */
    public static function Robofy_Ai_Chatbot_delete_questions_data($ai_chatbot_que_id, $ai_chatbot_display_que, $ai_chatbot_question, $ai_chatbot_ans) {
        // Validate and sanitize the input data
        $ai_chatbot_que_id = sanitize_text_field($ai_chatbot_que_id);
        $ai_chatbot_display_que = sanitize_text_field($ai_chatbot_display_que);
        $ai_chatbot_question = sanitize_text_field($ai_chatbot_question);
        $ai_chatbot_ans = sanitize_textarea_field($ai_chatbot_ans);

        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
  $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);

        // Extract account ID and website ID
        $ai_chatbot_accountid = isset($ai_chatbot_admin_setting->ai_chatbot_accountid) ? sanitize_text_field($ai_chatbot_admin_setting->ai_chatbot_accountid) : '';
        $ai_chatbot_websiteid = isset($ai_chatbot_admin_setting->ai_chatbot_websiteid) ? sanitize_text_field($ai_chatbot_admin_setting->ai_chatbot_websiteid) : '';

        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }

        // Prepare the request body
        $ai_chatbot_request_body = array(
            'Id' => $ai_chatbot_que_id,
            'AccountId' => $ai_chatbot_accountid,
            'WebsiteId' => $ai_chatbot_websiteid,
            'DisplayQuestion' => $ai_chatbot_display_que,
            'Question' => $ai_chatbot_question,
            'QuestionDate' => '',
            'Answer' => $ai_chatbot_ans,
            'Action' => '3'
        );

        // Set the request URL
        $ai_chatbot_request_url = 'https://api.robofy.ai/v1/chatbot-faq-crud';

        // Set the request headers
        $ai_chatbot_request_headers = array(
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        );

        // Make the API call
        $ai_chatbot_response = wp_remote_post($ai_chatbot_request_url, array(
            'headers' => $ai_chatbot_request_headers,
            'body' => wp_json_encode($ai_chatbot_request_body)
        ));


        // Check for errors
        if (is_wp_error($ai_chatbot_response)) {
            return new WP_Error('ai_chatbot_api_error', 'API request failed: ' . $ai_chatbot_response->get_error_message());
        }


        // Get the response body
        $response_body = wp_remote_retrieve_body($ai_chatbot_response);

        // Decode the JSON response
        $decoded_response = json_decode($response_body, true);

        // Check for a valid response
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('ai_chatbot_api_error', 'Failed to decode API response: ' . json_last_error_msg());
        }

        // Return the responseStatusCode
        return $decoded_response['responseStatusCode'] ?? new WP_Error('ai_chatbot_api_error', 'responseStatusCode not found in API response');

    }

    /**
     * delete url
     *
     *@since     2.0.0
     * @throws Exception
     */
    public static function Robofy_Ai_Chatbot_delete_website_link($ai_chatbot_website_delete_id){
        // Get ai_chatbot admin settings
        $ai_chatbot_admin_setting = get_option('ai_chatbot_adminsettings');
        $ai_chatbot_admin_setting = json_decode($ai_chatbot_admin_setting);
        $ai_chatbot_admin_setting = sanitize_option('ai_chatbot_adminsettings', $ai_chatbot_admin_setting);

        // Extract account ID and website ID
        $ai_chatbot_accountid = $ai_chatbot_admin_setting->ai_chatbot_accountid ?? '';
        $ai_chatbot_websiteid = $ai_chatbot_admin_setting->ai_chatbot_websiteid ?? '';

        // Validate account ID and website ID
        if (empty($ai_chatbot_accountid) || empty($ai_chatbot_websiteid)) {
            return new WP_Error('ai_chatbot_error', 'ai_chatbot_accountid or ai_chatbot_websiteid not set');
        }
        // Construct the API URL
        $api_url = sprintf(
            'https://api.robofy.ai/v1/delete-url?accountId=%s&websiteId=%s&urlId=%s',
            urlencode($ai_chatbot_accountid),
            urlencode($ai_chatbot_websiteid),
            urlencode($ai_chatbot_website_delete_id)
        );

        // Make the API call
        $response = wp_remote_post($api_url, array(
            'method'  => 'POST',
            'headers' => array(
                'Accept' => 'application/json',
            ),
            'body' => ''  // If you need to send any data, add it here
        ));

        // Check for errors
        if (is_wp_error($response)) {
            return new WP_Error('ai_chatbot_api_error', 'API request failed: ' . $response->get_error_message());
        }


        // Get the response body
        $response_body = wp_remote_retrieve_body($response);

        // Decode the JSON response
        $decoded_response = json_decode($response_body, true);

        // Check for a valid response
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('ai_chatbot_api_error', 'Failed to decode API response: ' . json_last_error_msg());
        }

        // Return the responseStatusCode
        return $decoded_response['responseStatusCode'] ?? new WP_Error('ai_chatbot_api_error', 'responseStatusCode not found in API response');



    }

    /**
     * Send data to API.
     *
     * @since     2.0.0
     * @throws Exception
     */
    function Robofy_Ai_Chatbot_send_data()
    {
        $ai_chatbot_result = update_option('ai_chatbot_test_1', "in function");

        // Retrieve plugin settings from the database.
        $ai_chatbot_data = get_option('ai_chatbot_adminsettings');
        if (!$ai_chatbot_data) {
            error_log('User data not found - option: ai_chatbot_adminsettings');
            return;
        }
        $ai_chatbot_data = json_decode($ai_chatbot_data, true);
        if (!$ai_chatbot_data) {
            throw new Exception('Error decoding ai_chatbot_adminsettings JSON data');
        }
        $ai_chatbot_data = array_map('sanitize_text_field', $ai_chatbot_data);

        // Get database connection.
        global $wpdb;

        // Prepare SQL statement to retrieve the next unsent row.
        $ai_chatbot_table_name = $wpdb->prefix . 'ai_chatbot_botdata';
        $ai_chatbot_query = $wpdb->prepare(
            "SELECT * FROM $ai_chatbot_table_name WHERE ai_chatbot_is_sent = %d LIMIT 1",
            0
        );

        // Retrieve the next unsent row from the database.
        $ai_chatbot_row = $wpdb->get_row($ai_chatbot_query);
        if (!$ai_chatbot_row) {
            return;
        }
        $ai_chatbot_result = update_option('ai_chatbot_test_2', $ai_chatbot_row);

        // Build the data payload to send to the API.

        $ai_chatbot_data_ary = array(
            'Email' => $ai_chatbot_data['ai_chatbot_email'],
            'AccountId' => $ai_chatbot_data['ai_chatbot_accountid'],
            'WebsiteId' => $ai_chatbot_data['ai_chatbot_websiteid'],
            'Type' => $ai_chatbot_row->ai_chatbot_type,
            'Title' => $ai_chatbot_row->ai_chatbot_title,
            'URL' => unserialize($ai_chatbot_row->ai_chatbot_url),
            'DescriptionHTML' => $ai_chatbot_row->ai_chatbot_description_html,
            'Tags' => unserialize($ai_chatbot_row->ai_chatbot_tags),
            'Categories' => unserialize($ai_chatbot_row->ai_chatbot_category),
            'Meta_title' => $ai_chatbot_row->ai_chatbot_meta_title,
            'Meta_description' => $ai_chatbot_row->ai_chatbot_meta_description,
            'SourcePath' => "content",
            'ContentType' => $ai_chatbot_row->ai_chatbot_type,
        );
        $ai_chatbot_result = update_option('ai_chatbot_test_3', $ai_chatbot_data_ary);

        // Send data to the API.
        $ai_chatbot_url = 'https://w7n4kezbeg.execute-api.us-west-2.amazonaws.com/Prod';
        $ai_chatbot_headers = array('Content-Type' => 'application/json');
        $ai_chatbot_args = array(
            'headers' => $ai_chatbot_headers,
            'body' => json_encode($ai_chatbot_data_ary),
        );
        $ai_chatbot_response = wp_remote_post($ai_chatbot_url, $ai_chatbot_args);
        $ai_chatbot_result = update_option('ai_chatbot_botsend_last_response', $ai_chatbot_response);

        if (is_wp_error($ai_chatbot_response)) {
            throw new Exception($ai_chatbot_response->get_error_message());
        }
        $ai_chatbot_current_date = current_time('timestamp', true);
        $ai_chatbot_date_string = date('Y-m-d H:i:s', $ai_chatbot_current_date);
        // Update the row with API request, response, and is_sent = 1.
        $wpdb->update(
            $ai_chatbot_table_name,
            array(
                'ai_chatbot_api_request_json' => json_encode($ai_chatbot_data_ary),
                'ai_chatbot_api_response_json' => $ai_chatbot_response['body'],
                'ai_chatbot_is_sent' => 1,
                'ai_chatbot_sent_date' => $ai_chatbot_date_string
            ),
            array('ai_chatbot_id' => $ai_chatbot_row->ai_chatbot_id),
            array('%s', '%s', '%d'),
            array('%d')
        );
    }


}

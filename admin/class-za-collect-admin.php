<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       kjhuer.com
 * @since      1.0.0
 *
 * @package    Za_Collect
 * @subpackage Za_Collect/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Za_Collect
 * @subpackage Za_Collect/admin
 * @author     Kevin J Huer <kjhuer@gmail.com>
 */
class Za_Collect_Admin {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/za-collect-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/za-collect-admin.js', array( 'jquery' ), $this->version, false );

	}
        
        /**
	 * Add options page for zaCollect
	 *
	 * @since    1.0.0
	 */
        public function add_plugin_admin_menu(){
            add_options_page('zaCollect','zaCollect','manage_options',$this->plugin_name,array($this,'display_plugin_setup_page'));
            
        }
        
        public function add_action_links( $links ) {
    /*
    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
    */
            $settings_link = array(
            '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
           );
           return array_merge(  $settings_link, $links );

        }

                /**
         * Render the settings page for this plugin.
         *
         * @since    1.0.0
         */

        public function display_plugin_setup_page() {
            include_once( 'partials/za-collect-admin-display.php' );
        }
        
         public function options_update() {
            register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
         }
        public function validate( $input ){
           $valid = array();
           
           $valid['referral_id'] = (isset($input['referral_id']) && !empty($input['referral_id']) && preg_match( '/^[0-9]{18}$/', $input['referral_id']  ) ) ? sanitize_text_field($input['referral_id']) : '';
           
            if ( empty($valid['referral_id']) && !empty($input['referral_id']) ) { 
                    add_settings_error(
                            'referral_id',                     // Setting title
                            'referral_id_texterror',            // Error ID
                            'Please enter a valid referral ID (18 numbers, no spaces or extra characters)',     // Error message
                            'error'                         // Type of message
                    );
                }
           $valid['buy_button_text'] =(isset($input['buy_button_text']) && !empty($input['buy_button_text'])) ? sanitize_text_field($input['buy_button_text']) : ''; 
           
           
            $valid['accent_color'] = (isset($input['accent_color']) && !empty($input['accent_color'])) ? sanitize_text_field($input['accent_color']) : '';
                
                if ( !empty($valid['accent_color']) && !preg_match( '/^#[a-f0-9]{6}$/i', $valid['accent_color']  ) ) { // if user insert a HEX color with #
                    add_settings_error(
                            'accent_color',                     // Setting title
                            'accent_color_texterror',            // Error ID
                            'Please enter a valid hex value color for accent color',     // Error message
                            'error'                         // Type of message
                    );
                }
                
            $valid['accent_text_color'] = (isset($input['accent_text_color']) && !empty($input['accent_text_color'])) ? sanitize_text_field($input['accent_text_color']) : '';
                
                if ( !empty($valid['accent_text_color']) && !preg_match( '/^#[a-f0-9]{6}$/i', $valid['accent_text_color']  ) ) { // if user insert a HEX color with #
                    add_settings_error(
                            'accent_text_color',                     // Setting title
                            'accent_text_color_texterror',            // Error ID
                            'Please enter a valid hex value color for accent text color',     // Error message
                            'error'                         // Type of message
                    );
                }    
            $valid['new_window'] = (isset($input['new_window']) && !empty($input['new_window'])) ? 1 : 0;    
           
           return $valid;
        }
        
        
        

}

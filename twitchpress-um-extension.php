<?php 
/*
Plugin Name: TwitchPress UM Extension
Version: 1.0.0
Plugin URI: http://twitchpress.wordpress.com
Description: Integrate the Ultimate Member and TwitchPress plugins.
Author: Ryan Bayne
Author URI: http://ryanbayne.wordpress.com
Text Domain: twitchpress-um
Domain Path: /languages
Copyright: © 2017 Ryan Bayne
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 
GPL v3 

This program is free software downloaded from WordPress.org: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. This means
it can be provided for the sole purpose of being developed further
and we do not promise it is ready for any one persons specific needs.
See the GNU General Public License for more details.

See <http://www.gnu.org/licenses/>.


    Planning to create a TwitchPress extension like this one?

    Step 1: Read WordPress.org plugin development guidelines
    https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/

    Step 2: Read the TwitchPress extension development guidelines.
    Full guide coming soon!
    
    
*/

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'Direct script access is not allowed!' );

/**
 * Check if TwitchPress is active, else avoid activation.
 **/
if ( !in_array( 'channel-solution-for-twitch/twitchpress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}

/**
* Check if Ultimate MEmber is active, else avoid activation.
*/
if ( !in_array( 'ultimate-member/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}

/**
 * Required minimums and constants
 */
define( 'TWITCHPRESS_UM_VERSION', '1.0.0' );
define( 'TWITCHPRESS_UM_MIN_PHP_VER', '5.6.0' );
define( 'TWITCHPRESS_UM_MIN_TP_VER', '1.2.6' );
define( 'TWITCHPRESS_UM_MAIN_FILE', __FILE__ );
define( 'TWITCHPRESS_UM_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'TWITCHPRESS_UM_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

if ( ! class_exists( 'TwitchPress_UM' ) ) :

    class TwitchPress_UM {
        /**
         * @var Singleton
         */
        private static $instance;        

        /**
         * Get a *Singleton* instance of this class.
         *
         * @return Singleton The *Singleton* instance.
         * 
         * @version 1.0
         */
        public static function instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }
            return self::$instance;
            
        } 
        
        /**
         * Private clone method to prevent cloning of the instance of the
         * *Singleton* instance.
         *
         * @return void
         */
        private function __clone() {}

        /**
         * Private unserialize method to prevent unserializing of the *Singleton*
         * instance.
         *
         * @return void
         */
        private function __wakeup() {}    
        
        /**
         * Protected constructor to prevent creating a new instance of the
         * *Singleton* via the `new` operator from outside of this class.
         */
        protected function __construct() {
            
            $this->define_constants();
            $this->includes();
            $this->init();                        

        }

        /**
         * Define TwitchPress Login Constants.
         * 
         * @version 1.0
         */
        private function define_constants() {
            
            $upload_dir = wp_upload_dir();
            
            // Main (package) constants.
            if ( ! defined( 'TWITCHPRESS_UM_ABSPATH' ) )  { define( 'TWITCHPRESS_UM_ABSPATH', __FILE__ ); }
            if ( ! defined( 'TWITCHPRESS_UM_BASENAME' ) ) { define( 'TWITCHPRESS_UM_BASENAME', plugin_basename( __FILE__ ) ); }
            if ( ! defined( 'TWITCHPRESS_UM_DIR_PATH' ) ) { define( 'TWITCHPRESS_UM_DIR_PATH', plugin_dir_path( __FILE__ ) ); }
            
            // Constants for force hidden views to been seen for this plugin.
            if ( ! defined( 'TWITCHPRESS_SHOW_SETTINGS_USERS' ) )    { define( 'TWITCHPRESS_SHOW_SETTINGS_USERS', true ); }
            if ( ! defined( 'TWITCHPRESS_SHOW_SETTINGS_BOT' ) )      { define( 'TWITCHPRESS_SHOW_SETTINGS_BOT', true ); }
            if ( ! defined( 'TWITCHPRESS_SHOW_SETTINGS_CHAT' ) )     { define( 'TWITCHPRESS_SHOW_SETTINGS_CHAT', true ); }
            if ( ! defined( 'TWITCHPRESS_SHOW_SETTINGS_JUKEBOX' ) )  { define( 'TWITCHPRESS_SHOW_SETTINGS_JUKEBOX', true ); }
            if ( ! defined( 'TWITCHPRESS_SHOW_SETTINGS_GAMES' ) )    { define( 'TWITCHPRESS_SHOW_SETTINGS_GAMES', true ); }
            if ( ! defined( 'TWITCHPRESS_SHOW_SETTINGS_COMMANDS' ) ) { define( 'TWITCHPRESS_SHOW_SETTINGS_COMMANDS', true ); }
            if ( ! defined( 'TWITCHPRESS_SHOW_SETTINGS_CONTENT' ) )  { define( 'TWITCHPRESS_SHOW_SETTINGS_CONTENT', true ); }      
        }  

        /**
         * Include required files.
         * 
         * @version 1.0
         */
        public function includes() {
            //include_once( 'includes/function.twitchpress-sync-core.php' );
            
            if ( twitchpress_is_request( 'admin' ) ) {
                //include_once( 'includes/class.twitchpress-sync-uninstall.php' );
            }      
        }

        /**
         * Hook into actions and filters.
         * 
         * @version 1.0
         */
        private function init() {
        
            // Load this extension after plugins loaded, we need TwitchPress core to load first mainly.
            add_action( 'plugins_loaded',      array( $this, 'after_plugins_loaded' ), 0 );

            // do_action in TwitchPress Sync Extension when visitor subscribes through WP API.
            add_action( 'twitchpress_sync_new_subscriber', array( $this, 'process_new_subscriber' ), 99, 3 );
            add_action( 'twitchpress_sync_discontinued_subscriber', array( $this, 'discontinued_subscriber' ), 99, 2 );
            
            register_activation_hook( __FILE__, array( 'TwitchPress_Boilerplate_Install', 'install' ) );
            
            // Do not confuse deactivation of a plugin with deletion of a plugin - two very different requests.
            register_deactivation_hook( __FILE__, array( 'TwitchPress_Boilerplate_Uninstall', 'deactivate' ) );
        }
                      
        /**
         * Init the plugin after plugins_loaded so environment variables are set.
         * 
         * @version 1.0
         */
        public function after_plugins_loaded() {
            
            // Filters
            add_filter( 'twitchpress_get_sections_users', array( $this, 'settings_add_section_users' ), 8 );
            add_filter( 'twitchpress_get_settings_users', array( $this, 'settings_add_options_users' ), 8 );
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
                                       
        }
        
        /**
        * An action hooked using twitchpress_sync_new_subscriber. Checks if the Twitch user
        * is a subscriber to the giving channel (in the current system the main/default channel
        * is passed to this method). 
        * 
        * If the new WP user is a subscriber on Twitch then the applicable UM role is applied.
        * 
        * @version 1.0
        */
        public function process_new_subscriber( $user_id, $channel_id, $twitch_api_response ) {
          
            $kraken = new TWITCHPRESS_Kraken5_Calls();

            $user_subscribed = $kraken->getUserSubscription( 
                $user_id, 
                $channel_id 
            );
            
            if( $user_subscribed === NULL || !isset( $user_subscribed['sub_plan'] ) ) {
                return false;
            }

            // The giving user subscribes to the giving channel. Now get the role paired to the plan.
            $um_role_subscribed = get_option( 'twitchpress_um_subtorole_' . $user_subscribed['sub_plan'] );

            // Set the users role. User meta is handled by TwitchPress Sync Extension 
            $wp_user_object = new WP_User( $user_id );
            $wp_user_object->set_role( $um_role_subscribed );
            
        }

        /**
        * An action hooked using twitchpress_discontinued_subscriber.
        * 
        * @version 1.0
        */        
        public function discontinued_subscriber( $user_id, $channel_id ) {   
         
            $um_role_subscribed = get_option( 'twitchpress_um_subtorole_none' );
            $wp_user_object = new WP_User( $user_id );
            $wp_user_object->set_role( $um_role_subscribed );    
                    
        }
        
        /**
        * Add a new section to the User settings tab.
        * 
        * @param mixed $sections
        * 
        * @version 1.0
        */
        public function settings_add_section_users( $sections ) {  
            global $only_section;
            
            // We use this to apply this extensions settings as the default view...
            // i.e. when the tab is clicked and there is no "section" in URL. 
            if( empty( $sections ) ){ 
                $only_section = true;
            } else { 
                $only_section = false; 
            }
                        
            // Add sections to the User Settings tab. 
            $new_sections = array(
                'ultimatemember'  => __( 'UM Roles', 'twitchpress-um' ),
            );

            return array_merge( $sections, $new_sections );           
        }
        
        /**
        * Add options to this extensions own settings section.
        * 
        * @param mixed $settings
        * 
        * @version 1.0
        */
        public function settings_add_options_users( $settings ) {
            global $current_section, $only_section;
            
            $new_settings = array();
            
            // This first section is default if there are no other sections at all.
            if ( 'ultimatemember' == $current_section || !$current_section && $only_section ) {
                
                // Get Ultimate Member roles. 
                $um_roles = um_get_roles();
                            
                $new_settings = apply_filters( 'twitchpress_ultimatemember_users_settings', array(
     
                    array(
                        'title' => __( 'Subscription to Role Pairing', 'twitchpress-um' ),
                        'type'     => 'title',
                        'desc'     => __( 'These options have been added by the TwitchPress UM extension. Pair your Twitch subscription plans to Ultimate Member roles.', 'twitchpress-um' ),
                        'id'     => 'subscriptionrolepairing',
                    ),

                    array(
                        'title'    => __( 'No Subscription', 'twitchpress-um' ),
                        'id'       => 'twitchpress_um_subtorole_none',
                        'css'      => 'min-width:300px;',
                        'default'  => 'menu_order',
                        'type'     => 'select',
                        'options'  => apply_filters( 'twitchpress_um_subtorole_none', $um_roles ),
                    ),
                    
                    array(
                        'title'    => __( 'Prime', 'twitchpress-um' ),
                        'id'       => 'twitchpress_um_subtorole_menu_prime',
                        'css'      => 'min-width:300px;',
                        'default'  => 'menu_order',
                        'type'     => 'select',
                        'options'  => apply_filters( 'twitchpress_um_subtorole_prime', $um_roles ),
                    ),                    
                    
                    array(
                        'title'    => __( '$4.99', 'twitchpress-um' ),
                        'id'       => 'twitchpress_um_subtorole_1000',
                        'css'      => 'min-width:300px;',
                        'default'  => 'menu_order',
                        'type'     => 'select',
                        'options'  => apply_filters( 'twitchpress_um_subtorole_1000', $um_roles ),
                    ),
                      
                    array(
                        'title'    => __( '$9.99', 'twitchpress-um' ),
                        'id'       => 'twitchpress_um_subtorole_2000',
                        'css'      => 'min-width:300px;',
                        'default'  => 'menu_order',
                        'type'     => 'select',
                        'options'  => apply_filters( 'twitchpress_um_subtorole_2000', $um_roles ),
                    ),
                      
                    array(
                        'title'    => __( '$24.99', 'twitchpress-um' ),
                        'id'       => 'twitchpress_um_subtorole_3000',
                        'css'      => 'min-width:300px;',
                        'default'  => 'menu_order',
                        'type'     => 'select',
                        'options'  => apply_filters( 'twitchpress_um_subtorole_3000', $um_roles ),
                    ),
                            
                    array(
                        'type'     => 'sectionend',
                        'id'     => 'membershiprolepairing'
                    ),

                ));   
                
            }
            
            return array_merge( $settings, $new_settings );         
        }
        
        /**
         * Adds plugin action links
         *
         * @since 1.0.0
         */
        public function plugin_action_links( $links ) {
            $plugin_links = array(

            );
            return array_merge( $plugin_links, $links );
        }        

        /**
         * Get the plugin url.
         * @return string
         */
        public function plugin_url() {                
            return untrailingslashit( plugins_url( '/', __FILE__ ) );
        }

        /**
         * Get the plugin path.
         * @return string
         */
        public function plugin_path() {              
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }                                                         
    }
    
endif;    


if( !function_exists( 'TwitchPress_UM_Ext' ) ) {

    function TwitchPress_UM_Ext() {        
        return TwitchPress_UM::instance();
    }

    // Global for backwards compatibility.
    $GLOBALS['twitchpress-um'] = TwitchPress_UM_Ext(); 
}
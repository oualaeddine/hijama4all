<?php
/**
 * Plugin Name: Awesome Contact Form7 for Elementor
 * Description: Awesome Contact Form7 for Elementor Plugin add Contact form 7 to Elementor Page builder.
 * Plugin URI: https://wordpress.org/plugins/
 * Version: 1.9
 * Author: B.M. Rafiul Alam
 * Author URI: https://themesbyte.com
 * Text Domain: aep
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ACFE_URL', plugins_url( '/', __FILE__ ) );
define( 'ACFE_PATH', plugin_dir_path(__FILE__));

add_action( 'elementor/preview/enqueue_styles', 'aep_elementor_enqueue_style' );
add_action('wp_enqueue_scripts', 'aep_elementor_enqueue_style');

function aep_elementor_enqueue_style() {
    wp_enqueue_style( 'aep-preview', ACFE_URL  . 'assets/css/style.css', array());
}

class aepCf7 {
 
   private static $instance = null;
 
   public static function get_instance() {
      if ( ! self::$instance )
         self::$instance = new self;
      return self::$instance;
   }
 
   public function init(){
      add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );
   }
 
   public function widgets_registered() {
 
      // We check if the Elementor plugin has been installed / activated.
      if(defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')){

         // We look for any theme overrides for this custom Elementor element.
         // If no theme overrides are found we use the default one in this plugin.
         $widget_file = get_template_directory() .'/awesome-contact-form7-for-elementor/widget/aep-contact-form7.php';
         $template_file = locate_template($widget_file);
         if ( !$template_file || !is_readable( $template_file ) ) {
           $template_file = plugin_dir_path(__FILE__).'widgets/aep-contact-form7.php' ; 
         }
         if ( $template_file && is_readable( $template_file ) ) {
            require_once $template_file;
         }
      }
   }
}
 
aepCf7::get_instance()->init();
require_once ACFE_PATH .'/includes/aep-notice/admin-notice.php';
require_once ACFE_PATH .'/includes/helper/contact-form7.php';
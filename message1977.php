<?php
/*
Plugin Name: Message1977
Plugin URI: 
Description: Multi Position Message Bar, show messages or notifications with message bar. You can setup message bar look and feel and the position. 
Author: Chin
Version: 0.1.9
Author URI: http://web1977.com

Todo:
 - Mobile/Tablet detection and CSS 
 - Editor for easier input 
 - Better UI for custom CSS
 - Better Page Id, Post Id, Category Id selection
 - Preview
 - Theme
 
*/

defined('MESSAGE1977_VERSION') or define('MESSAGE1977_VERSION', '0.1.9');
defined('MESSAGE1977_PATH') or define('MESSAGE1977_PATH', plugin_dir_path(__FILE__));
defined('MESSAGE1977_NONCE') or define('MESSAGE1977_NONCE', "message1977_nonce_" . MESSAGE1977_VERSION);
require_once 'includes/message1977.php';
  
if ( !is_admin() ) {    

    add_action('wp', 'message1977::pre_process');

} else {

    require_once 'includes/activate.php';
    require_once 'includes/backend.php';
    
    register_activation_hook (__FILE__, 'message1977_activate::install');
    register_activation_hook (__FILE__, 'message1977_activate::install_data');        
    register_uninstall_hook  (__FILE__, 'message1977_activate::uninstall');
    
    /** Add menu link to configuration **/
    add_action( 
        'admin_menu', 
        'message1977_backend::menu'
    );

    /** For php < 5.2.0 **/
    if (!function_exists('json_encode')) {
        function json_encode($data) {
            switch ($type = gettype($data)) {
                case 'NULL':
                    return 'null';
                case 'boolean':
                    return ($data ? 'true' : 'false');
                case 'integer':
                case 'double':
                case 'float':
                    return $data;
                case 'string':
                    return '"' . addslashes($data) . '"';
                case 'object':
                    $data = get_object_vars($data);
                case 'array':
                    $output_index_count = 0;
                    $output_indexed = array();
                    $output_associative = array();
                    foreach ($data as $key => $value) {
                        $output_indexed[] = json_encode($value);
                        $output_associative[] = json_encode($key) . ':' . json_encode($value);
                        if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                            $output_index_count = NULL;
                        }
                    }
                    if ($output_index_count !== NULL) {
                        return '[' . implode(',', $output_indexed) . ']';
                    } else {
                        return '{' . implode(',', $output_associative) . '}';
                    }
                default:
                    return ''; // Not supported
            }
        }
    }    
}



<?php

// Require an action parameter
if ( empty( $_REQUEST['action'] ) )
    die( '0' );

/** Load WordPress Bootstrap */
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' );
require_once( ABSPATH . '/wp-content/plugins/message1977/includes/backend.php' );

@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );

if ( function_exists('send_nosniff_header') ) {
    send_nosniff_header();
}

switch ($_REQUEST['action']) {
    
    case 'get':
        message1977_backend::get_item_callback();
        break;
        
    case 'save':
        message1977_backend::save_item_callback();
        break;
        
    case 'delete':
        message1977_backend::delete_item_callback();
        break;
        
    case 'publish':
        message1977_backend::publish_callback();
        break;
        
    case 'unpublish':
        message1977_backend::unpublish_callback();
        break;
        
    default:
        die( '0' );

} 


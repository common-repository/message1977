<?php

    class message1977_activate {
     
        public static function install() {
            
            global $wpdb;
            
            $installed  = get_option("message1977_version");
            
            if ( $installed !== MESSAGE1977_VERSION ) :
                
                $table_name = $wpdb->prefix . "message1977";
    
                $sql = "CREATE TABLE $table_name (
                    
                    id               mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,
                    
                    time             datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    name             tinytext DEFAULT '' NOT NULL,
                    theme            varchar(255) CHARACTER SET 'utf8' DEFAULT '' NOT NULL,
                    sdelay           smallint(6) UNSIGNED DEFAULT 0 NOT NULL,
                    edelay           smallint(6) UNSIGNED DEFAULT 0 NOT NULL,
                    
                    position         varchar(20) CHARACTER SET 'utf8' DEFAULT '' NOT NULL,
                    priority         smallint(6) UNSIGNED DEFAULT 100 NOT NULL,
                    publish          tinyint(1) UNSIGNED DEFAULT 1 NOT NULL,
                    click_to_dismiss tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
                    custom_css       varchar(255) CHARACTER SET 'utf8' DEFAULT '' NOT NULL,
                    
                    ptime            datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    etime            datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    
                    is_all           tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
                    is_home          tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
                    page_id          varchar(255) CHARACTER SET 'utf8' DEFAULT '' NOT NULL,
                    cate_id          varchar(255) CHARACTER SET 'utf8' DEFAULT '' NOT NULL,
                    post_id          varchar(255) CHARACTER SET 'utf8' DEFAULT '' NOT NULL,
                    user_level       varchar(255) CHARACTER SET 'utf8' DEFAULT '' NOT NULL,

                    content          MEDIUMTEXT CHARACTER SET 'utf8' NOT NULL,
                    
                    UNIQUE KEY id (id),
                    INDEX `publish` (`ptime` ASC, `etime` ASC, `publish` DESC, `priority` DESC)
                    
                ) DEFAULT CHARSET=utf8;";
    
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                
            endif;
            
        }   
        
        public static function install_data() {

            global $wpdb;
            
            $installed  = get_option("message1977_version");
           
            if ( !$installed ) :
                $table_name = $wpdb->prefix . "message1977";
                        
                // example 1 - Welcome Message
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'welcome',
                    'sdelay'   => '0',
                    'edelay'   => '0',
                    'position' => 'top',
                    'theme'    => 'default1977',
                    'user_level' => '',
                    'publish'    => 0,
                    'content'    => 'Thank you for choosing Message1977.' 
                ));
                
                // example 2 - delay
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'delay example',
                    'sdelay'   => '3000',
                    'edelay'   => '10000',
                    'position' => 'top',
                    'theme'    => 'default1977',
                    'user_level' => '',
                    'publish'    => 0,
                    'content'    => 'This message will appear after 3 seconds, and disappear 10 seconds afterwards.' 
                ));
                                
                // example 3 - Show only on home page
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'specific example',
                    'sdelay'   => '0',
                    'edelay'   => '0',
                    'position' => 'top',
                    'theme'    => 'default1977',
                    'user_level' => '',
                    'publish'    => 0,
                    'content'    => 'Will only show on home page.' 
                ));

                // example 4 - Show with specific user level
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'specific example1',
                    'sdelay'   => '0',
                    'edelay'   => '0',
                    'position' => 'top',
                    'theme'    => 'default1977',
                    'publish'  => 0,
                    'is_all'   => 0,
                    'is_home'  => 0,
                    'user_level' => '10,9',
                    'content'    => 'Will only show for user level 10 and 9.' 
                ));                
                
                // example 5 - html
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'specific example2',
                    'sdelay'   => '0',
                    'edelay'   => '0',
                    'position' => 'top',
                    'theme'    => 'default1977',
                    'publish'  => 0,
                    'user_level' => '',
                    'content'    => 'Some <b>html</b> stuff. <img src="wp-content/plugins/message1977/theme/default1977/img/tick.png" border="0" style="vertical-align:top;" />' 
                ));                                
                               
                // example 6 - bottom message
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'specific example',
                    'sdelay'   => '0',
                    'edelay'   => '0',
                    'position' => 'bottom',
                    'theme'    => 'default1977',
                    'publish'  => 0,
                    'user_level' => '',
                    'content'    => 'Show message at <strong><i>bottom</i></strong>' 
                ));                                

                // example 7 - Custom CSS
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'Custom CSS 1',
                    'sdelay'   => '0',
                    'edelay'   => '0',
                    'position' => 'top',
                    'theme'    => 'default1977',
                    'publish'  => 0,
                    'user_level' => '',
                    'content'    => 'Show message with custom CSS',
                    'custom_css' => 'background-color:#990000;color:#fff;'
                ));                
                // example 8 - Custom CSS
                $rows_affected = $wpdb->insert( $table_name, array( 
                    'time'     => current_time('mysql'), 
                    'name'     => 'Custom CSS 2',
                    'sdelay'   => '0',
                    'edelay'   => '0',
                    'position' => 'top',
                    'theme'    => 'default1977',
                    'publish'  => 0,
                    'user_level' => '',
                    'content'    => 'Show message with custom CSS',
                    'custom_css' => 'background-color:#ffcc00;color:#333;text-shadow: 0 1px 2px #222;'
                ));                                
            endif;
            
            update_option("message1977_version", MESSAGE1977_VERSION);
            
        }

        public static function uninstall() {
            global $wpdb;
            
            delete_option("message1977_version");
            $table_name = $wpdb->prefix . "message1977";
                        
            $wpdb->query(
                "
                    DROP TABLE IF EXISTS $table_name 
                "
            );             
        }   
         
    }
        
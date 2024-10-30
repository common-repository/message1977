<?php

    class message1977 {
        
        public static $config   = null;
        public static $messages = null;
        
        public static $need_body_class_top    = false;
        public static $need_body_class_bottom = false;
        
        public static function process_config() {
            
            // Default values
            message1977::$config = array(
                'theme'             => 'default1977', // Default Theme
                
                'priority_script'   => 1000,
                'priority_footer'   => 1000,
                'jquery'            => true,
            );
            
            // Override with user values
            if ( $opt = get_option('message1977_priority_script') ) 
                message1977::$config['priority_script'] = $opt;
            if ( $opt = get_option('message1977_priority_footer') ) 
                message1977::$config['priority_footer'] = $opt; 
             
            
        } /* function process_config */
        
        public static function pre_process() {
            
            // Process if there are any publish messages
            message1977::get_messages();
            
            // If there is no messages, don't load anything
            if ( count(message1977::$messages) ) :
                // Get plugin configurations, settings
                message1977::process_config();        
                
                wp_enqueue_style(
                    'message1977-style', 
                    WP_CONTENT_URL . '/plugins/message1977/css/main.css', 
                    null, 
                    MESSAGE1977_VERSION, 
                    'all'
                );
                                
                add_action(
                    'wp_print_scripts', 
                    'message1977::init', 
                    message1977::$config['priority_script'] // Can ovrride priority base on settings
                );     
                
                add_action(
                    'wp_footer', 
                    'message1977::render', 
                    message1977::$config['priority_footer']
                );

                /** If message appear permanent on screen then need to make space **/
                if ( message1977::$need_body_class_top )
                    add_filter('body_class', 'message1977::render_body_class_top');
                /** If message appear permanent on screen then need to make space **/
                if ( message1977::$need_body_class_bottom )
                    add_filter('body_class', 'message1977::render_body_class_bottom');
                                
            endif; /* if ( count(message1977::$messages) ) */
        }
        
        public static function init() {
            // Exclude if it is admin?... Nah..
            // if (current_user_can( 'manage_options' )) {
            $dep = array();
            if ( message1977::$config['jquery'] ) {
                $dep[] = 'jquery';
            }
        
            wp_enqueue_script(
                'message1977', 
                WP_CONTENT_URL . '/plugins/message1977/js/main.js', 
                $dep, 
                MESSAGE1977_VERSION, 
                true
            );
        } /* function init */
                
        public static function render() {
            
            global $is_IE;
 
            $class = 'wrapper'; 
            
            if ( $is_IE ) {
                if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 7' ) )
                    $class .= ' ie7';
                elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 8' ) )
                    $class .= ' ie8';
                elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) )
                    $class .= ' ie9';
            } else {
                if ( function_exists('wp_is_mobile' ) ) {
                    if ( wp_is_mobile() ) {
                        $class .= ' mobile';
                    }
                }
                $class .= ' normal';
            }/* if ( $is_IE ) */
            
            $script = $messages = $css = '';
            foreach ( message1977::$messages as $key => $val ) :
                if ( !$val->theme ) $val->theme = message1977::$config['theme'];
                $script .= "
                            message1977_obj.messages[" . $key . "] = {
                                 rel : " . $key . ",
                                 sdelay : " . $val->sdelay . ", 
                                 edelay : " . $val->edelay . ",
                                 position : '" . $val->position . "',
                                 click_to_dismiss : " . $val->click_to_dismiss . " 
                            };
                           ";
                           
                $css[$val->theme] = "<link rel='stylesheet' href='" . (WP_CONTENT_URL . '/plugins/message1977/theme/' . $val->theme . '/main.css?ver=' . MESSAGE1977_VERSION ) . "' type='text/css' media='all' />";
            
                $messages .= '
                <div class="message1977 ' . $val->theme . ' ' . ($val->theme . '_' . $val->position) . ' message1977_' . $val->position . ' ' . ( $val->sdelay ? 'message1977_hideIt' : 'message1977_nodelay' ) . '"' . 
                   ' rel="' . $key . '"' .
                   ' style=""' .
                   '>
                    <div class="' . $class . '">
                        <span class="msg" style="' . $val->custom_css . '">' . $val->content . '</span>
                    </div>
                </div>';
            
            endforeach; /* foreach ( message1977::$messages as $key => $val ) : */
            
            foreach ( $css as $key => $val ) :
                echo $val;
            endforeach; /* foreach ( $css as $key => $val ) : */

            echo $messages;
                        
            ?>
            <script>
                jQuery(document).ready(function() {
                    <?php echo $script; ?>
                    message1977_obj.process();
                });
            </script>
            <?php
            
        } /* function render */

        public static function render_body_class_top( $classes ) {
            $classes[] = 'message1977_body_top';
            return $classes;
        }

        public static function render_body_class_bottom( $classes ) {
            $classes[] = 'message1977_body_bottom';
            return $classes;
        }
                
        public static function get_messages() {
            // Get messages that are :
            //       - Within time range  
            //    or - ptime is 0000-00-00 00:00:00
            //   and - publish = 1
            //    or - within page_id
            //    or - within cate_id
            //    or - within post_id
            //    or - within user_grp
            
            // flag if got messages and sdelay && edelay = 0 and position is top or bottom 
            // ( This is to make sure we reserver room for them ) 
            
            global $wpdb, $user_level, $wp_query;
            
            $post_id    = !empty($_REQUEST['p']) ? $_REQUEST['p'] : get_the_ID();
            $cate_id    = !empty($_REQUEST['cat']) ? $_REQUEST['cat'] : 0;
            $page_id    = !empty($_REQUEST['page_id']) ? $_REQUEST['page_id'] : $wp_query->get_queried_object_id();
            
            $table_name = $wpdb->prefix . "message1977";
            
            $msg        = $wpdb->get_results("
                SELECT * FROM $table_name WHERE 
                    (ptime <= '" . current_time('mysql') . "' OR ptime = '0000-00-00 00:00:00') AND
                    (etime >= '" . current_time('mysql') . "' OR etime = '0000-00-00 00:00:00') AND
                    publish = 1
                    ORDER BY priority DESC, time DESC
            ");
            
            $ret = array();
            
            foreach ( $msg as $key => $val ) :
                if ( !$val->is_all ) {
                    if ( (is_home() && !$val->is_home) || (!is_home() && $val->is_home) ) {
                        continue;
                    } else {
                        if ( $val->post_id != '' ) {
                            $check = explode(',', $val->post_id);
                            if ( !in_array($post_id, $check) ) continue;
                        }
                        
                        if ( $val->cate_id != '' ) {
                            $check = explode(',', $val->cate_id);
                            if ( !in_array($cate_id, $check) ) continue;                    
                        }
                        
                        if ( $val->page_id != '') {
                            $check = explode(',', $val->page_id);
                            if ( !in_array($page_id, $check) ) continue;                    
                        }
                        
                        if ( $val->user_level != '' && isset($user_level) ) {
                            $check = explode(',', $val->user_level);
                            if ( !in_array($user_level, $check) ) continue;                    
                        }
                    } /* if ( is_home() && !$val->is_home ) */
                } /* if ( !$val->is_all ) */
                
                if ( !$val->sdelay && !$val->edelay && $val->position == 'top' ) 
                    message1977::$need_body_class_top = true;
                if ( !$val->sdelay && !$val->edelay && $val->position == 'bottom' ) 
                    message1977::$need_body_class_bottom = true;
                
                $ret[] = $val;
            endforeach;
            
            message1977::$messages = $ret;
            
        }        
        
    } /* class message1977 */
   

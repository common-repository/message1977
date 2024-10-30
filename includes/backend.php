<?php

    class message1977_backend {

        /** ADMIN **/
        public static function menu() {
            
            $hook = add_menu_page(__('Message1977 Setup'), __('Message1977'), 'manage_options', 'message1977_config', 'message1977_backend::setup', plugin_dir_url( __FILE__) . '../img/icon.png', 100);
            add_action('admin_print_scripts-' . $hook, 'message1977_backend::scripts');
             
        } /* function menu */

        public static function scripts() {

            wp_deregister_script( 'jquery' );
            wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
            wp_enqueue_script( 'jquery' );

        }
        
        public static function setup() {

            $filter   = message1977_backend::get_filters(); 
            $messages = message1977_backend::get_messages($filter);
            $base_url = WP_CONTENT_URL . '/plugins/message1977';
?>
            <link rel='stylesheet' href='<?php echo ($base_url . '/css/backend.css?ver=' . MESSAGE1977_VERSION ); ?>' type='text/css' media='all' />
            <link rel='stylesheet' href='<?php echo ($base_url . '/lib/plugins/jquery.fancybox.css?ver=' . MESSAGE1977_VERSION ); ?>' type='text/css' media='all' />

            <div class="wrap ">
                <h2 class="message1977_title"><?php _e('Message1977 Setup'); ?></h2>
                <a class="message1977_add" href="javascript:void(0);" onclick="message1977_backend.add_item();">Add New</a>
                <div class="sep_large"></div>
                <?php if ( isset($_GET['message']) ) : ?>
                    <div class="updated below-h2" id="message">
                        <p><?php _e( $_GET['message'] ); ?></p>
                    </div>
                    <div class="sep_large"></div>
                <?php endif; ?>
                
                <?php if ( $_REQUEST['filter'] == 'all' ) : ?>All<?php else : ?>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&filter=all">All</a>
                <?php endif; ?> (<span id="message1977_count_all"><?php echo count($messages['data']); ?></span>) &middot; 
                
                <?php if ( $_REQUEST['filter'] == 'top' ) : ?>Top<?php else : ?>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&filter=top">Top</a> 
                <?php endif; ?> (<span id="message1977_count_top"><?php echo $messages['top_count']; ?></span>) &middot;
                
                <?php if ( $_REQUEST['filter'] == 'bottom' ) : ?>Bottom<?php else : ?>
                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&filter=bottom">Bottom</a>
                <?php endif; ?> (<span id="message1977_count_bottom"><?php echo $messages['bottom_count']; ?></span>)
                
                <div class="sep_small"></div>
                <table width="100%" class="message1977_items" cellpadding="0" cellspacing="0">
                    <tr id="message1977_items_header">
                        <th>Name/Slug</th>
                        <th>Message</th>
                        <th>Position</th>
                        <th>Priority</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Publish</th>
                    </tr>
                    
                    <?php $i = 0; foreach ( $messages['data'] as $key => $val ) : ?>
                    <tr class="<?php echo ($i % 2) ? 'row_even' : 'row_odd' ; ?>" rel="<?php echo $val->id; ?>">
                        <td><?php echo $val->name ? $val->name : '-'; ?></td>
                        <td><?php echo strip_tags($val->content); ?></td>
                        <td><?php echo $val->position; ?></td>
                        <td><?php echo $val->priority; ?></td>
                        <td><?php echo ($val->ptime != '0000-00-00 00:00:00') ? date('D M j', strtotime($val->ptime)) : '-'; ?></td>
                        <td><?php echo ($val->etime != '0000-00-00 00:00:00') ? date('D M j', strtotime($val->etime)) : '&infin;'; ?></td>
                        <td>
                            <span class="<?php echo $val->publish ? 'published' : 'pending'; ?>">
                            <?php 
                                echo $val->publish ? 'Published' : 'Pending'; 
                            ?>
                            </span>
                        </td>
                    </tr>
                    <tr class="<?php echo ($i % 2) ? 'row_even' : 'row_odd' ; ?> message1977_controls">
                        <td colspan="7">
                            <a href="javascript:void(0);" onclick="message1977_backend.edit_item(<?php echo $val->id; ?>);">Edit</a> &middot; 
                            <a href="javascript:void(0);" class="red" onclick="message1977_backend.delete_item(<?php echo $val->id; ?>);">Trash</a> &middot;
                            <?php if ( $val->publish ) : ?>
                                <a href="javascript:void(0);" onclick="message1977_backend.unpublish(<?php echo $val->id; ?>);">Unpublish</a>
                            <?php else: ?>
                                <a href="javascript:void(0);" onclick="message1977_backend.publish(<?php echo $val->id; ?>);">Publish</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $i++; endforeach; ?>
                </table>
                <div class="sep_small"></div>
                <span class="remarks">Showing <span id="message1977_count"><?php echo $i; ?></span> messages</span>
                <hr class="line" />    
                <span class="remarks">
                    <a href="http://web1977.com">web1977.com</a> &middot; 
                    Version <?php echo MESSAGE1977_VERSION; ?> &middot; 
                    Special thanks to &rsaquo; 
                    <a href="//wordpress.org">WordPress</a> &middot; 
                    <a href="//jquery.com">jQuery</a> &middot; 
                    <a href="//fancyapps.com/fancybox/">FancyBox2</a> &middot; 
                    <a href="//craigsworks.com/projects/simpletip">SimpleTip</a> &middot; 
                    <a href="//github.com/RobinHerbots/jquery.inputmask">InputMask</a>
                </span>
            </div>

            <div id="editBox">
                <div class="twoCol message1977_edit">
                    <h3></h3>
                    <input type="hidden" name="item_id" value="" />
                    <div class="sep_small"></div>
                    <div class="col">
                        <div class="row tt" rel="Short description of your entry">
                            <label>Name</label>
                            <div class="input">
                                <input class="in" type="text" name="name" value="" />
                            </div>
                        </div>
                        <div class="row tt" rel="Enter the message you want to display on site">
                            <label>Message</label>
                            <div class="input">
                                <textarea class="in" name="content"></textarea>
                            </div>
                        </div>
                        <div class="row tt" rel="Top - Message will appear on top<br />Bottom - Message will appear on bottom">
                            <label>Position</label>
                            <div class="input">
                                <select class="in" name="position">
                                    <option></option>
                                    <option value="top">Top</option>
                                    <option value="bottom">Bottom</option>
                                </select>
                            </div>
                        </div>    
                        <div class="row tt" rel="How many seconds before the message appear. <br />1000 = 1 second, 0 = appear immediately">
                            <label>Delay <span class="remarks">(Appear)</span></label>
                            <div class="input">
                                <input class="in" class="message1977_digit" type="text" name="sdelay" value="0" />
                            </div>
                        </div>                                                
                        <div class="row tt" rel="How many seconds before the message disappear. <br />1000 = 1 second, 0 = appear immediately">
                            <label>Delay <span class="remarks">(Disappear)</span></label>
                            <div class="input">
                                <input class="in" class="message1977_digit" type="text" name="edelay" value="0" />
                            </div>
                        </div>                    
                        <div class="row tt" rel="Plugin will pick message with the highest number to show first">
                            <label>Priority</label>
                            <div class="input">
                                <input class="in" class="message1977_digit" type="text" name="priority" value="100" />
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <label>Publish</label>
                            <div class="input">
                                <select class="in" name="publish">
                                    <option value="1">Publish</option>
                                    <option value="0">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="row tt" rel="The date you want the message to display.">
                            <label>Start Date</label>
                            <div class="input">
                                <input class="message1977_date in" type="text" name="ptime" value="" />
                            </div>
                        </div>
                        <div class="row tt" rel="The date you want the message to unpublish.">
                            <label>End Date</label>
                            <div class="input">
                                <input class="message1977_date in" type="text" name="etime" value="" />
                            </div>
                        </div>
                        <div class="row tt" rel="<ul><li>All - Message will appear on all pages</li><li>Home - Message will appear just on homepage</li><li>Specific - Message appear on specific post ID and etc.</li><li>Enter Page ID, Post ID like 10,11,21 (comma delimited)</li></ul>">
                            <label>When to Show</label>
                            <div class="input">
                                <select class="in" name="when_to_show">
                                    <option value="all">All</option>
                                    <option value="home">Just Home Page</option>
                                    <option value="specific">Specific Location</option>
                                </select>
                                <div class="sub_input hideIt when_to_show">
                                    <label>Page ID</label>     <input class="in" type="text" name="page_id" value="" />
                                    <div class="sep"></div>
                                    <label>Post ID</label>     <input class="in" type="text" name="post_id" value="" />
                                    <div class="sep"></div>
                                    <label>Category ID</label> <input class="in" type="text" name="cate_id" value="" />
                                    <div class="sep"></div>
                                    <label>User Level</label>  <input class="in" type="text" name="user_level" value="" />
                                    <div class="sep"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row tt" rel="If you are comfortable with CSS, you can overwrite look and feel using CSS here. For example <b>font-size:12px;color:red</b>">
                            <label>Custom CSS</label>
                            <div class="input">
                                <input class="in" type="text" name="custom_css" value="" />
                            </div>
                        </div>                        
                    </div>
                    <div class="sep_medium"></div>
                    <a class="message1977_save_button button-primary" href="javascript:void(0);" onclick="message1977_backend.save_item();">Save</a>
                </div>                
            </div>
            
            <script src="<?php echo ($base_url . '/lib/plugins/plugins.js?ver=' . MESSAGE1977_VERSION ); ?>"></script>
            <script src="<?php echo ($base_url . '/js/backend.js?ver=' . MESSAGE1977_VERSION ); ?>"></script>
            <script>
                message1977_backend.nonce   = '<?php echo wp_create_nonce(MESSAGE1977_NONCE); ?>';
                message1977_backend.ajaxurl = '<?php echo WP_CONTENT_URL . '/plugins/message1977/includes/ajax.php' ?>';
            </script>
            
<?php
        } /* function setup */
        
        public static function get_filters() {
            $filter = array();
            if ( isset($_REQUEST['filter']) ) {
                if ( $_REQUEST['filter'] === 'top' ) {
                    $filter = array(
                        'position' => 'top'
                    );
                } else if ( $_REQUEST['filter'] === 'bottom' ) {
                    $filter = array(
                        'position' => 'bottom'
                    );                   
                }
            }     
            return $filter;       
        } /* function get_filters */
        
        public static function get_messages(
            $filters = array()
        ) {
            global $wpdb;
            $table_name = $wpdb->prefix . "message1977";
            
            // Process Filters
            $where = ' 1 ';
            foreach ( $filters as $key => $val ) {
                $where .= ' AND ' . $key . '="'  . $val . '"';
            }
            
            $msg = $wpdb->get_results("
                SELECT * FROM $table_name
                WHERE " . $where . " 
                ORDER BY position, priority DESC, time DESC
            ");
            
            $tcount = $wpdb->get_row("
                SELECT COUNT(id) AS `total` FROM $table_name 
                WHERE position = 'top'
            ");
                        
            $bcount = $wpdb->get_row("
                SELECT COUNT(id) AS `total` FROM $table_name 
                WHERE position = 'bottom'
            ");

            return array(
                'data'         => $msg,
                'top_count'    => $tcount->total,
                'bottom_count' => $bcount->total
            );            
        } /* function get_messages */

        /** Ajax calls here **/
        function save_item_callback() {
            check_ajax_referer( MESSAGE1977_NONCE, 'security' );
            global $wpdb;
            $table_name = $wpdb->prefix . "message1977"; 
            
            $flag  = true;
            $error = $data = '';
            $is_home = $is_all = 0;
            
            if ( isset($_POST['item_id']) ) {
                if ( $_POST['when_to_show'] == 'all' ) $is_all = 1;
                if ( $_POST['when_to_show'] == 'home' ) $is_home = 1;
                
                if ( $_POST['item_id'] ) {
                    // Update
                    $wpdb->query(
                        "
                        UPDATE $table_name 
                        SET 
                            `publish`    = '" . $_POST['publish'] . "',
                            `name`       = '" . trim(strip_tags($_POST['name'])) . "',
                            `content`    = '" . trim($_POST['content']) . "',
                            `priority`   = '" . ($_POST['priority'] ? $_POST['priority'] : '100') . "',
                            `position`   = '" . $_POST['position'] . "',
                            `sdelay`     = '" . $_POST['sdelay'] . "',
                            `edelay`     = '" . $_POST['edelay'] . "',
                            
                            `ptime`      = '" . $_POST['ptime'] . " 00:00:00',    
                            `etime`      = '" . $_POST['etime'] . " 00:00:00',    
                            
                            `is_home`    = '" . $is_home . "',
                            `is_all`     = '" . $is_all . "',
                            
                            `page_id`    = '" . $_POST['page_id'] . "',
                            `post_id`    = '" . $_POST['post_id'] . "',
                            `cate_id`    = '" . $_POST['cate_id'] . "',
                            `user_level` = '" . $_POST['user_level'] . "',
                            `custom_css` = '" . $_POST['custom_css'] . "'                            
                        WHERE `id` = '" . $_POST['item_id'] . "'
                        "
                    ); 
                                            
                } else {
                    // Add
                    $wpdb->query(
                        "
                        INSERT INTO $table_name 
                        SET 
                            `publish`    = '" . $_POST['publish'] . "',
                            `name`       = '" . trim(strip_tags($_POST['name'])) . "',
                            `content`    = '" . trim($_POST['content']) . "',
                            `priority`   = '" . ($_POST['priority'] ? $_POST['priority'] : '100') . "',
                            `position`   = '" . $_POST['position'] . "',
                            `sdelay`     = '" . $_POST['sdelay'] . "',
                            `edelay`     = '" . $_POST['edelay'] . "',
                            
                            `ptime`      = '" . $_POST['ptime'] . " 00:00:00',    
                            `etime`      = '" . $_POST['etime'] . " 00:00:00',    
                            
                            `is_home`    = '" . $is_home . "',
                            `is_all`     = '" . $is_all . "',
                            
                            `page_id`    = '" . $_POST['page_id'] . "',
                            `post_id`    = '" . $_POST['post_id'] . "',
                            `cate_id`    = '" . $_POST['cate_id'] . "',
                            `user_level` = '" . $_POST['user_level'] . "',
                            `custom_css` = '" . $_POST['custom_css'] . "'
                        "
                    ); 
                }
                $flag = true;
            } else {
                $error = '[Save Item] Empty form POST value : item_id, please try again or contact plugin developer.';    
            }           
            
            echo json_encode(array(
                'flag'  => $flag,
                'data'  => $data,
                'error' => $error
            ));
                    
            die(); 
        } /* function save_item_callback */     

        function get_item_callback() {
            check_ajax_referer( MESSAGE1977_NONCE, 'security' );
            global $wpdb; 
            $table_name = $wpdb->prefix . "message1977";
            
            $flag  = false;
            $error = $data = '';
            $tmp   = 'all';
            
            if ( isset($_POST['item_id']) ) {
                $sql = "SELECT * FROM $table_name WHERE id = '" . $_POST['item_id'] . "'";
                $ret = $wpdb->get_row($sql);
                  
                if ( $ret ) {
                    $flag = true;
                    
                    $data['item_id']      = $ret->id;
                    $data['name']         = $ret->name;
                    $data['content']      = $ret->content;
                    $data['priority']     = $ret->priority;
                    $data['position']     = $ret->position;
                    $data['sdelay']       = $ret->sdelay;
                    $data['edelay']       = $ret->edelay;
                    $data['publish']      = $ret->publish;
                    
                    if ( $ret->ptime == '0000-00-00 00:00:00') {
                        $data['ptime'] = '';
                    } else {
                        $data['ptime'] = date('Y-m-d', strtotime($ret->ptime));    
                    }
                    if ( $ret->etime == '0000-00-00 00:00:00') {
                        $data['etime'] = '';
                    } else {
                        $data['etime'] = date('Y-m-d', strtotime($ret->etime));    
                    }
                    
                    if ( $ret->is_home ) $tmp = 'home';
                    if ( !$ret->is_home && !$ret->is_all ) $tmp = 'specific';
                    $data['when_to_show'] = $tmp;
                    
                    $data['page_id']      = $ret->page_id;
                    $data['post_id']      = $ret->post_id;
                    $data['cate_id']      = $ret->cate_id;
                    $data['user_level']   = $ret->user_level;
                    $data['custom_css']   = $ret->custom_css;
                } else {
                    $error = '[Get Item] Unable to retrieve data for item ID : ' . $_POST['item_id'] . ', please try again or contact plugin developer. SQL [' . $sql . ']';
                }
            } else {
                $error = '[Get Item] Empty form POST value : item_id, please try again or contact plugin developer.';
            }

            echo json_encode(array(
                'flag'  => $flag,
                'data'  => $data,
                'error' => $error
            ));
        
            die(); 
        } /* function get_item_callback */     

        function delete_item_callback() {
            check_ajax_referer( MESSAGE1977_NONCE, 'security' );
            global $wpdb; 
            $table_name = $wpdb->prefix . "message1977";
        
            $flag  = true;
            $error = $data = '';
            
            if ( isset($_POST['item_id']) ) {
                $wpdb->query(
                    "
                    DELETE FROM $table_name 
                    WHERE `id` = '" . $_POST['item_id'] . "'
                    "
                ); 
                $flag = true;                      
            } else {
                $error = '[Delete Item] Empty form POST value : item_id, please try again or contact plugin developer.';
            }
            
            echo json_encode(array(
                'flag'  => $flag,
                'data'  => $data,
                'error' => $error
            ));
        
            die(); 
        } /* function delete_item_callback */

        function unpublish_callback() {
            check_ajax_referer( MESSAGE1977_NONCE, 'security' );
            global $wpdb; 
            $table_name = $wpdb->prefix . "message1977";
        
            $flag  = false;
            $error = $data = '';
            
            if ( isset($_POST['item_id']) ) {
                $wpdb->query(
                    "
                    UPDATE $table_name 
                    SET publish = 0
                    WHERE `id` = '" . $_POST['item_id'] . "'
                    "
                ); 
                $flag = true;               
            } else {
                $error = '[Unpublish Item] Empty form POST value : item_id, please try again or contact plugin developer.';
            }            
            
            echo json_encode(array(
                'flag'  => $flag,
                'data'  => $data,
                'error' => $error
            ));
        
            die(); 
        } /* function unpublish_callback */        
        
        function publish_callback() {
            check_ajax_referer( MESSAGE1977_NONCE, 'security' );
            global $wpdb; 
            $table_name = $wpdb->prefix . "message1977";
            
            $flag  = false;
            $error = $data = '';
            
            if ( isset($_POST['item_id']) ) {
                $wpdb->query(
                    "
                    UPDATE $table_name 
                    SET publish = 1
                    WHERE `id` = '" . $_POST['item_id'] . "'
                    "
                ); 
                $flag = true;               
            } else {
                $error = '[Publish Item] Empty form POST value : item_id, please try again or contact plugin developer.';
            }            
            
            echo json_encode(array(
                'flag'  => $flag,
                'data'  => $data,
                'error' => $error
            ));
        
            die(); 
        } /* function publish_callback */
                                   
    } /* class message1977_backend */

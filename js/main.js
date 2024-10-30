var message1977_obj = null;

message1977_obj = {
        
    messages : [],
    
    squeue : [],
    equeue : [],
    count  : 0,
    queue  : null,
    
    top : 0,
        
    init : function() {
  
        message1977_obj.admin_bar();    
        
    },
 
    admin_bar : function() {
       var admin_bar = null
           ;
        
        /** Push admin bar upward or downward when detected **/
        if ( (admin_bar = jQuery('#wpadminbar')).attr('id') === 'wpadminbar' &&
             jQuery('.message1977_top').size() ) {
                 
            jQuery('.message1977_top').each(function(){
                jQuery(this).css({
                    top: admin_bar.outerHeight()
                });    
                
                message1977_obj.top = admin_bar.outerHeight();
            });  
                      
        }        
    },
    
    process : function() {
        
        var obj = null, 
            tmp = 0;
        
        jQuery.each(message1977_obj.messages, function(key, val) {
            obj = jQuery('.message1977[rel="' + val.rel + '"]');
            
            // Put messages in queue for those that are not showing up immediately
            if ( val.sdelay ) {
                message1977_obj.squeue[val.sdelay + '_' + val.rel] = true;  
            }   
            if ( val.edelay ) {
                tmp = val.sdelay + val.edelay;
                message1977_obj.equeue[tmp + '_' + val.rel] = true;
            }   
            
            // Process click event to dismiss messages
            if ( val.click_to_dismiss ) {
                obj.live('click', function() {
                    delete message1977_obj.messages[jQuery(this).attr('rel')];
                    jQuery(this).remove();
                    message1977_obj.positioning();     
                });
            }      
        });
        
        // To run at least once
        message1977_obj.positioning();
        
        message1977_obj.queue = setInterval(function() {
            message1977_obj.count = message1977_obj.count + 1000;
            message1977_obj.positioning();    
        }, 1000);
        
    },
    
    positioning: function() {
        
        var got_visible_top    = false,
            got_visible_bottom = false,
            nothing_left       = true,
            obj                = null,
            top                = 0,
            bottom             = 0,
            count_top          = 0
            count_bottom       = 0;
        
        jQuery.each(message1977_obj.messages, function(key, val) {
            if ( typeof val != 'undefined' ) {
                obj = jQuery('.message1977[rel="' + val.rel + '"]');
                
                // Hide or show messages
                if ( typeof message1977_obj.squeue[message1977_obj.count + '_' + val.rel] != 'undefined' ) {
                    obj.removeClass('message1977_hideIt');
                    obj.addClass('message1977_nodelay');
                    message1977_obj.messages[key].sdelay = 0;    
                }
                
                if ( typeof message1977_obj.equeue[message1977_obj.count + '_' + val.rel] != 'undefined' ) {
                    delete message1977_obj.messages[obj.attr('rel')];
                    obj.remove();            
                }
                
                // Layout check
                if ( obj.is(':visible') ) {
                    if ( val.position == 'top' ) got_visible_top = true;
                    if ( val.position == 'bottom' ) got_visible_bottom = true;
                    
                    // Position messages
                    if ( val.position == 'top' ) {
                        if ( count_top > 0 ) top = top + obj.outerHeight();
                        else top = message1977_obj.top;
                        count_top++;
                        obj.css({
                            top : top
                        });
                    } else if ( val.position == 'bottom' && count_bottom > 0 ) {
                        if ( count_bottom > 0 ) bottom = bottom + obj.outerHeight();
                        count_bottom++;
                        obj.css({
                            bottom: bottom
                        });                    
                    }
                }
                
                if ( typeof message1977_obj.messages[key] != 'undefined' )
                    if ( message1977_obj.messages[key].sdelay || message1977_obj.messages[key].edelay ) {
                        nothing_left = false;
                    }              
            }
        });
        
        if ( !got_visible_top ) {
            jQuery('body').removeClass('message1977_body_top');
        } else if ( !jQuery('body').hasClass('message1977_body_top') ) {
            jQuery('body').addClass('message1977_body_top');
        }
        
        if ( !got_visible_bottom ) {
            jQuery('body').removeClass('message1977_body_bottom');
        } else if ( !jQuery('body').hasClass('message1977_body_bottom') ) {
            jQuery('body').addClass('message1977_body_bottom');
        }
        
        // Nothing left, so don't process messages
        if ( nothing_left ) {
            clearInterval(message1977_obj.queue);
        }
        
    }
       
};

if ( typeof(jQuery) != 'undefined' ) {
    // Execute only if jQuery is available
    jQuery(document).ready(function() {
        message1977_obj.init();
    });
}

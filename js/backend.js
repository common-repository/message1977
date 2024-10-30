var message1977_backend = null;

message1977_backend = {
    
    ajaxurl: '',
    nonce: '',
    
    init: function() {
        
        // Misc
        jQuery('.message1977_edit select[name="when_to_show"]').change(function() {
           if ( jQuery(this).val() == 'specific' ) {
                jQuery('.when_to_show').removeClass('hideIt');
           } else {
                jQuery('.when_to_show').addClass('hideIt');
           }
        });
        
        // Input Mask
        jQuery(".message1977_date").inputmask("y-m-d", {"placeholder": "yyyy-mm-dd"});
        jQuery(".message1977_digit").inputmask("9999999999", {placeholder:"", clearMaskOnLostFocus: true });
        
        // Tool Tip
        jQuery('.tt').each(function(){
            jQuery(this).simpletip({
                position : 'top',
                offset   : [-120, -60],
                fixed    : true,
                content  : jQuery(this).attr('rel') 
            });
        });
        
        
        jQuery('.in').focus(function() {
            jQuery(this).parent().parent('.row').trigger('mouseover'); 
        });
        
        jQuery('.in').blur(function() {
            jQuery(this).parent().parent('.row').trigger('mouseout'); 
        });
        
    },
    
    add_item: function() {

        jQuery('#editBox h3').text('Add Message');
        message1977_backend.reset_form();
        
        jQuery.fancybox.open({
            href : '#editBox'            
        }, message1977_backend.boxParam);
                
    },

    edit_item: function( item_id ) {

        var data = {
            action   : 'get',
            security : message1977_backend.nonce,
            item_id  : item_id
        };
    
        jQuery.post(message1977_backend.ajaxurl, data, function(response) {
            if ( response != -1 ) {
                if ( response.flag ) {
                    message1977_backend.reset_form();
                    
                    jQuery('#editBox h3').text('Edit Message - ID ' + response.data.item_id);
                                    
                    /** Fill form with data **/
                    jQuery('.message1977_edit input[name="item_id"]').val(response.data.item_id);
                    jQuery('.message1977_edit input[name="name"]').val(response.data.name);
                    jQuery('.message1977_edit textarea[name="content"]').val(response.data.content);
                    jQuery('.message1977_edit input[name="priority"]').val(response.data.priority);
                    jQuery('.message1977_edit select[name="position"] option[value="' + response.data.position + '"]').attr("selected", "selected");
                    jQuery('.message1977_edit input[name="sdelay"]').val(response.data.sdelay);
                    jQuery('.message1977_edit input[name="edelay"]').val(response.data.edelay);
                    jQuery('.message1977_edit select[name="publish"]').val(response.data.publish);
                    
                    if ( response.data.ptime )
                        jQuery('.message1977_edit input[name="ptime"]').val(response.data.ptime);
                    if ( response.data.etime )
                        jQuery('.message1977_edit input[name="etime"]').val(response.data.etime);
                        
                    jQuery('.message1977_edit select[name="when_to_show"] option[value="' + response.data.when_to_show + '"]').attr("selected", "selected");
                    
                    if ( response.data.when_to_show == 'specific' ) {
                        jQuery('.when_to_show').removeClass('hideIt');    
                    }
                    
                    jQuery('.message1977_edit input[name="page_id"]').val(response.data.page_id);
                    jQuery('.message1977_edit input[name="post_id"]').val(response.data.post_id);
                    jQuery('.message1977_edit input[name="cate_id"]').val(response.data.cate_id);
                    jQuery('.message1977_edit input[name="user_level"]').val(response.data.user_level);
                    jQuery('.message1977_edit input[name="custom_css"]').val(response.data.custom_css);
                                    
                    jQuery.fancybox.open({
                        href : '#editBox'            
                    }, message1977_backend.boxParam);
                } else if ( response.error ) {
                    alert('Error: ' + response.error);
                } else {
                    alert('Error: Please try again, or report this error to developer. [0x102] [Response : ' + response.flag + '] [' + response + ']'); 
                }    
            } else {
                alert('Error: Please try again, or report this error to developer. [0x101]');
            }
        }, 'json');       
                        
    },
        
    save_item: function( item_id ) {

        if ( message1977_backend.validate_form() ) {
            var data = {
                action   : 'save', 
                security : message1977_backend.nonce,
                item_id  : jQuery('.message1977_edit input[name="item_id"]').val(),
                name     : jQuery('.message1977_edit input[name="name"]').val(),
                content  : jQuery('.message1977_edit textarea[name="content"]').val(),
                priority : jQuery('.message1977_edit input[name="priority"]').val(),
                position : jQuery('.message1977_edit select[name="position"]').val(),
                sdelay   : jQuery('.message1977_edit input[name="sdelay"]').val(),
                edelay   : jQuery('.message1977_edit input[name="edelay"]').val(),
                publish  : jQuery('.message1977_edit select[name="publish"]').val(),
                
                ptime        : jQuery('.message1977_edit input[name="ptime"]').val(),
                etime        : jQuery('.message1977_edit input[name="etime"]').val(),
                when_to_show : jQuery('.message1977_edit select[name="when_to_show"]').val(),
                page_id      : jQuery('.message1977_edit input[name="page_id"]').val(),
                post_id      : jQuery('.message1977_edit input[name="post_id"]').val(),
                cate_id      : jQuery('.message1977_edit input[name="cate_id"]').val(),
                user_level   : jQuery('.message1977_edit input[name="user_level"]').val(),
                custom_css   : jQuery('.message1977_edit input[name="custom_css"]').val()
            };
        
            jQuery.post(message1977_backend.ajaxurl, data, function(response) {
                if ( response != -1 ) {
                    if ( response.flag ) {
                        window.location.reload();
                    } else if ( response.error ) {
                        alert('Error: ' + response.error);                    
                    } else {
                        alert('Error: Please try again, or report this error to developer. [0x202] [Response : ' + response.flag + '] [' + response + ']');
                    }
                } else {
                    alert('Error: Please try again, or report this error to developer. [0x201]');
                }
            }, 'json');       
         }
    },
    
    delete_item: function( item_id ) {
        if ( confirm("Are you sure you want to remove this message?") ) {
            var data = {
                action   : 'delete', 
                security : message1977_backend.nonce,
                item_id  : item_id
            };
        
            jQuery.post(message1977_backend.ajaxurl, data, function(response) {
                if ( response != -1 ) {
                    if ( response.flag ) {
                        window.location.reload();
                    } else if ( response.error ) {
                        alert('Error: ' + response.error);                    
                    } else {
                        alert('Error: Please try again, or report this error to developer. [0x302] [Response : ' + response.flag + '] [' + response + ']');
                    }
                } else {
                    alert('Error: Please try again, or report this error to developer. [0x301]');
                }
            }, 'json');
        }              
    },
    
    unpublish: function( item_id ) {
        var data = {
            action   : 'unpublish', 
            security : message1977_backend.nonce,
            item_id  : item_id
        };
    
        jQuery.post(message1977_backend.ajaxurl, data, function(response) {
            if ( response != -1 ) {
                if ( response.flag ) {
                    window.location.reload();
                } else if ( response.error ) {
                    alert('Error: ' + response.error);                    
                } else {
                    alert('Error: Please try again, or report this error to developer. [0x402] [Response : ' + response.flag + '] [' + response + ']');
                }
            } else {
                alert('Error: Please try again, or report this error to developer. [0x401]');
            }
        }, 'json');             
    },
    
    publish: function( item_id ) {
        var data = {
            action   : 'publish', 
            security : message1977_backend.nonce,
            item_id  : item_id
        };
    
        jQuery.post(message1977_backend.ajaxurl, data, function(response) {
            if ( response != -1 ) {
                if ( response.flag ) {
                    window.location.reload();
                } else if ( response.error ) {
                    alert('Error: ' + response.error);                    
                } else {
                    alert('Error: Please try again, or report this error to developer. [0x502] [Response : ' + response.flag + '] [' + response + ']');
                }
            } else {
                alert('Error: Please try again, or report this error to developer. [0x501]');
            }
        }, 'json');             
    },
    
    reset_form: function() {
        jQuery('.message1977_edit input[name="item_id"]').val('0');
        jQuery('.message1977_edit input[name="name"]').val('');
        jQuery('.message1977_edit textarea[name="content"]').val('');
        jQuery('.message1977_edit input[name="priority"]').val('100');
        jQuery('.message1977_edit select[name="position"] option:first-child').attr("selected", "selected");
        jQuery('.message1977_edit input[name="sdelay"]').val('0');
        jQuery('.message1977_edit input[name="edelay"]').val('0');
        jQuery('.message1977_edit select[name="publish"] option:first-child').attr("selected", "selected");
            
        jQuery('.message1977_edit input[name="ptime"]').val('yyyy-mm-dd');
        jQuery('.message1977_edit input[name="etime"]').val('yyyy-mm-dd');
        jQuery('.message1977_edit select[name="when_to_show"] option:first-child').attr("selected", "selected");
        
        if ( !jQuery('.when_to_show').hasClass('hideIt') ) {
            jQuery('.when_to_show').addClass('hideIt');   
        }
        
        jQuery('.message1977_edit input[name="page_id"]').val('');
        jQuery('.message1977_edit input[name="post_id"]').val('');
        jQuery('.message1977_edit input[name="cate_id"]').val('');
        jQuery('.message1977_edit input[name="user_level"]').val('');
    },
    
    validate_form: function () {
      
        var flag = true,
            msg  = '';
        
        if ( !jQuery('.message1977_edit input[name="name"]').val() ) {
            msg += 'Please enter a name/slug for your entry.\n';
        }
        if ( !jQuery('.message1977_edit textarea[name="content"]').val() ) {
            msg += 'Please enter message for your entry.\n';
        }
        
        if ( msg ) alert(msg);
        
        return flag;
    },
    
    boxParam: {
        maxWidth    : 650,
        maxHeight   : 480,
        fitToView   : false,
        width       : '100%',
        height      : '100%',
        autoSize    : false,
        closeClick  : false,
        openEffect  : 'elastic',
        closeEffect : 'elastic',
        openSpeed   : 'fast',
        closeSpeed  : 'fast'
    }
        
}


jQuery(document).ready(function() {
   
   message1977_backend.init();
    
});

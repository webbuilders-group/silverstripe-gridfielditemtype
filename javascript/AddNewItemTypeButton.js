(function($) {
    $.entwine('ss', function($) {
        $('.ss-gridfield .addNewItemTypeButton select.dropdown').entwine({
            onadd: function() {
                this._super();
                
                //Make sure the parent gets the width of the dropdown
                this.parent().width(this.width());
                
                var gridField=this.getGridField();
                var button=gridField.find('.addNewItemTypeButton .new-link');
                var href=button.attr('href');
                var select=this;
                href=href.replace(/(\?|&)ItemType=(.*?)$/, '');
                href+=(href.indexOf('?')>=0 ? '&':'?')+'ItemType='+encodeURIComponent($(this).val());
                
                button.attr('href', href);
                button.on('buttoncreate', function(e, ui) {
                    if(select.val()=='') {
                        button.addClass('disabled');
                    }
                });
            },
            
            onchange: function() {
                var gridField=this.getGridField();
                var button=gridField.find('.addNewItemTypeButton .new-link');
                var href=button.attr('href');
                
                href=href.replace(/(\?|&)ItemType=(.*?)$/, '');
                href+=(href.indexOf('?')>=0 ? '&':'?')+'ItemType='+encodeURIComponent($(this).val());
                
                button.attr('href', href);
                
                if($(this).val()=='') {
                    button.addClass('disabled');
                }else {
                    button.removeClass('disabled');
                }
            }
        });
	    
        /**
         * Overloads the LeftAndMain handle for grid field actions and use the href of the item type button
         */
	    $('.cms .ss-gridfield .new-item-type-add').entwine({
	        onmatch: function() {
                this._super();
                
	            var gridField=this.getGridField();
                var dropdown=gridField.find('.addNewItemTypeButton select.dropdown');
                if(dropdown.val()=='') {
                    this.addClass('disabled');
                }
	        },
	        onclick: function(e) {
	            var gridField=this.getGridField();
	            var dropdown=gridField.find('.addNewItemTypeButton select.dropdown');
	            if(dropdown.val()=='') {
	                statusMessage('You must select an option from the dropdown', 'bad');
	            }else {
	                $('.cms-container').loadPanel($(this).prop('href'));
	            }
	            
	            e.stopPropagation();
	            return false;
	        }
	    });
    });
    
    
    function statusMessage(text, type) {
        text = jQuery('<div/>').text(text).html(); // Escape HTML entities in text
        jQuery.noticeAdd({text: text, type: type, stayTime: 5000, inEffect: {left: '0', opacity: 'show'}});
    }
})(jQuery);
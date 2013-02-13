(function($) {
    $.entwine('ss', function($) {
        $('.ss-gridfield .addNewItemTypeButton select.dropdown').entwine({
            onmatch: function() {
                var gridField=this.getGridField();
                var button=gridField.find('.addNewItemTypeButton .new-link');
                var href=button.attr('href');
                var select=$(this);
                
                href=href.replace(/\?ItemType=(.*?)$/, '');
                href+='?ItemType='+$(this).val();
                
                button.attr('href', href);
                button.on('buttoncreate', function(e, ui) {
                    if(select.val()=='') {
                        button.button('option', 'disabled', true);
                    }
                });
            },
            
            onchange: function() {
                var gridField=this.getGridField();
                var button=gridField.find('.addNewItemTypeButton .new-link');
                var href=button.attr('href');
                
                href=href.replace(/\?ItemType=(.*?)$/, '');
                href+='?ItemType='+$(this).val();
                
                button.attr('href', href);
                
                if($(this).val()=='') {
                    button.button("option", "disabled", true);
                }else {
                    button.button("option", "disabled", false);
                }
            }
        });
    });
})(jQuery);
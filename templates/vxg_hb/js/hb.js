window.addEvent('domready', function(){
    
    if (!$defined($('ImageText'))){
        $$('.article img').each(function(el, index){
            var description = el.getProperty('title');

            var divImageText = new Element('div', {
                'id': 'ImageText',
                'styles':{
                    'z-index':10,
                    'opacity':1,
                    'position':'relative'
                }
            });
            var divImageTextBg = new Element('div',{
                'id' : 'ImageTextBg',
                'styles' : {
                    'z-index':9
                }
            });
            divImageTextBg.setHTML('&nbsp;');
            divImageText.setHTML(description);
            divImageTextBg.injectInside(el.getParent());
            divImageText.injectInside(el.getParent());
            
        });
    }
    if ($defined($('searchForm'))){
       
        $('searchForm').addEvent('submit', function(e) {
            /**
	 * Prevent the submit event
	 */
            new Event(e).stop();
            if (document.searchForm.searchword.value !='') {
                document.searchForm.option.value = 'com_search';
                document.searchForm.method = "post";
        
            }
            else {
                document.searchForm.option.value = 'com_kid';
                document.searchForm.method = "post";
                document.searchForm.action = "index2.php?option=com_kid&task=search&Itemid=16";
     
            }
            
            //document.searchForm.submit();
            /**
	 * This empties the log and shows the spinning indicator
	 */
            var content = $('content').empty();
            content.addClass('ajax-loading');
            
            /**
	 * send takes care of encoding and returns the Ajax instance.
	 * onComplete removes the spinner from the log.
	 */
            this.send({
                update: content,
                onComplete: function() {                    
                    content.removeClass('ajax-loading');
                    
                }
            });
        });
    }
    
});


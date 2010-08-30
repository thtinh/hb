window.addEvent('domready', function(){
    
    if (!$defined($('ImageText'))){
        $$('.article img').each(function(el, index){
            var description = el.getProperty('title');

            var divImageText = new Element('div', {
                'id': 'ImageText',
                'styles':{'z-index':10,'opacity':1,'position':'relative'}
            });
            var divImageTextBg = new Element('div',{
                'id' : 'ImageTextBg',
                'styles' : {'z-index':9}
            });
            divImageTextBg.setHTML('&nbsp;');
            divImageText.setHTML(description);
            divImageTextBg.injectInside(el.getParent());
            divImageText.injectInside(el.getParent());
            
        });
    }
    
});


window.addEvent('domready', function(){
    var addImages = function(images) {
        
        if ($defined($("thumbnail"))) $("thumbnail").remove();
        var divThumbnail = new Element('div', {
            'id': 'thumbnail',
            'style':"float:left"
        });
        var ulThumbnail = new Element('ul',{
            'id':'thumbnail-content'
        });
        count = 0;
        images.each(function(image) {
            
            var el = new Element('li', {
                'class': 'album-thumbnail',
                'id' : image.id+'-image'
            });

            //if (count > 4) el.addClass('hide');
            if (count == 0 ){
                el.addClass('active');
                loadImage(image.id);
            }
            var img = new Element('img', {
                'src': image.link,
                'width' : '82px',
                'height': '62px'
            }).injectInside(el);
            
            el.injectInside(ulThumbnail);
            count++;
        });
        ulThumbnail.injectInside(divThumbnail);
        divThumbnail.injectAfter($('back'));
        new iCarousel("thumbnail-content", {
            idPrevious: "back",
            idNext: "next",
            idToggle: "undefined",
            item: {
                klass: "album-thumbnail",
                size: 104
            },
            animation: {
                type : "scroll",
                duration: 1000,
                amount: 5
            }
        });
        $$('.album-thumbnail').each(function(el){
            el.addEvent('click',function(e){
                e = new Event(e).stop();
                id = el.id.toInt();
                $$('.album-thumbnail').removeClass('active');
                el.addClass('active');
                loadImage(id);
            });
        });

            
    };
    var addAlbumInfo = function (catInfo){
        intro = $('intro');
        intro.empty();
        
        if ($defined(catInfo[0]))
            intro.setHTML(catInfo[0].albumDescription);
    };
    var addImage = function(image) { 
        pic = $("picture");
        divDescription = $("description");
        if($defined(divDescription))
            pic.empty();
        else 
            pic = new Element('div',{
                id:'picture'
            });
        
      
        if ($defined(divDescription))
            divDescription.empty();
        else 
            divDescription = new Element('div',{
                id:'description'
            });
        
        
        
   
        var img = new Element('img', {
            'src': image.link,
            'style' : 'max-width:450px;max-height:340px'
            
        }).injectInside(pic);
        pic.injectAfter($('album-thumbnail'));
       
        divDescription.setHTML(image.description)
        divDescription.injectAfter($('picture'));
    };
    var loadAlbum = function(id){
        var url = 'index.php?option=com_phocagallery&view=category&id='+id+'&format=json';
        var request = new Json.Remote(url, {
            onComplete: function(jsonObj) {
                addImages(jsonObj);
                addAlbumInfo(jsonObj);
            }
        }).send();
        
    };
    var loadImage = function(id){
        
        var url = 'index.php?option=com_phocagallery&view=detail&id='+id+'&format=json';
        var request = new Json.Remote(url, {
            onComplete: function(jsonObj) {
                addImage(jsonObj);
            }
        }).send();

    };
    
    $$('.album-left a').addEvent('click', function(e) {
        e = new Event(e).stop();
        id = this.getParent().id.toInt();
        loadAlbum(id);        
    });
    
    //load first album when the page is loaded
    
    $('album-left').getElement('li').addClass('active');
    id = $('album-left').getElement('li').id.toInt();
    loadAlbum(id);

});




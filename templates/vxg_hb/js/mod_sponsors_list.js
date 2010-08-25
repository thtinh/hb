window.addEvent('domready', function(){
    var loadSponsorsList = function (limitstart){
        var url = 'index.php?option=com_vxgajax&task=SponsorsList&catid=11&limitstart='+limitstart+'&format=raw';
        var request = new Json.Remote(url, {
            onComplete: function(jsonObj) {
                addSponsors(jsonObj.content);
                if (!$('pagination').hasClass('pagination')){
                    addPaging(jsonObj.pagination);
                }
            }
        }).send();
    };
    var addSponsors = function (content){
        //if($defined(content)) alert(content.length);
        list1 = $('sponsorlist-1');
        list2 = $('sponsorlist-2');
        list1.empty();
        list2.empty();

        count = 0;
        content.each(function (sponsor){
            var li = new Element('li', {
                'class': 'sponsor',
                'id' : sponsor.id+'-sponsor'
            });
            var el = new Element('a', {
                'href': sponsor.link,
                'title' : sponsor.title
            }).setHTML(sponsor.title).injectInside(li);
            if (count < content.length/2 )
                li.injectInside(list1);
            else li.injectInside(list2);
            count++;
        });
    };
    var addPaging = function (paging){
        divPaging = $('pagination');
        divPaging.addClass('pagination');
        divPaging.empty();
        el = null;

        limitstart = paging['limitstart'];
        limit = paging['limit'];
        currentPage = paging['pages.current'];
        
        for (var i=0;i<paging['pages.total'];i++){
            if (i+1==currentPage){
                el = new Element('a',{
                    'class':'pl pl-a'
                    
                });
                el.setHTML(i+1);
            }
            else{
                el = new Element('a',{
                    'class':'pl'
                    
                });
                el.setHTML(i+1);       
            }
            el.injectInside(divPaging);
        }
   
        $$('#pagination a').each(function (el,index){
            el.addEvent('click',function(e){
                e = new Event(e).stop();
                loadSponsorsList(limit*index);
                $$('#pagination a').removeClass("pl-a");
                 el.addClass("pl-a");
            });
            i++;
        });

    };
    loadSponsorsList(0,8);
});




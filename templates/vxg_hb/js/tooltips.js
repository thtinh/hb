window.addEvent('domready', function(){
    if ($$('.SponsorToolTip')){
        var SponsorToolTip = new Tips($$('.SponsorToolTip'), {
            initialize:function(){
                this.fx = new Fx.Style(this.toolTip, 'opacity', {
                    duration: 500,
                    wait: false
                }).set(0);
            },
            onShow: function(toolTip) {
                this.fx.start(1);
            },
            onHide: function(toolTip) {
                this.fx.start(0);
            }
        });
    };
    
    
});

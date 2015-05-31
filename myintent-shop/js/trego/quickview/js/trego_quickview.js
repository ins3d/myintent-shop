jQuery.noConflict();
jQuery(function($) {
    var myhref,qsbtt;
    var opt = {
        itemClass : ['.products-grid li.item', '.products-grid li.item div.item-content', '.products-list li.item', '.filter-products .products div.item'],
        aClass : 'a.product-image',
        imgClass: '.product-image img'
    };

    function ieVersion(){
        var value = -1;
        if (navigator.appName == 'Microsoft Internet Explorer'){
            var agent = navigator.userAgent;
            var reg  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (reg.exec(agent) != null)
                value = parseFloat( RegExp.$1 );
        }
        return value;
    }

    function readHref(){
        var mypath = arguments[0];
        var patt = /\/[^\/]{0,}$/ig;
        if(mypath[mypath.length-1]=="/"){
            mypath = mypath.substring(0,mypath.length-1);
            return (mypath.match(patt)+"/");
        }
        return mypath.match(patt);
    }

    function strTrim(){
        return arguments[0].replace(/^\s+|\s+$/g,"");
    }

    function _quickviewJnit(){
        var selectorObj = arguments[0];
        var listprod = $$(selectorObj.itemClass);
        var mypath = 'quickview/index/view';
        var baseUrl = Trego.Quickview.BASE_URL + mypath;

        var _quickviewHref = "<a id=\"trego_quickview_handler\" href=\"#\" style=\"position:absolute;top:0;left:-999em; font-size: 16px; padding: 5px;\">"+Trego.Quickview.QV_TITLE+"</a>";
        $(document.body).append(_quickviewHref);
        var quickviewHandlerImg = $('#trego_quickview_handler img');
        $.each(listprod, function(index, value) {
            var reloadurl = baseUrl;
            //get reload url
            myhref = $(value).find(selectorObj.aClass);
            if (myhref.length == 0) return;
            var product_id = myhref.attr('product_id');

            reloadurl = baseUrl+"/id/"+product_id;
            //end reload url
            
            $(this).find(".product-image-area").mouseover(function() {
                if ($(window).width() <= 768 ) return;
                if (this.className == 'i_new') return;
                $('#trego_quickview_handler').appendTo($(this));
                $('#trego_quickview_handler img').show();
                $('#trego_quickview_handler').css('top','50%');
                $('#trego_quickview_handler').css('left','50%');
                var margin_left = "-14px";
                var margin_top = "-14px";
                if($('#trego_quickview_handler').find("img").height()>0)
                    margin_top = '-'+$('#trego_quickview_handler').height()/2+'px';
                if($('#trego_quickview_handler').find("img").width()>0)
                    margin_left = '-'+$('#trego_quickview_handler').width()/2+'px';
                $('#trego_quickview_handler').css('margin-top',margin_top);
                $('#trego_quickview_handler').css('margin-left',margin_left);
                $('#trego_quickview_handler').attr('href',reloadurl).fadeIn();
                return false;
            }).mouseleave(function() {
                $('#trego_quickview_handler').hide();
                return false;
            });
        });

        //fix bug image disapper when hover
        $('#trego_quickview_handler')
            .bind('mouseover', function() {
                $(this).show();
                return false;
            })
            .bind('click', function() {
                $(this).hide();
            });
        //insert quickview popup
        $('#trego_quickview_handler').fancybox({
            'type'              : 'iframe',
            'scrolling'         : 'auto',
            'padding'           : 0,
            'margin'            : 0,
            'autoSize'          : false,
            'width'             : Trego.Quickview.QV_FRM_WIDTH,
            'height'            : 'auto',
            'afterLoad'        : function() {
                $('#fancybox-content').height('auto');
            }
        });
    }

    //end base function

    if (typeof jqSmartCatalog != 'undefined') {
        jqSmartCatalog(document).bind('smart-pagination-ajax-after', function() {
            _quickviewJnit(opt);
        });
    }

    _quickviewJnit(opt);
});



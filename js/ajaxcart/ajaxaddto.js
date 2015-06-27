jQuery.noConflict();
	function setAjaxData(data,iframe,type){
		if(data.status == 'ERROR'){
			alert(data.message);
		}else{
            if(jQuery('.footer-menu .links')){
                jQuery('.footer-menu .links').replaceWith(data.toplink);
            }
	        if(jQuery('.mini-cart-layer')){
	            jQuery('.mini-cart-layer').replaceWith(data.minicart);
	        }
			
	        jQuery.fancybox.close();
			if(type!='item'){
				jQuery('#after-loading-success-message').show();
			}
		}
	}
	function setLocationAjax(url,id,type){
        if (url.indexOf("?")){
            url = url.split("?")[0];
        }
		url += 'isAjax/1';
		url = url.replace("checkout/cart","ajaxcart/index");
		if(window.location.href.match('https://') && !url.match('https://')){
            url = url.replace('http://','https://');
        }
        if(window.location.href.match('http://') && !url.match('http://')){
            url = url.replace('https://','http://');
        }
		jQuery('#loading-mask').show();

		try {
			jQuery.ajax( {
				url : url,
				dataType : 'json',
				success : function(data) {
					jQuery('#loading-mask').hide();
         			setAjaxData(data,false,type);				
				}
			});
		} catch (e) {
		}
	}

    function showOptions(id){
		initFancybox();
        jQuery('#fancybox'+id).trigger('click');
    }
	
	function initFancybox(){
		jQuery.noConflict();
		jQuery(document).ready(function(){
		jQuery('.fancybox').fancybox({
				hideOnContentClick : true,
				width: 382,
				autoDimensions: true,
				type : 'iframe',
				showTitle: false,
				scrolling: 'no',
				onComplete: function(){
					jQuery('#fancybox-frame').load(function() { // wait for frame to load and then gets it's height
						jQuery('#fancybox-content').height(jQuery(this).contents().find('body').height()+100);
						jQuery.fancybox.resize();
					});

				}
			}
		);
		});   	
	}
	function ajaxCompare(url,id){
        if (url.indexOf("?")){
            url = url.split("?")[0];
        }
	    url = url.replace("catalog/product_compare/add","ajaxcart/whishlist/compare");
	    url += 'isAjax/1/';
		if(window.location.href.match('https://') && !url.match('https://')){
            url = url.replace('http://','https://');
        }
        if(window.location.href.match('http://') && !url.match('http://')){
            url = url.replace('https://','http://');
        }
	    jQuery('#loading-mask').show();

	    jQuery.ajax( {
		    url : url,
		    dataType : 'json',
		    success : function(data) {
			    jQuery('#loading-mask').hide();
			    if(data.status == 'ERROR'){
				    alert(data.message);
			    }else{
				    alert(data.message);
				    if(jQuery('.block-compare').length){
                        jQuery('.block-compare').replaceWith(data.sidebar);
                    }else{
                        if(jQuery('.col-right').length){
                    	    jQuery('.col-right').prepend(data.sidebar);
                        }
                    }
			    }
		    }
	    });
    }
    function ajaxWishlist(url,id){
        if (url.indexOf("?")){
            url = url.split("?")[0];
        }
	    url = url.replace("wishlist/index","ajaxcart/whishlist");
	    url += 'isAjax/1/';
		if(window.location.href.match('https://') && !url.match('https://')){
            url = url.replace('http://','https://');
        }
        if(window.location.href.match('http://') && !url.match('http://')){
            url = url.replace('https://','http://');
        }
	    jQuery('#loading-mask').show();
	    jQuery.ajax( {
		    url : url,
		    dataType : 'json',
		    success : function(data) {
			    jQuery('#loading-mask').hide();
			    if(data.status == 'ERROR'){
				    alert(data.message);
			    }else{
				    alert(data.message);
				    if(jQuery('.footer-menu .links')){
                        jQuery('.footer-menu .links').replaceWith(data.toplink);
                    }
			    }
		    }
	    });
    }
    function deleteAction(deleteUrl,itemId,msg){
	    var result =  confirm(msg);
	    if(result==true){
		    setLocationAjax(deleteUrl,itemId,'item')
	    }else{
		    return false;
	    }
    }
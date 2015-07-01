jQuery(document).ready(function(){
    if(jQuery(".header .form-search input.input-text#search").val() != 'Search...'){
        jQuery("#mob_search").css('display', 'none');
        jQuery(".header .form-search input.input-text#search").css('display', 'block');
        jQuery(".header .form-search button.button").css('display', 'block');
    }
    jQuery("#mob_search").click(function(){
        jQuery(this).css('display', 'none');
        jQuery(".header .form-search input.input-text#search").css('display', 'block');
        jQuery(".header .form-search button.button").css('display', 'block');
        jQuery(".header .form-search input.input-text#search").focus();
    });
    jQuery(".nav-container #mobnav").click(function(){
        if (jQuery(this).parent().hasClass('expanded')){
            jQuery(this).parent().removeClass('expanded');
/* Begin customization */			
//            jQuery(this).parent().children('#nav').fadeOut();
            jQuery(this).parent().children('#nav').fadeOut(400, function(){				
				jQuery(this).get(0).style.display = ''; // remove inline display: none styling
			});
/* End customization */			
        } else {
            jQuery(this).parent().addClass('expanded');
            jQuery(this).parent().children('#nav').fadeIn();
        }
        jQuery("#nav ul.level1").each(function(){
            var scrolltop = 0;
            if(jQuery('html').scrollTop()>1)
                scrolltop = jQuery('html').scrollTop();
            else if(jQuery('body').scrollTop()>1)
                scrolltop = jQuery('body').scrollTop();
            jQuery(this).css('top', jQuery(this).parent().offset().top-scrolltop);
        });
    });
/* Begin customization */				
	jQuery(document).ready(function() {
		var path = window.location.pathname;
		
		if (path.indexOf("create") > -1 || path.indexOf("bracelet") > -1 || path.indexOf("necklace") > -1)
		{
			var menuTab = "Create";
		}
		else if (path.indexOf("about") > -1)
		{
			var menuTab = "AboutUs";
		}
		else if (path.indexOf("resources") > -1)
		{
			var menuTab = "Resources";
		}
		else if (path.indexOf("news") > -1)
		{
			var menuTab = "News";
		}
		else if (path.indexOf("contacts") > -1)
		{
			var menuTab = "Contacts";
		}
		else if (path.indexOf("terms") > -1)
		{
			var menuTab = "Terms";
		}
		else if (path.indexOf("account") > -1)
		{
			var menuTab = "MyAccount";
		}
		else if (path.indexOf("blog") > -1)
		{
			var menuTab = "Blog";
		}
		jQuery("#" + menuTab).find("a").addClass("active");
	});

/* End customization */	
    if(jQuery(window).width()<=1024 && jQuery(window).width()>=768 && jQuery('#nav').height()>40)
        jQuery('.header .header-bground').addClass('menu-expanded');
    jQuery(window).resize(function(){
        if(jQuery(window).width()<=1024 && jQuery(window).width()>=768 && jQuery('#nav').height()>40)
            jQuery('.header .header-bground').addClass('menu-expanded');
    })
/* customization
    if(jQuery(window).height()>550)
        jQuery('.box-scroll').css('height',(jQuery(window).height()-400)+"px");
    else
        jQuery('.box-scroll').css('height',"150px");
    
    jQuery(window).resize(function(){
        if(jQuery(window).height()>550)
            jQuery('.box-scroll').css('height',(jQuery(window).height()-400)+"px");
        else
            jQuery('.box-scroll').css('height',"150px");
        if(jQuery(window).width() <= 1024)
        {
            jQuery(".box-scroll").getNiceScroll().remove();
        } else {
            jQuery('.box-scroll').niceScroll({zindex : 51, objfixed: true});
        }
    });
*/
});
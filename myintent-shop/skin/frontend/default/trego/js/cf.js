function slideUp()
{
    jQuery('#topCartContent:visible').slideUp(500);
    jQuery('.mini-cart-layer').addClass('mini-cart-layer-up');
    jQuery('.mini-cart-layer').removeClass('mini-cart-layer-down');
}

function slideDown()
{
    jQuery('#topCartContent:hidden').slideDown(500);
    /*startTimer()*/ /* optional*/
    jQuery('.mini-cart-layer').addClass('mini-cart-layer-down');
    jQuery('.mini-cart-layer').removeClass('mini-cart-layer-up');
}

function toggleTopCart()
{
    if(jQuery('#topCartContent').is(':visible'))
    {
        slideUp();
    } else {
        slideDown();
    }
}

var timer;
function startTimer()
{
    timer = setTimeout(function(){
        slideUp();
    }, 5000);
}

jQuery(document).ready(function(){
    jQuery('.mini-cart-layer .top-cart .block-title #cartHeader').click(function(){
        toggleTopCart();
    });

    jQuery('.mini-cart-layer .top-cart .block-title #cartHeader').mouseover(function(){
        clearTimeout(timer);
    }).mouseout(function(){
        startTimer();
    });

    jQuery("#topCartContent").mouseover(function() {
        clearTimeout(timer);
    }).mouseout(function(){
        startTimer();
    });
});
$(document).ready(function(){
    
    var rtl = false;
    
    if($("html").attr("dir") == 'rtl'){
         rtl = true;
        $('.main-wrapper').addClass('rtl-style');
    }
    
    /* Header Fixed */
    
    $(window).scroll(function(){
            
        if ($(window).scrollTop() >= 100) {
            $('#header').addClass('fixed-header');
        }
        else {
            $('#header').removeClass('fixed-header');
        }
              
    });
    
    
    /* Hamburger */
    
    $(".hamburger").click(function () {
        $(".mobile-menu").addClass('active');
        $("body").addClass('active');
    });

    $(".is-closed").click(function () {
        $(".mobile-menu").removeClass('active');
        $("body").removeClass('active');
    });
    
    $(".hamburger-dash").click(function () {
        $(".aside-menu").addClass('active');
    });

    $(".closed").click(function () {
        $(".aside-menu").removeClass('active');
    });
   
    
});
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
   
    /* Owl Carousel */
    
    $("#subscriptions-slider").owlCarousel({
        loop: true,
        margin: 20,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                margin: 10,
            },
            992: {
                items: 1,
            },
            1199: {
                items: 3,
            }
        },
        dots: false,
        nav: true,
        rtl: rtl,
        autoplay: false,
        navText:['<i class="fa-solid fa-angle-left"></i>','<i class="fa-solid fa-angle-right"></i>']
    });
    
});
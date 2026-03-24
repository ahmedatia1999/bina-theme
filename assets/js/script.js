$(document).ready(function () {

    const header = $('#header');
    const hamburger = $('.hamburger');
    const mobileMenu = $('.mobile-menu');
    const overlay = $('#mobile-overlay');
    const closeBtn = $('.close');

    /* SCROLL */
    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 20) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }
    });

    /* OPEN MENU */
    hamburger.on('click', function () {
        mobileMenu.addClass('active');
        overlay.addClass('active');
    });

    /* CLOSE MENU */
    function closeMenu() {
        mobileMenu.removeClass('active');
        overlay.removeClass('active');
    }

    closeBtn.on('click', closeMenu);
    overlay.on('click', closeMenu);

    /* ============================================
       REVIEWS CAROUSEL
    ============================================ */
    const reviewsSwiper = new Swiper('.reviews-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        dir: 'rtl',
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.reviews-swiper .swiper-button-next',
            prevEl: '.reviews-swiper .swiper-button-prev',
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });

    /* ============================================
   ACCORDION
============================================ */
    const accordionItems = $('[data-orientation="vertical"]').has('[aria-controls]');
    

    accordionItems.each(function () {

        $(this).find('button').on('click', function () {

            const button = $(this);
            const contentId = button.attr('aria-controls');
            const content = $('#' + CSS.escape(contentId));

            const isOpen = button.attr('aria-expanded') === 'true';

            // اقفل كل العناصر
            accordionItems.each(function () {
                const btn = $(this).find('button');
                const cId = btn.attr('aria-controls');
                const c = $('#' + CSS.escape(cId));

                btn.attr('aria-expanded', 'false');
                btn.attr('data-state', 'closed');

                c.attr('data-state', 'closed');
                c.attr('hidden', true);
            });

            // لو كان مفتوح → اقفله (وده اللي كان ناقص)
            if (isOpen) {
                return;
            }

            // افتح الحالي
            button.attr('aria-expanded', 'true');
            button.attr('data-state', 'open');

            content.attr('data-state', 'open');
            content.removeAttr('hidden');
        });

    });


    /* ============================================
   PARTNERS MARQUEE
============================================ */
    function initMarquee() {
        const track = $('.marquee-track');
        if (!track.length) return;

        // نسخ المحتوى للحصول على حركة لا نهائية
        const items = track.html();
        track.append(items);

        let position = 0;
        const speed = 0.5; // عدل السرعة حسب رغبتك
        const isRTL = $('html').attr('dir') === 'rtl';

        function animate() {
            position += isRTL ? speed : -speed;

            const totalWidth = track[0].scrollWidth / 2;

            if (isRTL && position >= totalWidth) {
                position = 0;
            } else if (!isRTL && position <= -totalWidth) {
                position = 0;
            }

            track.css('transform', `translateX(${position}px)`);
            requestAnimationFrame(animate);
        }

        animate();

        // إيقاف عند hover
        track.closest('.marquee-wrapper').on('mouseenter', function () {
            track.css('animation-play-state', 'paused');
        }).on('mouseleave', function () {
            track.css('animation-play-state', 'running');
        });
    }

    $(document).ready(function () {
        initMarquee();
    });



});
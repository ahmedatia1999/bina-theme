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
    $(document).on('click', '[data-orientation="vertical"] button', function () {
        const btn = $(this);
        const isOpen = btn.attr('aria-expanded') === 'true';
        const contentId = btn.attr('aria-controls');

        // أغلق كل العناصر - نفس الطريقة الآمنة
        $('[data-orientation="vertical"] button').each(function () {
            const otherId = $(this).attr('aria-controls');
            const otherContent = otherId ? document.getElementById(otherId) : null;

            $(this).attr('aria-expanded', 'false');
            $(this).closest('[data-state]').attr('data-state', 'closed');
            $(this).find('svg').css('transform', 'rotate(0deg)');

            if (otherContent) {
                $(otherContent).css('max-height', '0').removeAttr('hidden');
            }
        });

        // افتح المضغوط لو كان مغلق
        if (!isOpen) {
            const content = document.getElementById(contentId);
            if (content) {
                btn.attr('aria-expanded', 'true');
                btn.closest('[data-state]').attr('data-state', 'open');
                btn.find('svg').css('transform', 'rotate(180deg)');
                $(content).css('max-height', content.scrollHeight + 'px');
            }
        }
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
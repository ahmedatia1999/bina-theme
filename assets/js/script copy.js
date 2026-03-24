(function ($) {
    'use strict';

    /* =============================================
       document.ready
       ============================================= */
    $(document).ready(function () {

        /* =============================================
           RTL / LTR
           ============================================= */
        if ($("html").attr("dir") === 'ltr') {
            $('.main-wrapper').addClass('ltr-style');
        }

        /* =============================================
           Mobile Menu - Open
           ============================================= */
        $('.hamburger-btn').on('click', function () {
            $('.mobile-menu').addClass('active');
            $(this).addClass('active');
            $('body').css('overflow', 'hidden');
        });

        /* =============================================
           Mobile Menu - Close (X button)
           ============================================= */
        $('.is-closed').on('click', function () {
            closeMobileMenu();
        });

        /* =============================================
           Mobile Menu - Close (click outside)
           ============================================= */
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.mobile-menu, .hamburger-btn').length) {
                closeMobileMenu();
            }
        });

        /* =============================================
           Mobile Menu - Close (after link click)
           ============================================= */
        $('.mobile-menu .page-scroll, .mobile-menu .btn-contact-mobile').on('click', function () {
            closeMobileMenu();
        });

        /* =============================================
           Helper: Close Mobile Menu
           ============================================= */
        function closeMobileMenu() {
            $('.mobile-menu').removeClass('active');
            $('.hamburger-btn').removeClass('active');
            $('body').css('overflow', '');
        }

        /* =============================================
           Sticky Header
           ============================================= */
        var $header = $('#header');
        var $placeholder = $('#header-placeholder');
        var headerHeight = $header.outerHeight();
        var isSticky = false;

        if (!$placeholder.length) {
            $header.after('<div id="header-placeholder"></div>');
            $placeholder = $('#header-placeholder');
        }

        $(window).on('scroll.stickyHeader', function () {
            var scrollTop = $(window).scrollTop();

            if (scrollTop >= headerHeight && !isSticky) {
                isSticky = true;
                headerHeight = $header.outerHeight();
                $placeholder.css('height', headerHeight + 'px').addClass('active');
                $header.addClass('is-sticky');
            } else if (scrollTop < headerHeight && isSticky) {
                isSticky = false;
                $header.removeClass('is-sticky');
                $placeholder.removeClass('active').css('height', '0');
            }
        });

        $(window).on('resize.stickyHeader', function () {
            if (!isSticky) {
                headerHeight = $header.outerHeight();
            }
        });

        /* =============================================
           Practice Cards Accordion
           ============================================= */
        if ($('.section_practice .practice-card').length) {

            var $cards = $('.section_practice .practice-card');
            var isAnimating = false;

            // Desktop only (> 992px)
            function isMobile() {
                return $(window).width() <= 992;
            }

            $cards.on('click', function () {
                if (isAnimating || isMobile()) return;

                var $clicked = $(this);
                if ($clicked.hasClass('active')) return;

                isAnimating = true;

                // ── الكارد الحالي المفتوح ──
                var $currentActive = $cards.filter('.active');
                var $currentContent = $currentActive.find('.practice-card-content');

                // ── الكارد الجديد ──
                var $newContent = $clicked.find('.practice-card-content');

                // 1. أخفي محتوى الكارد الحالي
                gsap.to($currentContent, {
                    autoAlpha: 0,
                    duration: 0.2,
                    ease: 'power2.in',
                    onComplete: function () {

                        // 2. بدّل الـ active class
                        $currentActive.removeClass('active');
                        $clicked.addClass('active');

                        // 3. ظهور محتوى الكارد الجديد
                        gsap.fromTo($newContent,
                            { autoAlpha: 0, y: 15 },
                            {
                                autoAlpha: 1,
                                y: 0,
                                duration: 0.45,
                                ease: 'power3.out',
                                delay: 0.15,
                                onComplete: function () {
                                    isAnimating = false;
                                }
                            }
                        );

                        // 4. أنيمشن السهم
                        gsap.fromTo($clicked.find('.practice-card-arrow'),
                            { rotation: -45, autoAlpha: 0 },
                            { rotation: 0, autoAlpha: 1, duration: 0.4, ease: 'back.out(1.4)', delay: 0.2 }
                        );
                    }
                });
            });

            // Hover effect على الكروت المغلقة
            $cards.on('mouseenter', function () {
                if (isMobile()) return;
                var $card = $(this);
                if (!$card.hasClass('active')) {
                    gsap.to($card.find('.practice-card-arrow'), {
                        y: -3,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                }
            }).on('mouseleave', function () {
                if (isMobile()) return;
                var $card = $(this);
                if (!$card.hasClass('active')) {
                    gsap.to($card.find('.practice-card-arrow'), {
                        y: 0,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                }
            });
        }

        /* =============================================
           GSAP Animations
           ============================================= */
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {

            // ✅ FIX: إيقاف warnings الخاصة بـ targets مش موجودة في الصفحة الحالية
            gsap.config({ nullTargetWarn: false });

            gsap.registerPlugin(ScrollTrigger);

            /* ------------------------------------------
               1. Hero Section
               ------------------------------------------ */
            var heroTl = gsap.timeline({ delay: 0.3 });

            heroTl
                .fromTo('.section_hero .hero-title',
                    { autoAlpha: 0, y: 60 },
                    { autoAlpha: 1, y: 0, duration: 1, ease: 'power3.out' }
                )
                .fromTo('.section_hero .hero-desc',
                    { autoAlpha: 0, y: 40 },
                    { autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out' },
                    '-=0.5'
                )
                .fromTo('.section_hero .hero-buttons .btn-contact-header',
                    { autoAlpha: 0, y: 30 },
                    { autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out' },
                    '-=0.4'
                );

            /* ------------------------------------------
               2. About Intro Section
               ------------------------------------------ */
            gsap.fromTo('.section_about_intro .about-intro-title',
                { autoAlpha: 0, x: -50 },
                {
                    autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_about_intro',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_about_intro .about-intro-btn',
                { autoAlpha: 0, x: 50 },
                {
                    autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_about_intro',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_about_intro .stat-item',
                { autoAlpha: 0, y: 50 },
                {
                    autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.15,
                    scrollTrigger: {
                        trigger: '.section_about_intro .about-intro-stats',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            /* ------------------------------------------
               3. Practice Areas Section
               ------------------------------------------ */
            gsap.fromTo('.section_practice .practice-title',
                { autoAlpha: 0, y: 40 },
                {
                    autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_practice',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_practice .practice-card',
                { autoAlpha: 0, x: 60 },
                {
                    autoAlpha: 1, x: 0, duration: 0.7, ease: 'power3.out', stagger: 0.12,
                    scrollTrigger: {
                        trigger: '.section_practice .practice-cards-wrapper',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            /* ------------------------------------------
               4. Operating Model Section
               ------------------------------------------ */
            gsap.fromTo('.section_operating_model .operating-title',
                { autoAlpha: 0, x: -50 },
                {
                    autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_operating_model',
                        start: 'top 80%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_operating_model .operating-left p',
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.15,
                    scrollTrigger: {
                        trigger: '.section_operating_model',
                        start: 'top 75%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_operating_model .operating-right',
                { autoAlpha: 0, x: 80 },
                {
                    autoAlpha: 1, x: 0, duration: 1, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_operating_model',
                        start: 'top 80%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            /* ------------------------------------------
               5. Services Packages Section
               ------------------------------------------ */
            gsap.fromTo('.section_services_packages .services-packages-label',
                { autoAlpha: 0, x: -50 },
                {
                    autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_services_packages',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo(
                ['.section_services_packages .services-packages-title',
                    '.section_services_packages .services-packages-desc'],
                { autoAlpha: 0, y: 40 },
                {
                    autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', stagger: 0.15,
                    scrollTrigger: {
                        trigger: '.section_services_packages .services-packages-right',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_services_packages .services-package-card',
                { autoAlpha: 0, y: 60 },
                {
                    autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.13,
                    scrollTrigger: {
                        trigger: '.section_services_packages .services-packages-cards',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            /* ------------------------------------------
               6. Partners Section
               ------------------------------------------ */
            gsap.fromTo('.section_partners .partners-title',
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_partners',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_partners .partner-item',
                { autoAlpha: 0, y: 40, scale: 0.9 },
                {
                    autoAlpha: 1, y: 0, scale: 1, duration: 0.6, ease: 'back.out(1.4)', stagger: 0.15,
                    scrollTrigger: {
                        trigger: '.section_partners .partners-logos',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            /* ------------------------------------------
               7. Strategic Pillars Section
               ------------------------------------------ */
            gsap.fromTo('.section_strategic_pillars .strategic-pillars-title',
                { autoAlpha: 0, y: 50 },
                {
                    autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_strategic_pillars',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_strategic_pillars .strategic-pillars-desc',
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_strategic_pillars .strategic-pillars-head',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_strategic_pillars .pillar-item-wrapper:first-child .pillar-item',
                { autoAlpha: 0, x: -60 },
                {
                    autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_strategic_pillars .strategic-pillars-row',
                        start: 'top 80%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_strategic_pillars .pillar-item-wrapper:last-child .pillar-item',
                { autoAlpha: 0, x: 60 },
                {
                    autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_strategic_pillars .strategic-pillars-row',
                        start: 'top 80%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.section_strategic_pillars .pillars-center-img',
                { autoAlpha: 0, y: 80 },
                {
                    autoAlpha: 1, y: 0, duration: 1, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.section_strategic_pillars .strategic-pillars-row',
                        start: 'top 70%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            /* ------------------------------------------
               8. Footer
               ------------------------------------------ */
            gsap.fromTo('.footer-logo',
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: 'footer .footer-top',
                        start: 'top 90%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo(['.footer-tagline', '.footer-nav'],
                { autoAlpha: 0, y: 25 },
                {
                    autoAlpha: 1, y: 0, duration: 0.6, stagger: 0.15, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: 'footer .footer-top',
                        start: 'top 88%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.footer-contact-row',
                { autoAlpha: 0, x: 40 },
                {
                    autoAlpha: 1, x: 0, duration: 0.6, ease: 'power3.out', stagger: 0.1,
                    scrollTrigger: {
                        trigger: 'footer .footer-right',
                        start: 'top 88%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            gsap.fromTo('.footer-brand-banner span',
                { autoAlpha: 0, y: 60 },
                {
                    autoAlpha: 1, y: 0, duration: 1, ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.footer-brand-banner',
                        start: 'top 90%',
                        toggleActions: 'play none none none'
                    }
                }
            );

            /* =============================================
               About Hero Section — GSAP Animation
               ============================================= */
            if ($('.section_about_hero').length) {

                var aboutHeroTl = gsap.timeline({ delay: 0.3 });

                aboutHeroTl
                    .fromTo('.section_about_hero .about-hero-title',
                        { autoAlpha: 0, y: 60 },
                        { autoAlpha: 1, y: 0, duration: 1, ease: 'power3.out' }
                    )
                    .fromTo('.section_about_hero .about-hero-desc',
                        { autoAlpha: 0, y: 30 },
                        { autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', stagger: 0.15 },
                        '-=0.5'
                    )
                    .fromTo('.section_about_hero .about-hero-buttons .btn-contact-header',
                        { autoAlpha: 0, y: 20 },
                        { autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out' },
                        '-=0.3'
                    )
                    .fromTo('.section_about_hero .about-hero-shape',
                        { autoAlpha: 0, x: 60 },
                        { autoAlpha: 1, x: 0, duration: 1.2, ease: 'power3.out' },
                        '-=0.8'
                    );
            }

            /* =============================================
               Our Core Section — GSAP Animation
               ============================================= */
            if ($('.section_our_core').length) {

                gsap.fromTo('.section_our_core .our-core-tag',
                    { autoAlpha: 0, y: -30 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out',
                        scrollTrigger: {
                            trigger: '.section_our_core',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_our_core .our-core-card',
                    { autoAlpha: 0, y: 50 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: {
                            trigger: '.section_our_core .our-core-card',
                            start: 'top 88%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_our_core .core-item:first-child',
                    { autoAlpha: 0, x: -40 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.8, ease: 'power3.out', delay: 0.15,
                        scrollTrigger: {
                            trigger: '.section_our_core .our-core-card',
                            start: 'top 88%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_our_core .core-item:last-child',
                    { autoAlpha: 0, x: 40 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.8, ease: 'power3.out', delay: 0.15,
                        scrollTrigger: {
                            trigger: '.section_our_core .our-core-card',
                            start: 'top 88%',
                            toggleActions: 'play none none none'
                        }
                    }
                );
            }

            /* =============================================
               Why Choose Us — GSAP Animation + Tab System
               ============================================= */
            if ($('.section_why_choose').length) {

                gsap.fromTo('.section_why_choose .why-choose-title',
                    { autoAlpha: 0, x: -50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: {
                            trigger: '.section_why_choose',
                            start: 'top 80%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_why_choose .why-choose-desc',
                    { autoAlpha: 0, x: -40 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.8, ease: 'power3.out', delay: 0.15,
                        scrollTrigger: {
                            trigger: '.section_why_choose',
                            start: 'top 80%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_why_choose .why-choose-tab',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.6, ease: 'power3.out', stagger: 0.1,
                        scrollTrigger: {
                            trigger: '.section_why_choose .why-choose-tabs',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_why_choose .why-choose-img-wrapper',
                    { autoAlpha: 0, x: 50 },
                    {
                        autoAlpha: 1, x: 0, duration: 1, ease: 'power3.out',
                        scrollTrigger: {
                            trigger: '.section_why_choose .why-choose-right',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_why_choose .why-choose-panel.active',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', delay: 0.3,
                        scrollTrigger: {
                            trigger: '.section_why_choose .why-choose-right',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                /* Tab Click — Image + Content Switch */
                var $whyImg = $('.section_why_choose .why-choose-img');
                var isTabSwitching = false;

                $(document).on('click', '.section_why_choose .why-choose-tab', function () {
                    var $tab = $(this);
                    if ($tab.hasClass('active') || isTabSwitching) return;

                    isTabSwitching = true;

                    var tabId = $tab.data('tab');
                    var newImg = $tab.data('img');
                    var $newPanel = $('.why-choose-panel[data-panel="' + tabId + '"]');
                    var $currentPanel = $('.why-choose-panel.active');

                    $('.section_why_choose .why-choose-tab').removeClass('active');
                    $tab.addClass('active');

                    gsap.fromTo($tab,
                        { scale: 0.96 },
                        { scale: 1, duration: 0.3, ease: 'back.out(1.5)' }
                    );

                    var tl = gsap.timeline({
                        onComplete: function () { isTabSwitching = false; }
                    });

                    tl
                        .to($currentPanel, {
                            autoAlpha: 0,
                            y: -12,
                            duration: 0.22,
                            ease: 'power2.in'
                        })
                        .to($whyImg, {
                            autoAlpha: 0,
                            scale: 1.04,
                            duration: 0.22,
                            ease: 'power2.in'
                        }, '<')
                        .call(function () {
                            $currentPanel.removeClass('active').css({ opacity: '', visibility: '', transform: '' });
                            $newPanel.addClass('active');
                            if (newImg) {
                                $whyImg.attr('src', newImg);
                            }
                        })
                        .fromTo($whyImg,
                            { autoAlpha: 0, scale: 0.97 },
                            { autoAlpha: 1, scale: 1, duration: 0.4, ease: 'power3.out' }
                        )
                        .fromTo($newPanel,
                            { autoAlpha: 0, y: 16 },
                            { autoAlpha: 1, y: 0, duration: 0.4, ease: 'power3.out' },
                            '-=0.3'
                        );
                });
            }

            /* =============================================
               Key Feature Section — GSAP Animation
               ============================================= */
            if ($('.section_key_feature').length) {

                gsap.fromTo('.section_key_feature .key-feature-title',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: {
                            trigger: '.section_key_feature',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_key_feature .key-feature-col-left .key-feature-card',
                    { autoAlpha: 0, y: 50 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.75, ease: 'power3.out', stagger: 0.2,
                        scrollTrigger: {
                            trigger: '.section_key_feature .key-feature-grid',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_key_feature .key-feature-col-right .key-feature-card',
                    { autoAlpha: 0, x: 60 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: {
                            trigger: '.section_key_feature .key-feature-grid',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );
            }

            /* =============================================
               Board Members — GSAP Animation
               ============================================= */
            if ($('.section_board_members').length) {

                gsap.fromTo('.section_board_members .board-members-title',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: {
                            trigger: '.section_board_members',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_board_members .board-member-card',
                    { autoAlpha: 0, y: 60 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.75, ease: 'power3.out', stagger: 0.15,
                        scrollTrigger: {
                            trigger: '.section_board_members .board-members-cards',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                /* Mobile: Click to toggle */
                if ($(window).width() <= 768) {
                    $('.section_board_members .board-member-card').on('click', function () {
                        var $card = $(this);
                        var isActive = $card.hasClass('active');
                        $('.section_board_members .board-member-card').removeClass('active');
                        if (!isActive) $card.addClass('active');
                    });
                }
            }

            /* =============================================
               Features Section — GSAP Animation
               ============================================= */
            if ($('.section_features').length) {

                gsap.fromTo('.section_features .feature-card-icon',
                    { autoAlpha: 0, y: 50, scale: 0.8 },
                    {
                        autoAlpha: 1, y: 0, scale: 1,
                        duration: 0.7, ease: 'back.out(1.4)', stagger: 0.18,
                        scrollTrigger: {
                            trigger: '.section_features .features-cards',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_features .feature-card-title',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0,
                        duration: 0.65, ease: 'power3.out', stagger: 0.18, delay: 0.15,
                        scrollTrigger: {
                            trigger: '.section_features .features-cards',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                gsap.fromTo('.section_features .feature-card-desc',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0,
                        duration: 0.6, ease: 'power3.out', stagger: 0.18, delay: 0.25,
                        scrollTrigger: {
                            trigger: '.section_features .features-cards',
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        }
                    }
                );

                $('.section_features .feature-card').on('mouseenter', function () {
                    gsap.to($(this).find('.feature-card-icon'), {
                        y: -8,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                }).on('mouseleave', function () {
                    gsap.to($(this).find('.feature-card-icon'), {
                        y: 0,
                        duration: 0.5,
                        ease: 'elastic.out(1, 0.5)'
                    });
                });
            }

            /* =============================================
               Enterprise AI Consultancy Section
               ============================================= */
            if ($('.section_enterprise_consultancy').length) {

                gsap.fromTo('.section_enterprise_consultancy .enterprise-tag',
                    { autoAlpha: 0, y: -20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_enterprise_consultancy', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_enterprise_consultancy .enterprise-title',
                    { autoAlpha: 0, x: -50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out', delay: 0.1,
                        scrollTrigger: { trigger: '.section_enterprise_consultancy', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_enterprise_consultancy .enterprise-desc',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', delay: 0.2,
                        scrollTrigger: { trigger: '.section_enterprise_consultancy', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_enterprise_consultancy .enterprise-btn',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', delay: 0.3,
                        scrollTrigger: { trigger: '.section_enterprise_consultancy', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_enterprise_consultancy .enterprise-feature',
                    { autoAlpha: 0, x: 40 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.65, ease: 'power3.out', stagger: 0.15,
                        scrollTrigger: { trigger: '.section_enterprise_consultancy .enterprise-right', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );

                $('.section_enterprise_consultancy .enterprise-feature-icon').on('mouseenter', function () {
                    gsap.to(this, { scale: 1.1, duration: 0.3, ease: 'back.out(1.5)' });
                }).on('mouseleave', function () {
                    gsap.to(this, { scale: 1, duration: 0.3, ease: 'power2.out' });
                });
            }

            /* =============================================
               Bridge Program Section
               ============================================= */
            if ($('.section_bridge_program').length) {

                gsap.fromTo('.section_bridge_program .bridge-tag',
                    { autoAlpha: 0, y: -15 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.6, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_bridge_program', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_bridge_program .bridge-title',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out', delay: 0.1,
                        scrollTrigger: { trigger: '.section_bridge_program', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_bridge_program .bridge-desc',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', delay: 0.2,
                        scrollTrigger: { trigger: '.section_bridge_program', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_bridge_program .bridge-card',
                    { autoAlpha: 0, y: 50, scale: 0.95 },
                    {
                        autoAlpha: 1, y: 0, scale: 1, duration: 0.7, ease: 'power3.out', stagger: 0.15,
                        scrollTrigger: { trigger: '.section_bridge_program .bridge-cards', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_bridge_program .bridge-benefits',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_bridge_program .bridge-benefits', start: 'top 88%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_bridge_program .bridge-benefit',
                    { autoAlpha: 0, x: -30 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.6, ease: 'power3.out', stagger: 0.15, delay: 0.2,
                        scrollTrigger: { trigger: '.section_bridge_program .bridge-benefits', start: 'top 88%', toggleActions: 'play none none none' }
                    }
                );

                $('.section_bridge_program .bridge-card').on('mouseenter', function () {
                    gsap.to($(this).find('.bridge-card-icon'), { y: -6, duration: 0.35, ease: 'power2.out' });
                }).on('mouseleave', function () {
                    gsap.to($(this).find('.bridge-card-icon'), { y: 0, duration: 0.5, ease: 'elastic.out(1, 0.5)' });
                });
            }

            /* =============================================
               Solutions Overview Section
               ============================================= */
            if ($('.section_solutions_overview').length) {

                gsap.fromTo('.section_solutions_overview .solutions-overview-title',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_solutions_overview', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_overview .solutions-overview-desc',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', delay: 0.1,
                        scrollTrigger: { trigger: '.section_solutions_overview', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_overview .solutions-tab',
                    { autoAlpha: 0, x: -30 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.6, ease: 'power3.out', stagger: 0.1,
                        scrollTrigger: { trigger: '.section_solutions_overview .solutions-tabs', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_overview .solutions-panel.active .solutions-panel-content',
                    { autoAlpha: 0, x: -30 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.8, ease: 'power3.out', delay: 0.2,
                        scrollTrigger: { trigger: '.section_solutions_overview .solutions-panels', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_overview .solutions-panel.active .solutions-panel-image',
                    { autoAlpha: 0, x: 40 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out', delay: 0.3,
                        scrollTrigger: { trigger: '.section_solutions_overview .solutions-panels', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );

                var isSolSwitching = false;

                $(document).on('click', '.section_solutions_overview .solutions-tab', function () {
                    var $tab = $(this);
                    if ($tab.hasClass('active') || isSolSwitching) return;

                    isSolSwitching = true;

                    var solId = $tab.data('sol');
                    var $newPanel = $('.solutions-panel[data-panel="' + solId + '"]');
                    var $currentPanel = $('.solutions-panel.active');

                    $('.section_solutions_overview .solutions-tab').removeClass('active');
                    $tab.addClass('active');

                    gsap.fromTo($tab, { scale: 0.97 }, { scale: 1, duration: 0.3, ease: 'back.out(1.5)' });

                    var tl = gsap.timeline({ onComplete: function () { isSolSwitching = false; } });

                    tl
                        .to($currentPanel, { autoAlpha: 0, y: -15, duration: 0.25, ease: 'power2.in' })
                        .call(function () {
                            $currentPanel.removeClass('active').css({ opacity: '', visibility: '', transform: '' });
                            $newPanel.addClass('active');
                        })
                        .fromTo($newPanel.find('.solutions-panel-content'),
                            { autoAlpha: 0, x: -25 },
                            { autoAlpha: 1, x: 0, duration: 0.45, ease: 'power3.out' }
                        )
                        .fromTo($newPanel.find('.solutions-panel-image'),
                            { autoAlpha: 0, x: 25 },
                            { autoAlpha: 1, x: 0, duration: 0.45, ease: 'power3.out' },
                            '<'
                        );
                });
            }

            /* =============================================
               How It Works Section
               ============================================= */
            if ($('.section_how_it_works').length) {

                gsap.fromTo('.section_how_it_works .how-it-works-tag',
                    { autoAlpha: 0, y: -15 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.6, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_how_it_works', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_how_it_works .how-it-works-title',
                    { autoAlpha: 0, y: 35 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', delay: 0.1,
                        scrollTrigger: { trigger: '.section_how_it_works', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_how_it_works .how-it-works-desc',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', delay: 0.2,
                        scrollTrigger: { trigger: '.section_how_it_works', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_how_it_works .how-step',
                    { autoAlpha: 0, y: 50 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.2,
                        scrollTrigger: { trigger: '.section_how_it_works .how-it-works-steps', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_how_it_works .how-step-divider',
                    { autoAlpha: 0, scaleX: 0 },
                    {
                        autoAlpha: 1, scaleX: 1, duration: 0.5, ease: 'power2.out', stagger: 0.2, delay: 0.3,
                        scrollTrigger: { trigger: '.section_how_it_works .how-it-works-steps', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_how_it_works .how-step-number',
                    { autoAlpha: 0, scale: 0.7 },
                    {
                        autoAlpha: 1, scale: 1, duration: 0.6, ease: 'back.out(1.4)', stagger: 0.2,
                        scrollTrigger: { trigger: '.section_how_it_works .how-it-works-steps', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
            }

            /* =============================================
               Solutions CTA Section
               ============================================= */
            if ($('.section_solutions_cta').length) {

                gsap.fromTo('.section_solutions_cta .solutions-cta-tag',
                    { autoAlpha: 0, y: -15 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.6, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_solutions_cta', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_cta .solutions-cta-title',
                    { autoAlpha: 0, x: -50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out', delay: 0.1,
                        scrollTrigger: { trigger: '.section_solutions_cta', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_cta .solutions-cta-desc',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', delay: 0.2,
                        scrollTrigger: { trigger: '.section_solutions_cta', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_cta .solutions-cta-buttons a',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.6, ease: 'power3.out', stagger: 0.15, delay: 0.3,
                        scrollTrigger: { trigger: '.section_solutions_cta', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_solutions_cta .cta-stat',
                    { autoAlpha: 0, x: 50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.7, ease: 'power3.out', stagger: 0.15,
                        scrollTrigger: { trigger: '.section_solutions_cta .solutions-cta-stats', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
            }

            /* =============================================
               Industries Overview Section
               ============================================= */
            if ($('.section_industries_overview').length) {

                gsap.fromTo('.section_industries_overview .industries-overview-title',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_industries_overview', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_industries_overview .industries-overview-desc',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', delay: 0.1,
                        scrollTrigger: { trigger: '.section_industries_overview', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_industries_overview .industry-card',
                    { autoAlpha: 0, y: 50 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.65, ease: 'power3.out', stagger: 0.1,
                        scrollTrigger: { trigger: '.section_industries_overview .industries-grid', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
            }

            /* =============================================
               Industries Detail Section
               ============================================= */
            if ($('.section_industries_detail').length) {

                gsap.fromTo('.section_industries_detail .industries-detail-title',
                    { autoAlpha: 0, x: -40 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_industries_detail', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_industries_detail .industries-detail-desc',
                    { autoAlpha: 0, y: 25 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', delay: 0.1,
                        scrollTrigger: { trigger: '.section_industries_detail', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_industries_detail .industry-item',
                    { autoAlpha: 0, y: 30 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.6, ease: 'power3.out', stagger: 0.1,
                        scrollTrigger: { trigger: '.section_industries_detail .industries-accordion', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );

                $(document).on('click', '.section_industries_detail .industry-item-header', function () {
                    var $item = $(this).closest('.industry-item');
                    var $body = $item.find('.industry-item-body');
                    var isActive = $item.hasClass('active');

                    $('.section_industries_detail .industry-item').removeClass('active');
                    $('.section_industries_detail .industry-item-body').slideUp(300);

                    if (!isActive) {
                        $item.addClass('active');
                        $body.slideDown(350);
                        gsap.fromTo($item.find('.industry-item-inner'),
                            { autoAlpha: 0, y: 15 },
                            { autoAlpha: 1, y: 0, duration: 0.45, ease: 'power3.out', delay: 0.1 }
                        );
                    }
                });
            }

            /* =============================================
               Insights Featured Section
               ============================================= */
            if ($('.section_insights_featured').length) {

                gsap.fromTo('.section_insights_featured .insights-featured-image',
                    { autoAlpha: 0, x: -60 },
                    {
                        autoAlpha: 1, x: 0, duration: 1, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_insights_featured', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_insights_featured .insights-featured-content',
                    { autoAlpha: 0, x: 50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out', delay: 0.15,
                        scrollTrigger: { trigger: '.section_insights_featured', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
            }

            /* =============================================
               Insights Grid Section
               ============================================= */
            if ($('.section_insights_grid').length) {

                gsap.fromTo('.section_insights_grid .insights-filter',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.5, ease: 'power3.out', stagger: 0.08,
                        scrollTrigger: { trigger: '.section_insights_grid', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_insights_grid .insight-card',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.65, ease: 'power3.out', stagger: 0.1,
                        scrollTrigger: { trigger: '.section_insights_grid .insights-articles-grid', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );

                $(document).on('click', '.section_insights_grid .insights-filter', function () {
                    var $btn = $(this);
                    var filter = $btn.data('filter');

                    $('.section_insights_grid .insights-filter').removeClass('active');
                    $btn.addClass('active');

                    var $cards = $('.section_insights_grid .insight-card');

                    if (filter === 'all') {
                        $cards.removeClass('hidden');
                        gsap.fromTo($cards, { autoAlpha: 0, y: 20 }, { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power3.out', stagger: 0.08 });
                    } else {
                        $cards.each(function () {
                            var $card = $(this);
                            if ($card.data('category') === filter) {
                                $card.removeClass('hidden');
                            } else {
                                $card.addClass('hidden');
                            }
                        });
                        var $visible = $cards.not('.hidden');
                        gsap.fromTo($visible, { autoAlpha: 0, y: 20 }, { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power3.out', stagger: 0.08 });
                    }
                });
            }

            /* =============================================
               Newsletter Section
               ============================================= */
            if ($('.section_newsletter').length) {

                gsap.fromTo('.section_newsletter .newsletter-content',
                    { autoAlpha: 0, x: -50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_newsletter', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_newsletter .newsletter-form',
                    { autoAlpha: 0, x: 50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out', delay: 0.15,
                        scrollTrigger: { trigger: '.section_newsletter', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
            }

            /* =============================================
               Contact Main Section
               ============================================= */
            if ($('.section_contact_main').length) {

                gsap.fromTo('.section_contact_main .contact-form-wrap',
                    { autoAlpha: 0, x: -50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_contact_main', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_contact_main .contact-info-wrap',
                    { autoAlpha: 0, x: 50 },
                    {
                        autoAlpha: 1, x: 0, duration: 0.9, ease: 'power3.out', delay: 0.15,
                        scrollTrigger: { trigger: '.section_contact_main', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_contact_main .contact-form-group',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.5, ease: 'power3.out', stagger: 0.08, delay: 0.3,
                        scrollTrigger: { trigger: '.section_contact_main .contact-form', start: 'top 88%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_contact_main .contact-info-item',
                    { autoAlpha: 0, y: 20 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.5, ease: 'power3.out', stagger: 0.1, delay: 0.3,
                        scrollTrigger: { trigger: '.section_contact_main .contact-info-items', start: 'top 88%', toggleActions: 'play none none none' }
                    }
                );
            }

            /* =============================================
               Contact Why Section
               ============================================= */
            if ($('.section_contact_why').length) {

                gsap.fromTo('.section_contact_why .contact-why-title',
                    { autoAlpha: 0, y: 40 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: '.section_contact_why', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
                gsap.fromTo('.section_contact_why .contact-why-step',
                    { autoAlpha: 0, y: 50 },
                    {
                        autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.15,
                        scrollTrigger: { trigger: '.section_contact_why .contact-why-steps', start: 'top 85%', toggleActions: 'play none none none' }
                    }
                );
            }

        } // end GSAP check

    }); // end document.ready

})(jQuery);
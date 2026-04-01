jQuery(function ($) {

    const header = $('#header');
    const hamburger = $('.hamburger');
    const mobileMenu = $('.mobile-menu');
    const overlay = $('#mobile-overlay');
    const closeBtn = $('.close');
    const $html = $('html');

    // Ensure header stays fixed even when rendered inside transformed Elementor wrappers.
    (function normalizeStickyHeader() {
        const $header = $('#header').first();
        if (!$header.length) return;

        const $overlay = $('#mobile-overlay').first();
        const $mobileMenu = $('.mobile-menu').first();

        // Move to body to avoid "position: fixed" being trapped by parent transform/overflow.
        if (!$header.parent().is('body')) {
            if ($mobileMenu.length) $('body').prepend($mobileMenu);
            if ($overlay.length) $('body').prepend($overlay);
            $('body').prepend($header);
        }

        $header.css({
            position: 'fixed',
            top: '0',
            left: '0',
            right: '0',
            zIndex: '9999'
        });
    })();

    /* ============================================
       SCROLL - HEADER
    ============================================ */
    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 20) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }
    });

    /* ============================================
       MOBILE MENU
    ============================================ */
    hamburger.on('click', function () {
        mobileMenu.addClass('active');
        overlay.addClass('active');
        $('body').addClass('menu-open');
    });

    function closeMenu() {
        mobileMenu.removeClass('active');
        overlay.removeClass('active');
        $('body').removeClass('menu-open');
    }

    closeBtn.on('click', closeMenu);
    overlay.on('click', closeMenu);

    /* ============================================
       THEME TOGGLE (DASHBOARD)
       - toggles `dark` class on <html>
       - persists to localStorage
    ============================================ */
    (function initThemeToggle() {
        const STORAGE_KEY = 'bina_theme';

        const $toggle = $('button[aria-label*="theme" i], button[aria-label*="ثيم" i], button[aria-label*="الوضع" i]').first();
        if (!$toggle.length) return; // don't affect pages without the toggle

        function setIcon(isDark) {
            // Keep it simple: swap SVG inside the button
            const sun = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun h-[1.2rem] w-[1.2rem]"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></svg>';
            const moon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon h-[1.2rem] w-[1.2rem]"><path d="M12 3a7 7 0 1 0 9 9 9 9 0 0 1-9-9"></path></svg>';
            $toggle.html(isDark ? moon : sun);
            $toggle.attr('aria-label', isDark ? 'Switch to light theme' : 'Switch to dark theme');
        }

        function applyTheme(mode) {
            const isDark = mode === 'dark';
            $html.toggleClass('dark', isDark);
            setIcon(isDark);
        }

        function getInitialTheme() {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved === 'dark' || saved === 'light') return saved;
            } catch (_) { }
            // default: prefer system
            try {
                return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            } catch (_) { }
            return 'light';
        }

        // init
        applyTheme(getInitialTheme());

        let tHandle = null;
        function withThemeTransition() {
            try {
                if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    return;
                }
            } catch (_) { }
            try {
                $html.addClass('bina-theme-transition');
                if (tHandle) clearTimeout(tHandle);
                tHandle = setTimeout(function () {
                    $html.removeClass('bina-theme-transition');
                }, 260);
            } catch (_) { }
        }

        $toggle.on('click', function () {
            const nowDark = !$html.hasClass('dark');
            const next = nowDark ? 'dark' : 'light';
            withThemeTransition();
            applyTheme(next);
            try { localStorage.setItem(STORAGE_KEY, next); } catch (_) { }
        });
    })();

    /* ============================================
       SMOOTH SCROLL - ANCHOR LINKS
    ============================================ */
    $('a[href^="#"]').on('click', function (e) {
        const target = $(this).attr('href');
        if (target === '#') return;
        const $target = $(target);
        if ($target.length) {
            e.preventDefault();
            closeMenu();
            $('html, body').animate({
                scrollTop: $target.offset().top - 80
            }, 700, 'swing');
        }
    });

    /* ============================================
       SCROLL ANIMATIONS - FADE IN ON SCROLL
    ============================================ */
    const animateElements = $('.animate-on-scroll');

    function checkAnimations() {
        const windowBottom = $(window).scrollTop() + $(window).height();
        animateElements.each(function () {
            const elementTop = $(this).offset().top;
            if (windowBottom > elementTop + 60) {
                $(this).addClass('visible');
            }
        });
    }

    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(40px);
                transition: opacity 0.6s ease, transform 0.6s ease;
            }
            .animate-on-scroll.from-left {
                transform: translateX(-40px);
            }
            .animate-on-scroll.from-right {
                transform: translateX(40px);
            }
            .animate-on-scroll.visible {
                opacity: 1;
                transform: translate(0, 0);
            }
        `)
        .appendTo('head');

    checkAnimations();
    $(window).on('scroll', checkAnimations);

    /* ============================================
       COUNTER ANIMATION - STATS SECTION
    ============================================ */
    function animateCounter($el) {
        const target = parseInt($el.data('target'));
        const suffix = $el.data('suffix') || '';
        const prefix = $el.data('prefix') || '';
        const duration = 2000;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 4);
            const current = Math.floor(eased * target);
            $el.text(prefix + current.toLocaleString() + suffix);
            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                $el.text(prefix + target.toLocaleString() + suffix);
            }
        }

        requestAnimationFrame(update);
    }

    let countersStarted = false;

    function checkCounters() {
        if (countersStarted) return;
        const $counters = $('[data-counter]');
        if (!$counters.length) return;
        const windowBottom = $(window).scrollTop() + $(window).height();
        const sectionTop = $counters.first().closest('section').offset().top;
        if (windowBottom > sectionTop + 100) {
            countersStarted = true;
            $counters.each(function () {
                animateCounter($(this));
            });
        }
    }

    checkCounters();
    $(window).on('scroll', checkCounters);

    /* ============================================
       REVIEWS CAROUSEL
    ============================================ */
    $('.reviews-swiper').each(function () {
        var el = this;
        if (!el || el.dataset.binaSwiperInited === '1') return;
        if (typeof Swiper === 'undefined') return;

        var $el = $(el);
        var $wrap = $el.closest('.reviews-swiper-wrap');
        var nextEl = ($wrap.find('.swiper-button-next')[0]) || ($el.find('.swiper-button-next')[0]);
        var prevEl = ($wrap.find('.swiper-button-prev')[0]) || ($el.find('.swiper-button-prev')[0]);

        new Swiper(el, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            dir: 'rtl',
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: nextEl,
                prevEl: prevEl,
            },
            breakpoints: {
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 }
            }
        });

        el.dataset.binaSwiperInited = '1';
    });

    /* ============================================
       ACCORDION - النوع الأول (document 3)
       صفحة المقاولين - overflow + height + opacity
       الـ button مش فيه aria-controls بس فيه
       div.overflow-hidden جنبه مباشرة
    ============================================ */
    function initAccordionTypeOne() {
        // بنشوف كل card فيها button وجنبه مباشرة div.overflow-hidden
        // وده بييز عن النوع التاني اللي بيستخدم data-orientation
        var $cards = $('.bg-card.rounded-xl.border').filter(function () {
            return $(this).find('> button, button:first').siblings('div.overflow-hidden').length > 0
                && !$(this).closest('[data-orientation="vertical"]').length;
        });

        // لو مفيش، جرب الـ selector التاني
        if (!$cards.length) {
            $cards = $('.bg-card').filter(function () {
                return $(this).children('button').length > 0
                    && $(this).children('div.overflow-hidden').length > 0;
            });
        }

        $cards.each(function () {
            var $card = $(this);
            var $btn = $card.children('button');
            var $content = $card.children('div.overflow-hidden');
            var $chevron = $btn.find('svg:last-of-type');

            // إغلاق أولي
            $content.css({ height: 0, opacity: 0 });

            $btn.on('click.accordionType1', function () {
                var isOpen = $card.data('acc-open') === true;

                // اقفل كل cards من نفس النوع
                $cards.each(function () {
                    var $c = $(this);
                    if ($c.data('acc-open') === true) {
                        $c.data('acc-open', false);
                        $c.children('div.overflow-hidden')
                            .stop(true, false)
                            .animate({ height: 0, opacity: 0 }, 300);
                        $c.children('button').attr('aria-expanded', 'false');
                        $c.children('button').find('svg:last-of-type')
                            .css('transform', 'rotate(0deg)');
                    }
                });

                if (!isOpen) {
                    $card.data('acc-open', true);
                    var targetH = $content[0].scrollHeight;
                    $content.stop(true, false).animate(
                        { height: targetH, opacity: 1 },
                        300,
                        function () { $content.css('height', 'auto'); }
                    );
                    $btn.attr('aria-expanded', 'true');
                    $chevron.css('transform', 'rotate(180deg)');
                }
            });
        });
    }

    /* ============================================
       ACCORDION - النوع الثاني (document 4)
       الصفحة الرئيسية - aria-controls + hidden
       فيه data-orientation="vertical" + data-state
    ============================================ */
    function initAccordionTypeTwo() {
        function smoothClose($el, done) {
            if (!$el || !$el.length) {
                if (typeof done === 'function') done();
                return;
            }
            var el = $el[0];
            el.style.overflow = 'hidden';
            el.style.height = el.scrollHeight + 'px';
            el.style.opacity = '1';
            el.style.paddingBottom = '16px';
            el.style.transition = 'none';

            requestAnimationFrame(function () {
                el.style.transition = 'height 240ms ease, opacity 200ms ease, padding-bottom 200ms ease';
                el.style.height = '0px';
                el.style.opacity = '0';
                el.style.paddingBottom = '0px';
            });

            window.setTimeout(function () {
                $el.attr('hidden', true);
                el.style.transition = '';
                el.style.height = '';
                el.style.opacity = '';
                el.style.paddingBottom = '';
                el.style.overflow = '';
                if (typeof done === 'function') done();
            }, 260);
        }

        function smoothOpen($el) {
            if (!$el || !$el.length) return;
            var el = $el[0];
            $el.removeAttr('hidden');
            el.style.overflow = 'hidden';
            el.style.height = '0px';
            el.style.opacity = '0';
            el.style.paddingBottom = '0px';
            el.style.transition = 'none';

            requestAnimationFrame(function () {
                el.style.transition = 'height 280ms ease, opacity 240ms ease, padding-bottom 240ms ease';
                el.style.height = el.scrollHeight + 'px';
                el.style.opacity = '1';
                el.style.paddingBottom = '16px';
            });

            window.setTimeout(function () {
                el.style.transition = '';
                el.style.height = '';
                el.style.opacity = '';
                el.style.overflow = '';
                // keep padding-bottom on opened answer
                el.style.paddingBottom = '16px';
            }, 300);
        }

        // delegated binding to support Elementor/dynamic renders
        $(document).off('click.accordionType2', 'button[aria-controls][data-orientation="vertical"]');
        $(document).on('click.accordionType2', 'button[aria-controls][data-orientation="vertical"]', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var contentId = $btn.attr('aria-controls');
            if (!contentId) return;

            var contentEl = document.getElementById(contentId);
            if (!contentEl) return;
            var $content = $(contentEl);
            var isOpen = $btn.attr('aria-expanded') === 'true';

            // إيجاد الـ parent accordion container (مش الـ item نفسه)
            var $container = $btn.closest('[data-orientation="vertical"]:not([data-state])');
            if (!$container.length) return;
            if ($container.data('accBusy') === true) return;
            $container.data('accBusy', true);

            // اقفل العنصر المفتوح الحالي فقط (أخف بكثير من لف كل العناصر)
            var $openedBtn = $container.find('button[aria-controls][data-orientation="vertical"][aria-expanded="true"]').not($btn).first();
            if ($openedBtn.length) {
                var openedId = $openedBtn.attr('aria-controls');
                var openedEl = openedId ? document.getElementById(openedId) : null;
                var $openedContent = openedEl ? $(openedEl) : $();
                var $openedItem = $openedBtn.closest('[data-orientation="vertical"][data-state]');

                if ($openedContent.length && !$openedContent.attr('hidden')) {
                    smoothClose($openedContent);
                }
                $openedBtn.attr('aria-expanded', 'false').attr('data-state', 'closed');
                $openedItem.attr('data-state', 'closed');
                $openedBtn.find('svg').css('transform', 'rotate(0deg)');
            }

            // لو كان مفتوح، اقفله بس
            if (isOpen) {
                smoothClose($content, function () {
                    $container.data('accBusy', false);
                });
                $btn.attr('aria-expanded', 'false').attr('data-state', 'closed');
                var $selfItem = $btn.closest('[data-orientation="vertical"][data-state]');
                $selfItem.attr('data-state', 'closed');
                $btn.find('svg').css('transform', 'rotate(0deg)');
                return;
            }

            // افتح الحالي
            var $item = $btn.closest('[data-orientation="vertical"][data-state]');

            smoothOpen($content);

            $btn.attr('aria-expanded', 'true').attr('data-state', 'open');
            $item.attr('data-state', 'open');
            $btn.find('svg').css('transform', 'rotate(180deg)');
            window.setTimeout(function () {
                $container.data('accBusy', false);
            }, 320);
        });
    }

    initAccordionTypeOne();
    initAccordionTypeTwo();

    /* ============================================
       PARTNERS MARQUEE
    ============================================ */
    function initMarquee() {
        const track = $('.marquee-track');
        if (!track.length) return;

        const items = track.html();
        track.append(items);

        let position = 0;
        const speed = 0.5;
        const isRTL = $('html').attr('dir') === 'rtl';
        let paused = false;

        function animate() {
            if (!paused) {
                position += isRTL ? speed : -speed;
                const totalWidth = track[0].scrollWidth / 2;
                if (isRTL && position >= totalWidth) position = 0;
                else if (!isRTL && position <= -totalWidth) position = 0;
                track.css('transform', `translateX(${position}px)`);
            }
            requestAnimationFrame(animate);
        }

        animate();

        track.closest('.marquee-wrapper')
            .on('mouseenter', function () { paused = true; })
            .on('mouseleave', function () { paused = false; });
    }

    initMarquee();

    /* ============================================
       WHATSAPP FLOAT BUTTON
    ============================================ */
    const $whatsapp = $('.whatsapp-float');
    if ($whatsapp.length) {
        $whatsapp.css({ opacity: 0, transform: 'scale(0)' });
        setTimeout(function () {
            $whatsapp.css({
                transition: 'opacity 0.4s ease, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                opacity: 1,
                transform: 'scale(1)'
            });
        }, 1000);

        $whatsapp
            .on('mouseenter', function () { $(this).css('transform', 'scale(1.1)'); })
            .on('mouseleave', function () { $(this).css('transform', 'scale(1)'); });
    }

    /* ============================================
       GRID PATTERN BACKGROUND - STATS SECTION
    ============================================ */
    const $statsSection = $('.stats-section');
    if ($statsSection.length) {
        $('<style>').prop('type', 'text/css').html(`
            .stats-section { position: relative; overflow: hidden; }
            .stats-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(to right, rgba(0,0,0,0.06) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(0,0,0,0.06) 1px, transparent 1px);
                background-size: 60px 60px;
                pointer-events: none;
                z-index: 0;
            }
            .stats-section > * { position: relative; z-index: 1; }
        `).appendTo('head');
    }

    /* ============================================
       ACTIVE NAV LINK HIGHLIGHT
    ============================================ */
    const currentPath = window.location.pathname;
    $('nav a, .mobile-menu a').each(function () {
        const href = $(this).attr('href');
        if (href && href !== '#' && currentPath === href) {
            $(this).addClass('active-nav');
        }
    });


    /* ============================================
       LAZY LOAD IMAGES
    ============================================ */
    if ('IntersectionObserver' in window) {
        const lazyObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = $(img).data('src');
                    if (src) {
                        img.src = src;
                        $(img).removeAttr('data-src').addClass('loaded');
                    }
                    lazyObserver.unobserve(img);
                }
            });
        });

        $('img[data-src]').each(function () {
            lazyObserver.observe(this);
        });
    } else {
        $('img[data-src]').each(function () {
            const src = $(this).data('src');
            if (src) this.src = src;
        });
    }

});
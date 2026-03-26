// Forgot password widget behavior: validate email, AJAX submit, toast notification.
(function () {
  const forms = document.querySelectorAll('[data-bina-forgot-form]');
  if (!forms.length) return;

  function getAjaxUrl() {
    if (window.bina && window.bina.ajaxurl) return String(window.bina.ajaxurl);
    return `${window.location.origin}/wp-admin/admin-ajax.php`;
  }

  function ensureToaster() {
    let wrapper = document.querySelector('[data-bina-toast-root]');
    if (wrapper) return wrapper;

    wrapper = document.createElement('section');
    wrapper.setAttribute('aria-label', 'Notifications alt+T');
    wrapper.setAttribute('tabindex', '-1');
    wrapper.setAttribute('aria-live', 'polite');
    wrapper.setAttribute('aria-relevant', 'additions text');
    wrapper.setAttribute('aria-atomic', 'false');
    wrapper.setAttribute('data-bina-toast-root', 'true');

    const list = document.createElement('ol');
    list.setAttribute('dir', 'ltr');
    list.setAttribute('tabindex', '-1');
    list.className = 'toaster group';
    list.setAttribute('data-sonner-toaster', 'true');
    list.setAttribute('data-sonner-theme', 'dark');
    list.setAttribute('data-y-position', 'top');
    list.setAttribute('data-x-position', 'right');
    list.style.cssText = '--front-toast-height: 53.5px; --width: 356px; --gap: 14px; --normal-bg: var(--popover); --normal-text: var(--popover-foreground); --normal-border: var(--border); --offset-top: 24px; --offset-right: 24px; --offset-bottom: 24px; --offset-left: 24px; --mobile-offset-top: 16px; --mobile-offset-right: 16px; --mobile-offset-bottom: 16px; --mobile-offset-left: 16px;';

    wrapper.appendChild(list);
    document.body.appendChild(wrapper);
    return wrapper;
  }

  function showToast(message, type) {
    const toasterRoot = ensureToaster();
    const list = toasterRoot.querySelector('[data-sonner-toaster]');
    if (!list) return;

    const toast = document.createElement('li');
    toast.setAttribute('tabindex', '0');
    toast.setAttribute('data-sonner-toast', '');
    toast.setAttribute('data-rich-colors', 'true');
    toast.setAttribute('data-styled', 'true');
    toast.setAttribute('data-mounted', 'true');
    toast.setAttribute('data-promise', 'false');
    toast.setAttribute('data-swiped', 'false');
    toast.setAttribute('data-removed', 'false');
    toast.setAttribute('data-visible', 'true');
    toast.setAttribute('data-y-position', 'top');
    toast.setAttribute('data-x-position', 'right');
    toast.setAttribute('data-index', '0');
    toast.setAttribute('data-front', 'true');
    toast.setAttribute('data-swiping', 'false');
    toast.setAttribute('data-dismissible', 'true');
    toast.setAttribute('data-type', type === 'error' ? 'error' : 'success');
    toast.setAttribute('data-swipe-out', 'false');
    toast.setAttribute('data-expanded', 'false');
    toast.style.cssText = '--index: 0; --toasts-before: 0; --z-index: 1; --offset: 0px; --initial-height: 53.5px;';

    const closeBtn = document.createElement('button');
    closeBtn.setAttribute('aria-label', 'Close toast');
    closeBtn.setAttribute('data-disabled', 'false');
    closeBtn.setAttribute('data-close-button', 'true');
    closeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
    closeBtn.addEventListener('click', function () {
      toast.remove();
    });

    const icon = document.createElement('div');
    icon.setAttribute('data-icon', '');
    icon.innerHTML = type === 'error'
      ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" height="20" width="20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-11a1 1 0 011 1v3a1 1 0 11-2 0V8a1 1 0 011-1zm0 8a1.25 1.25 0 100-2.5A1.25 1.25 0 0010 15z" clip-rule="evenodd"></path></svg>'
      : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" height="20" width="20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path></svg>';

    const content = document.createElement('div');
    content.setAttribute('data-content', '');
    const title = document.createElement('div');
    title.setAttribute('data-title', '');
    title.textContent = message;
    content.appendChild(title);

    toast.appendChild(closeBtn);
    toast.appendChild(icon);
    toast.appendChild(content);
    list.appendChild(toast);

    window.setTimeout(function () {
      if (toast.parentNode) toast.remove();
    }, 5000);
  }

  function isEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  forms.forEach(function (form) {
    const emailInput = form.querySelector('input[name="user_login"]');
    const errorEl = form.querySelector('[data-error-for="email"]');
    const submitBtn = form.querySelector('button[type="submit"]');
    const nonce = form.getAttribute('data-forgot-nonce') || '';

    function setError(text) {
      if (!errorEl) return;
      errorEl.textContent = text || '';
      errorEl.style.display = text ? 'block' : 'none';
    }

    setError('');

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      const email = (emailInput?.value || '').trim();
      setError('');

      if (!email || !isEmail(email)) {
        setError('يرجى إدخال بريد إلكتروني صحيح');
        return;
      }

      if (submitBtn) submitBtn.disabled = true;

      const payload = new URLSearchParams();
      payload.set('action', 'bina_forgot_password');
      payload.set('email', email);
      payload.set('nonce', nonce);

      try {
        const res = await fetch(getAjaxUrl(), {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
          body: payload.toString(),
          credentials: 'same-origin',
        });

        let data = null;
        try {
          data = await res.json();
        } catch (_err) {
          data = null;
        }

        if (!res.ok || !data || data.success !== true) {
          const fieldErrors = data && data.data && data.data.fieldErrors ? data.data.fieldErrors : {};
          const msg = fieldErrors.email || (data && data.data && data.data.message) || 'تعذر إرسال الرابط';
          setError(String(msg));
          showToast(String(msg), 'error');
          if (submitBtn) submitBtn.disabled = false;
          return;
        }

        const successMsg = (data.data && data.data.message) || 'تم إرسال الرابط بنجاح';
        setError('');
        showToast(String(successMsg), 'success');
        form.reset();
      } catch (_error) {
        const msg = 'حدث خطأ غير متوقع، حاول مرة أخرى';
        setError(msg);
        showToast(msg, 'error');
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  });
})();


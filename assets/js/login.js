// Login widget behavior: password toggle + real login via WordPress AJAX.
(function () {
  const forms = document.querySelectorAll('[data-bina-login-form]');
  if (!forms.length) return;

  forms.forEach(function (form) {
    const identifierInput = form.querySelector('input[name="identifier"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const identifierError = form.querySelector('[data-error-for="identifier"]');
    const passwordError = form.querySelector('[data-error-for="password"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    function getAjaxUrl() {
      if (window.bina && window.bina.ajaxurl) return String(window.bina.ajaxurl);
      return `${window.location.origin}/wp-admin/admin-ajax.php`;
    }

  function showError(el, text) {
    if (!el) return;
    el.textContent = text;
    el.style.display = "block";
  }

  function hideError(el) {
    if (!el) return;
    el.textContent = "";
    el.style.display = "none";
  }

    function setupPasswordToggles() {
      const wrappers = form.querySelectorAll(".relative");
      wrappers.forEach((wrapper) => {
        const input = wrapper.querySelector('input[type="password"][name="password"]');
        const btn = wrapper.querySelector('[data-password-toggle]');
        if (!input || !btn) return;

        btn.style.right = "6px";
        btn.style.left = "auto";

        const sr = btn.querySelector(".sr-only");
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();

          const isPassword = input.getAttribute("type") === "password";
          input.setAttribute("type", isPassword ? "text" : "password");
          if (sr) sr.textContent = isPassword ? "Hide password" : "Show password";
        });
      });
    }

    setupPasswordToggles();
    hideError(identifierError);
    hideError(passwordError);

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const identifier = (identifierInput?.value || "").trim();
      const password = passwordInput?.value || "";

      hideError(identifierError);
      hideError(passwordError);

      let ok = true;
      if (!identifier) {
        ok = false;
        showError(identifierError, "البريد الإلكتروني أو اسم المستخدم أو رقم الهاتف مطلوب");
      }
      if (!password) {
        ok = false;
        showError(passwordError, "كلمة المرور مطلوبة");
      }
      if (!ok) return;
      if (submitBtn) submitBtn.disabled = true;

      const payload = new URLSearchParams();
      payload.set("action", "bina_login_user");
      payload.set("identifier", identifier);
      payload.set("password", password);

      const url = new URL(getAjaxUrl());
      const res = await fetch(url.toString(), {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body: payload.toString(),
        credentials: "same-origin",
      });

      let data = null;
      try {
        data = await res.json();
      } catch {
        data = null;
      }

      if (!res.ok || !data || data.success !== true) {
        const fieldErrors = data && data.data && data.data.fieldErrors ? data.data.fieldErrors : {};
        if (fieldErrors.identifier) showError(identifierError, String(fieldErrors.identifier));
        if (fieldErrors.password) showError(passwordError, String(fieldErrors.password));
        if (submitBtn) submitBtn.disabled = false;
        return;
      }

      const redirectUrl =
        (data.data && data.data.redirect_url ? String(data.data.redirect_url) : "") || "/";
      window.location.href = redirectUrl;
    });
  });
})();


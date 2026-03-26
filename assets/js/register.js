// Register widget behavior: validation, city loading, submission.
(function () {
  const forms = document.querySelectorAll('[data-bina-register-form]');
  if (!forms.length) return;

  forms.forEach(function (form) {

    const els = {
      firstName: form.querySelector('input[name="firstName"]'),
      lastName: form.querySelector('input[name="lastName"]'),
      email: form.querySelector('input[name="email"]'),
      phone: form.querySelector('input[name="phone"]'),
      password: form.querySelector('input[name="password"]'),
      confirmPassword: form.querySelector('input[name="confirmPassword"]'),
      accountTypeValue: form.querySelector('[data-account-type-value]'),
      cityTrigger: form.querySelector('[data-city-trigger]'),
      cityValue: form.querySelector('[data-city-value]'),
      submitBtn: form.querySelector('button[type="submit"]'),
    };

    const messages = {
      firstName: form.querySelector('[data-error-for="firstName"]'),
      email: form.querySelector('[data-error-for="email"]'),
      phone: form.querySelector('[data-error-for="phone"]'),
      city: form.querySelector('[data-error-for="city"]'),
      password: form.querySelector('[data-error-for="password"]'),
      confirmPassword: form.querySelector('[data-error-for="confirmPassword"]'),
      accountType: form.querySelector('[data-error-for="accountType"]'),
    };

    function clearErrors() {
      Object.values(messages).forEach((el) => {
        if (el) el.textContent = "";
      });
    }

    function setError(key, text) {
      const el = messages[key];
      if (el) el.textContent = text;
    }

    function isEmail(value) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }

    function getAjaxUrl() {
      if (window.bina && window.bina.ajaxurl) return String(window.bina.ajaxurl);
      return `${window.location.origin}/wp-admin/admin-ajax.php`;
    }

    function getSiteBase() {
      if (window.bina && window.bina.home_url) return String(window.bina.home_url).replace(/\/$/, '');
      return window.location.origin;
    }

    const FALLBACK_CITIES = [
    { value: "riyadh", label: "الرياض" },
    { value: "jeddah", label: "جدة" },
    { value: "makkah", label: "مكة المكرمة" },
    { value: "madinah", label: "المدينة المنورة" },
    { value: "dammam", label: "الدمام" },
    { value: "khobar", label: "الخبر" },
    { value: "taif", label: "الطائف" },
    { value: "abha", label: "أبها" },
    { value: "tabuk", label: "تبوك" },
    { value: "buraydah", label: "بريدة" },
  ];

    function getAccountType() {
      const hidden = (els.accountTypeValue?.value || "").trim();
      if (hidden) return hidden;

    // radios in this HTML are weird (button + hidden input). We support either "checked" on input, or aria-checked on button.
      const checkedInput = form.querySelector('input[type="radio"][name="accountType"]:checked');
      if (checkedInput) return checkedInput.value;

    // fallback: look at closest role="radio" buttons
      const btnChecked = form.querySelector('[role="radio"][aria-checked="true"]');
      if (btnChecked) return btnChecked.getAttribute("value") || "";
      return "";
    }

    function setupAccountTypeRadios() {
      const group = form.querySelector('[role="radiogroup"]');
      if (!group) return;

      const radioButtons = Array.from(group.querySelectorAll('[role="radio"][data-slot="radio-group-item"]'));
      if (radioButtons.length === 0) return;

      function setChecked(btn) {
        radioButtons.forEach((b) => {
          const isOn = b === btn;
          b.setAttribute("aria-checked", isOn ? "true" : "false");
          b.setAttribute("data-state", isOn ? "checked" : "unchecked");
        });

        const value = btn.getAttribute("value") || "";
        if (els.accountTypeValue) els.accountTypeValue.value = value;
        setError("accountType", "");
      }

    // Allow clicking on the entire label/card (not just the tiny circle)
      const labels = Array.from(group.querySelectorAll("label"));
      labels.forEach((lab) => {
        lab.addEventListener("click", (e) => {
          const btn = lab.querySelector('[role="radio"][data-slot="radio-group-item"]');
          if (!btn) return;
          e.preventDefault();
          setChecked(btn);
        });
      });

    // Also handle direct clicks on the radio buttons
      radioButtons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          setChecked(btn);
        });
      });
    }

    function validate() {
      clearErrors();
      let ok = true;

      const firstName = (els.firstName?.value || "").trim();
      const email = (els.email?.value || "").trim();
      const phone = (els.phone?.value || "").trim();
      const city = (els.cityValue?.value || "").trim();
      const password = els.password?.value || "";
      const confirmPassword = els.confirmPassword?.value || "";
      const accountType = getAccountType();

      if (!firstName) {
        ok = false;
        setError("firstName", "الاسم الأول مطلوب");
      }

      if (!email) {
        ok = false;
        setError("email", "البريد الإلكتروني مطلوب");
      } else if (!isEmail(email)) {
        ok = false;
        setError("email", "يرجى إدخال بريد إلكتروني صحيح");
      }

      if (!phone) {
        ok = false;
        setError("phone", "رقم الهاتف مطلوب");
      }

      if (!city) {
        ok = false;
        setError("city", "المدينة مطلوبة");
      }

      if (!password || password.length < 8) {
        ok = false;
        setError("password", "يجب أن تكون كلمة المرور 8 أحرف على الأقل");
      }

      if (!confirmPassword) {
        ok = false;
        setError("confirmPassword", "يرجى تأكيد كلمة المرور.");
      } else if (confirmPassword !== password) {
        ok = false;
        setError("confirmPassword", "كلمتا المرور غير متطابقتين");
      }

      if (!accountType) {
        ok = false;
        setError("accountType", "نوع الحساب مطلوب");
      }
      return ok;
    }

    function setupPasswordToggles() {
    // Use the wrapper structure from the HTML to reliably find the toggle button + input.
      const wrappers = form.querySelectorAll(".relative");
      wrappers.forEach((wrapper) => {
        const input = wrapper.querySelector('input[name="password"], input[name="confirmPassword"]');
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
          if (sr) sr.textContent = isPassword ? "إخفاء كلمة المرور" : "إظهار كلمة المرور";
        });
      });
    }

    let cityDropdown;
    let userSelectedCity = false;
    let selectedCityValue = "";
    function ensureCityDropdown() {
      if (cityDropdown) return cityDropdown;
      cityDropdown = document.createElement("div");
      cityDropdown.className = "bg-popover border border-border rounded-md shadow-xs overflow-auto";
      cityDropdown.style.position = "absolute";
      cityDropdown.style.zIndex = "50";
      cityDropdown.style.left = "0";
      cityDropdown.style.right = "0";
      cityDropdown.style.marginTop = "6px";
      cityDropdown.style.maxHeight = "240px";
      cityDropdown.hidden = true;

      const wrapper = els.cityTrigger?.parentElement;
      if (wrapper) {
        wrapper.style.position = "relative";
        wrapper.appendChild(cityDropdown);
      }

      document.addEventListener("click", (e) => {
        if (!cityDropdown || cityDropdown.hidden) return;
        const inside = cityDropdown.contains(e.target) || els.cityTrigger?.contains(e.target);
        if (!inside) cityDropdown.hidden = true;
      });

      return cityDropdown;
    }

    function setCity(value, label) {
      if (els.cityValue) els.cityValue.value = value;
      if (els.cityTrigger) {
        els.cityTrigger.textContent = label;
      // re-add chevrons icon if it was removed by textContent
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg.setAttribute("xmlns", "http://www.w3.org/2000/svg");
      svg.setAttribute("width", "24");
      svg.setAttribute("height", "24");
      svg.setAttribute("viewBox", "0 0 24 24");
      svg.setAttribute("fill", "none");
      svg.setAttribute("stroke", "currentColor");
      svg.setAttribute("stroke-width", "2");
      svg.setAttribute("stroke-linecap", "round");
      svg.setAttribute("stroke-linejoin", "round");
      svg.setAttribute("class", "lucide lucide-chevrons-up-down me-2 h-4 w-4 shrink-0 opacity-50");
        const p1 = document.createElementNS("http://www.w3.org/2000/svg", "path");
      p1.setAttribute("d", "m7 15 5 5 5-5");
        const p2 = document.createElementNS("http://www.w3.org/2000/svg", "path");
      p2.setAttribute("d", "m7 9 5-5 5 5");
        svg.appendChild(p1);
        svg.appendChild(p2);
        els.cityTrigger.appendChild(svg);
      }
      setError("city", "");
    }

    function setCityFromUser(value, label) {
      selectedCityValue = String(value || "");
      userSelectedCity = Boolean(value);
      setCity(value, label);
      syncSelectedCityUI();
    }

    function syncSelectedCityUI() {
      if (!cityDropdown) return;
      const items = cityDropdown.querySelectorAll('button[role="option"][data-value]');
      items.forEach((btn) => {
        const v = String(btn.getAttribute("data-value") || "");
        const isSelected = selectedCityValue && v === selectedCityValue;
        if (isSelected) btn.classList.add("bg-accent/15");
        else btn.classList.remove("bg-accent/15");
      });
    }

    async function loadCities() {
      if (!els.cityTrigger) return;
      const dropdown = ensureCityDropdown();
      const ajaxUrl = getAjaxUrl();

    // UX fallback: allow selecting immediately even if AJAX fails.
      dropdown.innerHTML = "";
      FALLBACK_CITIES.forEach((c) => {
        const item = document.createElement("button");
        item.type = "button";
        item.textContent = c.label;
        item.className = "w-full text-start px-4 py-2 text-sm bg-transparent hover:bg-accent transition-colors cursor-pointer border-0";
        item.setAttribute("role", "option");
        item.setAttribute("data-value", c.value);
        item.addEventListener("click", () => {
          setCityFromUser(String(c.value), String(c.label));
          dropdown.hidden = true;
        });
        dropdown.appendChild(item);
      });

      els.cityTrigger.disabled = false;
      setCity("", "اختر المدينة");

      try {
      // Start fetching real cities; if it fails we keep fallback list.
        els.cityTrigger.disabled = false;
        setCity("", "جاري تحميل المدن...");

        const url = new URL(ajaxUrl);
        url.searchParams.set("action", "bina_get_cities");

        const timeoutMs = 6000;
        const res = await Promise.race([
          fetch(url.toString(), { credentials: "same-origin" }),
          new Promise((_, reject) => setTimeout(() => reject(new Error("timeout")), timeoutMs)),
        ]);
        const data = await res.json();

        if (!res.ok || !data || data.success !== true || !Array.isArray(data.data)) {
          throw new Error("bad_response");
        }

        dropdown.innerHTML = "";
        data.data.forEach((c) => {
          const item = document.createElement("button");
          item.type = "button";
          item.textContent = c.label;
          item.className = "w-full text-start px-4 py-2 text-sm bg-transparent hover:bg-accent transition-colors cursor-pointer border-0";
          item.setAttribute("role", "option");
          item.setAttribute("data-value", c.value);
          item.addEventListener("click", () => {
            setCityFromUser(String(c.value), String(c.label));
            dropdown.hidden = true;
          });
          dropdown.appendChild(item);
        });

        els.cityTrigger.disabled = false;
        if (!userSelectedCity) setCity("", "اختر المدينة");
        syncSelectedCityUI();
      } catch (err) {
        els.cityTrigger.disabled = false;
        if (!userSelectedCity) setCity("", "اختر المدينة");
        syncSelectedCityUI();
      }
    }

    if (els.cityTrigger) {
      els.cityTrigger.addEventListener("click", () => {
        const dropdown = ensureCityDropdown();
        if (els.cityTrigger.disabled) return;
        dropdown.hidden = !dropdown.hidden;
      });
    }

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (!validate()) return;
      if (els.submitBtn) els.submitBtn.disabled = true;

      const payload = new URLSearchParams();
      payload.set("action", "bina_register_user");
      payload.set("firstName", (els.firstName?.value || "").trim());
      payload.set("lastName", (els.lastName?.value || "").trim());
      payload.set("email", (els.email?.value || "").trim());
      payload.set("phone", (els.phone?.value || "").trim());
      payload.set("city", (els.cityValue?.value || "").trim());
      payload.set("password", els.password?.value || "");
      payload.set("confirmPassword", els.confirmPassword?.value || "");
      payload.set("accountType", getAccountType());

      const ajaxUrl = getAjaxUrl();
      const url = new URL(ajaxUrl);
      const res = await fetch(url.toString(), {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body: payload.toString(),
        credentials: "same-origin",
      });

      let data;
      try {
        data = await res.json();
      } catch {
        data = null;
      }

      if (!res.ok || !data || data.success !== true) {
        const msg = (data && data.data && data.data.message) ? String(data.data.message) : "حدث خطأ أثناء إنشاء الحساب";
        const fieldErrors = data && data.data && data.data.fieldErrors ? data.data.fieldErrors : null;
        if (fieldErrors && typeof fieldErrors === "object") {
          Object.entries(fieldErrors).forEach(([k, v]) => setError(k, String(v)));
        } else {
          setError("confirmPassword", msg);
        }
        if (els.submitBtn) els.submitBtn.disabled = false;
        return;
      }

      const redirectUrl =
        (data.data && data.data.redirect_url ? String(data.data.redirect_url) : "") ||
        `${getSiteBase()}/`;
      window.location.href = redirectUrl;
    });

    setupAccountTypeRadios();
    setupPasswordToggles();
    loadCities();
  });
})();


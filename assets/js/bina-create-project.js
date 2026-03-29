(function () {
  const root = document.querySelector("[data-bina-create-project]");
  if (!root) return;

  const step1 = root.querySelector("#bina-step1");
  const step2 = root.querySelector("#bina-step2");
  const nextBtn = root.querySelector("#bina-next-step");
  const categoryInput = root.querySelector("#bina-category-hidden");
  const form = root.querySelector("[data-bina-project-form]");
  const badge1 = root.querySelector("#bina-step-badge-1");
  const badge2 = root.querySelector("#bina-step-badge-2");

  function getAjaxUrl() {
    if (window.bina && window.bina.ajaxurl) return String(window.bina.ajaxurl);
    return window.location.origin + "/wp-admin/admin-ajax.php";
  }

  root.querySelectorAll("[data-bina-category-card]").forEach(function (card) {
    card.addEventListener("click", function () {
      const val = card.getAttribute("data-category") || "";
      if (categoryInput) categoryInput.value = val;
      root.querySelectorAll("[data-bina-category-card]").forEach(function (c) {
        c.classList.remove("border-primary", "bg-primary/5", "ring-2", "ring-primary");
        c.setAttribute("aria-checked", "false");
        const chk = c.querySelector("[data-selected-check]");
        if (chk) chk.classList.add("hidden");
      });
      card.classList.add("border-primary", "bg-primary/5", "ring-2", "ring-primary");
      card.setAttribute("aria-checked", "true");
      const chk = card.querySelector("[data-selected-check]");
      if (chk) chk.classList.remove("hidden");
    });
  });

  if (nextBtn && step1 && step2) {
    nextBtn.addEventListener("click", function () {
      if (!categoryInput || !String(categoryInput.value).trim()) {
        alert("يرجى اختيار فئة المشروع");
        return;
      }
      step1.style.display = "none";
      step2.style.display = "";
      if (badge1) {
        badge1.classList.remove("bg-primary", "text-primary-foreground");
        badge1.classList.add("bg-muted", "text-muted-foreground");
      }
      if (badge2) {
        badge2.classList.add("bg-primary", "text-primary-foreground");
        badge2.classList.remove("bg-muted", "text-muted-foreground");
      }
    });
  }

  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const btn = form.querySelector('[type="submit"]');
    if (btn) btn.disabled = true;

    const payload = new URLSearchParams();
    const mode = form.getAttribute("data-bina-mode") || "create";
    if (mode === "edit") {
      payload.set("action", "bina_update_project");
      const pidEl = form.querySelector('[name="post_id"]');
      const pidAttr = form.getAttribute("data-post-id");
      const postId = pidEl && pidEl.value ? pidEl.value : pidAttr || "";
      if (!String(postId).trim()) {
        alert("معرّف المشروع غير صالح");
        if (btn) btn.disabled = false;
        return;
      }
      payload.set("post_id", String(postId).trim());
    } else {
      payload.set("action", "bina_save_project");
    }
    payload.set("nonce", form.getAttribute("data-nonce") || "");
    payload.set("title", (form.querySelector('[name="title"]') || {}).value || "");
    payload.set("description", (form.querySelector('[name="description"]') || {}).value || "");
    const catEl = document.getElementById("bina-category-hidden");
    payload.set("category", catEl ? catEl.value : (form.querySelector('[name="category"]') || {}).value || "");
    payload.set("reminder", (form.querySelector('[name="reminder"]') || {}).value || "");
    payload.set("city", (form.querySelector('[name="city"]') || {}).value || "");
    payload.set("neighborhood", (form.querySelector('[name="neighborhood"]') || {}).value || "");
    payload.set("street", (form.querySelector('[name="street"]') || {}).value || "");
    const st = form.querySelector('[name="start_timing"]:checked');
    payload.set("start_timing", st ? st.value : "");
    const hp = form.querySelector('[name="has_plans"]:checked');
    payload.set("has_plans", hp ? hp.value : "");
    const hph = form.querySelector('[name="has_photos"]:checked');
    payload.set("has_photos", hph ? hph.value : "");

    fetch(getAjaxUrl(), {
      method: "POST",
      credentials: "same-origin",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      body: payload.toString(),
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (data.success && data.data && data.data.redirect_url) {
          window.location.href = data.data.redirect_url;
          return;
        }
        const msg =
          (data.data && data.data.message) ||
          (data.message && String(data.message)) ||
          "تعذر حفظ المشروع";
        alert(msg);
      })
      .catch(function () {
        alert("حدث خطأ في الاتصال");
      })
      .finally(function () {
        if (btn) btn.disabled = false;
      });
  });
})();

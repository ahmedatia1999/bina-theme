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
  const wrapPlans = root.querySelector("[data-bina-upload-plans]");
  const wrapPhotos = root.querySelector("[data-bina-upload-photos]");

  function getAjaxUrl() {
    if (window.bina && window.bina.ajaxurl) return String(window.bina.ajaxurl);
    return window.location.origin + "/wp-admin/admin-ajax.php";
  }

  function syncUploadVisibility() {
    const hp = form ? form.querySelector('[name="has_plans"]:checked') : null;
    const hph = form ? form.querySelector('[name="has_photos"]:checked') : null;
    const showPlans = hp && hp.value === "نعم";
    const showPhotos = hph && hph.value === "نعم";
    if (wrapPlans) {
      if (showPlans) wrapPlans.classList.remove("hidden");
      else wrapPlans.classList.add("hidden");
    }
    if (wrapPhotos) {
      if (showPhotos) wrapPhotos.classList.remove("hidden");
      else wrapPhotos.classList.add("hidden");
    }
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

  if (form) {
    form.querySelectorAll('[name="has_plans"]').forEach(function (el) {
      el.addEventListener("change", syncUploadVisibility);
    });
    form.querySelectorAll('[name="has_photos"]').forEach(function (el) {
      el.addEventListener("change", syncUploadVisibility);
    });
    syncUploadVisibility();
  }

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

    const mode = form.getAttribute("data-bina-mode") || "create";
    const fd = new FormData();

    if (mode === "edit") {
      fd.append("action", "bina_update_project");
      const pidEl = form.querySelector('[name="post_id"]');
      const pidAttr = form.getAttribute("data-post-id");
      const postId = pidEl && pidEl.value ? pidEl.value : pidAttr || "";
      if (!String(postId).trim()) {
        alert("معرّف المشروع غير صالح");
        if (btn) btn.disabled = false;
        return;
      }
      fd.append("post_id", String(postId).trim());
    } else {
      fd.append("action", "bina_save_project");
    }

    fd.append("nonce", form.getAttribute("data-nonce") || "");
    fd.append("title", (form.querySelector('[name="title"]') || {}).value || "");
    fd.append("description", (form.querySelector('[name="description"]') || {}).value || "");
    const catEl = document.getElementById("bina-category-hidden");
    fd.append("category", catEl ? catEl.value : (form.querySelector('[name="category"]') || {}).value || "");
    fd.append("reminder", (form.querySelector('[name="reminder"]') || {}).value || "");
    fd.append("city", (form.querySelector('[name="city"]') || {}).value || "");
    fd.append("neighborhood", (form.querySelector('[name="neighborhood"]') || {}).value || "");
    fd.append("street", (form.querySelector('[name="street"]') || {}).value || "");
    const st = form.querySelector('[name="start_timing"]:checked');
    fd.append("start_timing", st ? st.value : "");
    const hp = form.querySelector('[name="has_plans"]:checked');
    fd.append("has_plans", hp ? hp.value : "");
    const hph = form.querySelector('[name="has_photos"]:checked');
    fd.append("has_photos", hph ? hph.value : "");

    const plansInput = form.querySelector("#bina-plans-files");
    if (plansInput && plansInput.files && plansInput.files.length) {
      for (let i = 0; i < plansInput.files.length; i++) {
        fd.append("bina_plans[]", plansInput.files[i]);
      }
    }

    const photosInput = form.querySelector("#bina-site-photos-files");
    if (photosInput && photosInput.files && photosInput.files.length) {
      for (let i = 0; i < photosInput.files.length; i++) {
        fd.append("bina_site_photos[]", photosInput.files[i]);
      }
    }

    fetch(getAjaxUrl(), {
      method: "POST",
      credentials: "same-origin",
      body: fd,
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

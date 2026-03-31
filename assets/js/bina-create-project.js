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

  const uploadingEl = root.querySelector("[data-bina-uploading]");

  function fileKey(f) {
    return [f.name, f.size, f.lastModified].join(":");
  }

  function rebuildInputFiles(input, files) {
    try {
      const dt = new DataTransfer();
      files.forEach((f) => dt.items.add(f));
      input.files = dt.files;
    } catch (e) {
      // If DataTransfer unsupported, fall back to native input behavior.
    }
  }

  function isImageFile(f) {
    const t = String(f.type || "");
    if (t.indexOf("image/") === 0) return true;
    const n = String(f.name || "").toLowerCase();
    return n.endsWith(".jpg") || n.endsWith(".jpeg") || n.endsWith(".png") || n.endsWith(".webp") || n.endsWith(".gif");
  }

  function isPdfFile(f) {
    const t = String(f.type || "");
    if (t === "application/pdf") return true;
    const n = String(f.name || "").toLowerCase();
    return n.endsWith(".pdf");
  }

  function renderPreviews(kind, input, files, container) {
    if (!container) return;
    container.innerHTML = "";
    files.forEach((f, idx) => {
      const wrap = document.createElement("div");
      wrap.className = "relative h-16 w-16 overflow-hidden rounded-md border bg-background";

      if (isImageFile(f)) {
        const img = document.createElement("img");
        img.className = "h-16 w-16 object-cover";
        img.alt = f.name || "";
        wrap.appendChild(img);
        const url = URL.createObjectURL(f);
        img.src = url;
        img.onload = () => {
          try { URL.revokeObjectURL(url); } catch (e) {}
        };
      } else {
        const box = document.createElement("div");
        box.className = "h-16 w-16 flex items-center justify-center text-[10px] text-muted-foreground p-1 text-center";
        box.textContent = isPdfFile(f) ? "PDF" : "FILE";
        wrap.appendChild(box);
      }

      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "absolute -top-2 -start-2 h-6 w-6 rounded-full bg-destructive text-white text-xs leading-none flex items-center justify-center shadow";
      btn.textContent = "×";
      btn.setAttribute("aria-label", "remove");
      btn.addEventListener("click", () => {
        files.splice(idx, 1);
        rebuildInputFiles(input, files);
        renderPreviews(kind, input, files, container);
      });
      wrap.appendChild(btn);

      container.appendChild(wrap);
    });
  }

  function wireFilePreview(kind, inputId, containerSelector) {
    const input = form.querySelector(inputId);
    const container = root.querySelector(containerSelector);
    if (!input || !container) return;

    const state = { files: [] };

    input.addEventListener("change", () => {
      const next = Array.from(input.files || []);
      if (!next.length) return;

      const map = new Map(state.files.map((f) => [fileKey(f), f]));
      next.forEach((f) => map.set(fileKey(f), f));
      state.files = Array.from(map.values()).slice(0, 15);

      rebuildInputFiles(input, state.files);
      renderPreviews(kind, input, state.files, container);
    });

    // If browser keeps chosen files on back/forward cache, render once.
    const existing = Array.from(input.files || []);
    if (existing.length) {
      state.files = existing.slice(0, 15);
      renderPreviews(kind, input, state.files, container);
    }
  }

  wireFilePreview("plans", "#bina-plans-files", '[data-bina-file-previews="plans"]');
  wireFilePreview("photos", "#bina-site-photos-files", '[data-bina-file-previews="photos"]');

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const btn = form.querySelector('[type="submit"]');
    if (btn) btn.disabled = true;
    if (uploadingEl) {
      uploadingEl.classList.remove("hidden");
      uploadingEl.classList.add("inline-flex");
    }

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
        if (uploadingEl) {
          uploadingEl.classList.add("hidden");
          uploadingEl.classList.remove("inline-flex");
        }
      });
  });
})();

(function () {
  const root = document.querySelector("[data-bina-project-chat]");
  if (!root) return;

  const active = root.getAttribute("data-thread-active") === "1";
  if (!active) return;

  const projectId = parseInt(String(root.getAttribute("data-project-id") || "0"), 10) || 0;
  if (projectId < 1) return;

  const cfg = window.binaProjectMessages || {};
  const ajaxurl = cfg.ajaxurl || window.ajaxurl || "/wp-admin/admin-ajax.php";
  const nonce = cfg.nonce || "";
  const currentUserId = parseInt(String(cfg.currentUserId || "0"), 10) || 0;
  const portalRole = String(root.getAttribute("data-bina-portal-role") || "");

  const scrollEl = root.querySelector("[data-bina-thread-scroll]");
  const listEl = root.querySelector("[data-bina-thread-messages]");
  const emptyEl = root.querySelector("[data-bina-thread-empty]");
  const form = root.querySelector("[data-bina-thread-form]");
  const input = root.querySelector("[data-bina-thread-input]");
  const sendBtn = root.querySelector("[data-bina-thread-send]");
  const filesInput = root.querySelector("[data-bina-thread-files]");
  const previewsEl = root.querySelector("[data-bina-thread-previews]");

  const fileState = { files: [] };
  const isRTL = (function () {
    try {
      const el = document.documentElement || document.body;
      const dirAttr =
        (el && el.getAttribute && el.getAttribute("dir")) ||
        (root && root.closest && root.closest("[dir]") && root.closest("[dir]").getAttribute("dir")) ||
        "";
      if (String(dirAttr).toLowerCase() === "rtl") return true;
      if (String(dirAttr).toLowerCase() === "ltr") return false;
      const computed = window.getComputedStyle(document.body || document.documentElement);
      return computed && String(computed.direction).toLowerCase() === "rtl";
    } catch (e) {
      return true;
    }
  })();

  function fileKey(f) {
    return [f.name, f.size, f.lastModified].join(":");
  }

  function rebuildFilesInput() {
    if (!filesInput) return;
    try {
      const dt = new DataTransfer();
      fileState.files.forEach((f) => dt.items.add(f));
      filesInput.files = dt.files;
    } catch (e) {}
  }

  function isImageFile(f) {
    const t = String(f.type || "");
    return t.indexOf("image/") === 0;
  }

  function renderPreviews() {
    if (!previewsEl) return;
    previewsEl.innerHTML = "";
    if (!fileState.files.length) {
      previewsEl.classList.add("hidden");
      return;
    }
    previewsEl.classList.remove("hidden");
    previewsEl.classList.add("flex");
    fileState.files.forEach((f, idx) => {
      const wrap = document.createElement("div");
      wrap.className = "relative h-14 w-14 overflow-hidden rounded-md border bg-background";
      if (isImageFile(f)) {
        const img = document.createElement("img");
        img.className = "h-14 w-14 object-cover";
        img.alt = f.name || "";
        const url = URL.createObjectURL(f);
        img.src = url;
        img.onload = () => {
          try { URL.revokeObjectURL(url); } catch (e) {}
        };
        wrap.appendChild(img);
      } else {
        const box = document.createElement("div");
        box.className = "h-14 w-14 flex items-center justify-center text-[10px] text-muted-foreground p-1 text-center";
        box.textContent = "FILE";
        wrap.appendChild(box);
      }
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "absolute -top-2 -start-2 h-6 w-6 rounded-full bg-destructive text-white text-xs leading-none flex items-center justify-center shadow";
      btn.textContent = "×";
      btn.addEventListener("click", () => {
        fileState.files.splice(idx, 1);
        rebuildFilesInput();
        renderPreviews();
      });
      wrap.appendChild(btn);
      previewsEl.appendChild(wrap);
    });
  }

  let maxId = 0;
  let pollTimer = null;
  let isFetching = false;

  function displayNameForMessage(m) {
    const name = m && m.sender_name ? String(m.sender_name).trim() : "";
    if (name) return name;
    return m && m.is_mine ? "أنت" : "";
  }

  function appendMessages(items, replace) {
    if (!listEl) return;
    if (replace) {
      listEl.innerHTML = "";
    }
    if (!items || !items.length) {
      if (replace && emptyEl) {
        emptyEl.hidden = false;
        listEl.hidden = true;
      }
      return;
    }
    if (emptyEl) {
      emptyEl.hidden = true;
      listEl.hidden = false;
    }
    items.forEach(function (m) {
      const id = parseInt(String(m.id), 10) || 0;
      if (id > maxId) maxId = id;
      // Align by role with fallbacks:
      // - customer => right
      // - service_provider => left
      // Some payloads may miss sender_role, so fallback to sender_id/is_mine + current portal role.
      const role = m && m.sender_role ? String(m.sender_role) : "";
      const senderId = parseInt(String((m && m.sender_id) || "0"), 10) || 0;
      const mine = !!m.is_mine;
      let isCustomer = role === "customer";
      let isProvider = role === "service_provider";

      if (!isCustomer && !isProvider) {
        if (senderId > 0 && currentUserId > 0 && senderId === currentUserId) {
          isCustomer = portalRole === "customer";
          isProvider = portalRole === "service_provider";
        } else if (mine) {
          isCustomer = portalRole === "customer";
          isProvider = portalRole === "service_provider";
        } else {
          isCustomer = portalRole === "service_provider";
          isProvider = portalRole === "customer";
        }
      }
      // In RTL layouts, flex-start is right. Force logical right/left.
      const alignRight = isCustomer;
      const justify = (function () {
        // Want: customer on right, provider on left.
        // RTL: right = flex-start, left = flex-end
        if (isRTL) return alignRight ? "justify-start" : "justify-end";
        return alignRight ? "justify-end" : "justify-start";
      })();
      const wrap = document.createElement("div");
      wrap.className = "flex " + justify;
      wrap.setAttribute("data-msg-id", String(id));

      const meta = document.createElement("div");
      meta.className =
        "mb-1 text-[11px] text-muted-foreground " +
        (alignRight ? "text-end" : "text-start");
      meta.textContent = displayNameForMessage(m);

      const bubble = document.createElement("div");
      const bubbleTone = isCustomer
        ? "bg-primary text-primary-foreground rounded-br-sm"
        : "bg-muted text-foreground rounded-bl-sm";
      bubble.className =
        "max-w-[85%] rounded-2xl px-3 py-2 text-sm whitespace-pre-wrap break-words shadow-sm ring-1 ring-border/20 " +
        bubbleTone;
      bubble.textContent = m.body || "";

      if (m && Array.isArray(m.attachments) && m.attachments.length) {
        const atWrap = document.createElement("div");
        atWrap.className = "mt-2 flex flex-wrap gap-2";
        m.attachments.forEach((a) => {
          if (!a || !a.url) return;
          const isImg = !!a.is_image;
          const card = document.createElement("div");
          card.className = "overflow-hidden rounded border border-border/50 bg-background/50";

          const open = document.createElement("a");
          // Some generated image sizes may be missing on disk; always open original file URL.
          open.href = String(a.url || a.view || "#");
          open.target = "_blank";
          open.rel = "noopener noreferrer";
          open.className = "block relative";

          if (isImg) {
            const img = document.createElement("img");
            img.src = String(a.thumb || a.url);
            img.alt = String(a.title || "");
            img.className = "h-20 w-20 object-cover";
            open.appendChild(img);

            // Open image immediately in a new tab.
            open.addEventListener("click", function (ev) {
              ev.preventDefault();
              const src = String(a.url || "");
              if (!src) return;
              const sep = src.indexOf("?") > -1 ? "&" : "?";
              const viewUrl = src + sep + "_r=" + Date.now();
              window.open(viewUrl, "_blank", "noopener,noreferrer");
            });
          } else {
            const box = document.createElement("div");
            box.className = "h-12 w-40 max-w-[12rem] px-2 flex items-center text-xs";
            box.textContent = String(a.title || "ملف");
            open.appendChild(box);
          }

          // Visible download button (works even if overlay styles are missed).
          const dlRow = document.createElement("div");
          dlRow.className = "px-2 py-1 border-t border-border/40 bg-white";
          const dl = document.createElement("a");
          dl.href = String(a.url);
          dl.setAttribute("download", "");
          dl.className = "inline-flex items-center gap-1 text-xs font-medium text-primary hover:underline";
          dl.innerHTML =
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg><span>تحميل</span>';
          dlRow.appendChild(dl);

          card.appendChild(open);
          card.appendChild(dlRow);
          atWrap.appendChild(card);
        });
        bubble.appendChild(atWrap);
      }
      const stack = document.createElement("div");
      stack.className = "max-w-[85%]";
      if (meta.textContent) stack.appendChild(meta);
      stack.appendChild(bubble);
      wrap.appendChild(stack);
      listEl.appendChild(wrap);
    });
    if (scrollEl) {
      scrollEl.scrollTop = scrollEl.scrollHeight;
    }
  }

  function fetchMessages() {
    if (isFetching) return Promise.resolve();
    isFetching = true;
    const body = new URLSearchParams();
    body.set("action", "bina_get_thread_messages");
    body.set("nonce", nonce);
    body.set("project_id", String(projectId));
    body.set("since_id", String(maxId));

    return fetch(ajaxurl, {
      method: "POST",
      credentials: "same-origin",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      body: body.toString(),
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (res) {
        if (!res.success || !res.data || !res.data.messages) return;
        const msgs = res.data.messages;
        if (msgs.length && maxId === 0) {
          appendMessages(msgs, true);
        } else if (msgs.length) {
          appendMessages(msgs, false);
        }
      })
      .catch(function () {})
      .finally(function () {
        isFetching = false;
      });
  }

  function sendMessage(text) {
    const fd = new FormData();
    fd.append("action", "bina_send_thread_message");
    fd.append("nonce", nonce);
    fd.append("project_id", String(projectId));
    fd.append("body", text);
    if (filesInput && filesInput.files && filesInput.files.length) {
      Array.from(filesInput.files).slice(0, 6).forEach((f) => fd.append("attachments[]", f));
    }

    if (sendBtn) sendBtn.disabled = true;
    return fetch(ajaxurl, {
      method: "POST",
      credentials: "same-origin",
      body: fd,
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (res) {
        if (res.success && res.data && res.data.message) {
          appendMessages([res.data.message], false);
          if (input) input.value = "";
          if (filesInput) filesInput.value = "";
          fileState.files = [];
          renderPreviews();
        } else {
          const msg =
            (res.data && res.data.message) ||
            (res.message && String(res.message)) ||
            "تعذر الإرسال";
          alert(msg);
        }
      })
      .catch(function () {
        alert("حدث خطأ في الاتصال");
      })
      .finally(function () {
        if (sendBtn) sendBtn.disabled = false;
      });
  }

  fetchMessages();
  pollTimer = window.setInterval(fetchMessages, 4000);

  document.addEventListener("visibilitychange", function () {
    if (!document.hidden) fetchMessages();
  });
  window.addEventListener("focus", function () {
    fetchMessages();
  });

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const t = input ? String(input.value || "").trim() : "";
      const hasFiles = !!(filesInput && filesInput.files && filesInput.files.length);
      if (!t && !hasFiles) return;
      sendMessage(t);
    });
  }

  if (filesInput) {
    filesInput.addEventListener("change", function () {
      const next = Array.from(filesInput.files || []);
      if (!next.length) return;
      const map = new Map(fileState.files.map((f) => [fileKey(f), f]));
      next.forEach((f) => map.set(fileKey(f), f));
      fileState.files = Array.from(map.values()).slice(0, 6);
      rebuildFilesInput();
      renderPreviews();
    });
  }
})();

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

  const scrollEl = root.querySelector("[data-bina-thread-scroll]");
  const listEl = root.querySelector("[data-bina-thread-messages]");
  const emptyEl = root.querySelector("[data-bina-thread-empty]");
  const form = root.querySelector("[data-bina-thread-form]");
  const input = root.querySelector("[data-bina-thread-input]");
  const sendBtn = root.querySelector("[data-bina-thread-send]");

  let maxId = 0;
  let pollTimer = null;

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
      const mine = !!m.is_mine;
      const wrap = document.createElement("div");
      wrap.className =
        "flex " + (mine ? "justify-end" : "justify-start");
      wrap.setAttribute("data-msg-id", String(id));
      const bubble = document.createElement("div");
      bubble.className =
        "max-w-[85%] rounded-2xl px-3 py-2 text-sm whitespace-pre-wrap break-words " +
        (mine
          ? "bg-primary text-primary-foreground rounded-br-sm"
          : "bg-muted text-foreground rounded-bl-sm");
      bubble.textContent = m.body || "";
      wrap.appendChild(bubble);
      listEl.appendChild(wrap);
    });
    if (scrollEl) {
      scrollEl.scrollTop = scrollEl.scrollHeight;
    }
  }

  function fetchMessages() {
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
      .catch(function () {});
  }

  function sendMessage(text) {
    const body = new URLSearchParams();
    body.set("action", "bina_send_thread_message");
    body.set("nonce", nonce);
    body.set("project_id", String(projectId));
    body.set("body", text);

    if (sendBtn) sendBtn.disabled = true;
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
        if (res.success && res.data && res.data.message) {
          appendMessages([res.data.message], false);
          if (input) input.value = "";
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
  pollTimer = window.setInterval(fetchMessages, 25000);

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const t = input ? String(input.value || "").trim() : "";
      if (!t) return;
      sendMessage(t);
    });
  }
})();

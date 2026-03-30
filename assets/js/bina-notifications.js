(function () {
  const app = document.querySelector("[data-bina-notifications-app]");
  if (!app) return;

  const ajaxurl = app.getAttribute("data-ajaxurl") || "/wp-admin/admin-ajax.php";
  const nonce = app.getAttribute("data-nonce") || "";
  const chatBaseUrl = app.getAttribute("data-chat-base-url") || "";
  const pollMs = parseInt(app.getAttribute("data-poll-ms") || "8000", 10) || 8000;

  const unreadCountEl = document.querySelector("[data-bina-unread-count]");
  const listEl = document.querySelector("[data-bina-notifications-list]");

  let isFetching = false;
  let knownUnreadIds = new Set();

  function chatUrlForProject(projectId) {
    const pid = parseInt(projectId, 10) || 0;
    if (pid < 1) return chatBaseUrl || "/";
    const sep = chatBaseUrl.indexOf("?") > -1 ? "&" : "?";
    return chatBaseUrl + sep + "project_id=" + pid;
  }

  function formatCreatedAt(s) {
    if (!s) return "";
    // s is like "2026-03-30 12:34:56"
    return String(s).replace(" ", " ").slice(5, 16); // "MM-DD HH:MM"-ish
  }

  function notifyNoItems() {
    if (!listEl) return;
    listEl.innerHTML =
      '<div class="rounded-xl border bg-muted/30 p-8 text-center text-muted-foreground text-sm">لا توجد إشعارات.</div>';
  }

  function renderItems(items, opts) {
    opts = opts || {};
    const replace = !!opts.replace;
    if (!listEl) return;
    if (replace) listEl.innerHTML = "";
    if (!items || !items.length) {
      if (replace) notifyNoItems();
      return;
    }

    const frag = document.createDocumentFragment();
    items.forEach(function (n) {
      const nid = parseInt(String(n.id), 10) || 0;
      if (replace !== true && knownUnreadIds.has(nid) && n.is_read === 0) return;

      const isUnread = String(n.is_read) === "0";
      const wrapper = document.createElement("div");
      wrapper.className = "rounded-xl border border-border/80 bg-card p-4 shadow-sm";

      const title = n.title ? String(n.title) : "إشعار";
      const body = n.body ? String(n.body) : "";
      const projectId = n.project_id ? String(n.project_id) : "0";
      const url = chatUrlForProject(projectId);

      const badge = isUnread
        ? '<span class="inline-flex items-center justify-center rounded-full bg-destructive px-2 py-0.5 text-[10px] text-white font-medium" style="margin-left:8px">جديد</span>'
        : "";

      wrapper.innerHTML =
        '<a href="' +
        encodeURI(url) +
        '" class="block hover:opacity-95" data-bina-notif-id="' +
        nid +
        '">' +
        '<div class="flex items-start justify-between gap-4">' +
        '<div class="min-w-0 flex-1">' +
        '<div class="text-sm font-semibold text-foreground">' +
        badge +
        title +
        "</div>" +
        '<div class="text-xs text-muted-foreground mt-1">' +
        (n.sender_name ? String(n.sender_name) + " • " : "") +
        (n.project_id ? "مشروع" : "") +
        (n.created_at ? " • " + formatCreatedAt(n.created_at) : "") +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="text-sm text-muted-foreground mt-3 whitespace-pre-wrap break-words line-clamp-4">' +
        body +
        "</div>" +
        "</a>";

      frag.appendChild(wrapper);

      if (isUnread) {
        knownUnreadIds.add(nid);
      }
    });

    if (replace) {
      listEl.appendChild(frag);
    } else {
      listEl.insertBefore(frag, listEl.firstChild);
    }
  }

  function markAllRead() {
    const body = new URLSearchParams();
    body.set("action", "bina_mark_all_notifications_read");
    body.set("nonce", nonce);
    return fetch(ajaxurl, {
      method: "POST",
      credentials: "same-origin",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      body: body.toString(),
    })
      .then(function (r) {
        return r.json();
      })
      .catch(function () {});
  }

  function fetchList(onlyUnread, replace) {
    if (isFetching) return Promise.resolve();
    isFetching = true;
    const body = new URLSearchParams();
    body.set("action", "bina_get_notifications_list");
    body.set("nonce", nonce);
    body.set("only_unread", onlyUnread ? "1" : "0");
    body.set("limit", "20");

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
        if (!res.success || !res.data) return;
        const items = res.data.notifications || [];
        const unreadCount = res.data.unread_count || 0;
        if (unreadCountEl) unreadCountEl.textContent = unreadCount;
        if (onlyUnread) renderItems(items, { replace: !!replace });
        else renderItems(items, { replace: true });
      })
      .catch(function () {})
      .finally(function () {
        isFetching = false;
      });
  }

  function pollUnread() {
    fetchList(true, false);
  }

  // Mark all read on page open (so bell badge and unread count clear).
  markAllRead().then(function () {
    const bellEl = document.querySelector("[data-bina-unread-notifications-bell]");
    if (bellEl) {
      bellEl.textContent = "0";
      bellEl.classList.add("hidden");
    }
    fetchList(false, true);
    pollUnread();
    setInterval(pollUnread, pollMs);
  });

  // Manual mark-all read.
  const markAllBtn = document.querySelector("[data-bina-mark-all]");
  if (markAllBtn) {
    markAllBtn.addEventListener("click", function () {
      markAllRead().then(function () {
        knownUnreadIds = new Set();
        const bellEl = document.querySelector("[data-bina-unread-notifications-bell]");
        if (bellEl) {
          bellEl.textContent = "0";
          bellEl.classList.add("hidden");
        }
        fetchList(false, true);
        pollUnread();
      });
    });
  }

  // Clicking a notification should mark it read quickly (best-effort).
  if (listEl) {
    listEl.addEventListener("click", function (e) {
      const a = e.target.closest("[data-bina-notif-id]");
      if (!a) return;
      const nid = a.getAttribute("data-bina-notif-id");
      if (!nid) return;
      const href = a.getAttribute("href") || "";
      e.preventDefault();
      const body = new URLSearchParams();
      body.set("action", "bina_mark_notification_read");
      body.set("nonce", nonce);
      body.set("notification_id", nid);
      fetch(ajaxurl, {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body: body.toString(),
      })
        .catch(function () {})
        .finally(function () {
          if (href) window.location.href = href;
        });
    });
  }
})();


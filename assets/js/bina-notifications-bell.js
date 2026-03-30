(function () {
  const cfg = window.binaNotificationsBell || null;
  if (!cfg || !cfg.ajaxurl || !cfg.nonce) return;

  const pollMs = parseInt(String(cfg.pollMs || "8000"), 10) || 8000;
  const el = document.querySelector("[data-bina-unread-notifications-bell]");
  if (!el) return;

  const cardEl = document.querySelector("[data-bina-unread-notifications-card]");

  function setCount(n) {
    const count = parseInt(String(n || "0"), 10) || 0;
    el.textContent = String(count);
    if (count > 0) el.classList.remove("hidden");
    else el.classList.add("hidden");

    if (cardEl) cardEl.textContent = String(count);
  }

  function fetchCount() {
    const body = new URLSearchParams();
    body.set("action", "bina_get_unread_notifications_count");
    body.set("nonce", cfg.nonce);

    return fetch(cfg.ajaxurl, {
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
        setCount(res.data.unread_count || 0);
      })
      .catch(function () {});
  }

  fetchCount();
  setInterval(fetchCount, pollMs);
})();


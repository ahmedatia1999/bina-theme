(() => {
  async function post(ajaxUrl, data) {
    const res = await fetch(ajaxUrl, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      body: new URLSearchParams(data).toString(),
      credentials: "same-origin",
    });
    const json = await res.json().catch(() => null);
    if (!json) throw new Error("Bad response");
    return json;
  }

  function init(root) {
    const ajaxUrl = root.getAttribute("data-ajaxurl") || "";
    const nonce = root.getAttribute("data-nonce") || "";
    if (!ajaxUrl || !nonce) return;

    root.addEventListener("click", async (e) => {
      const btn = e.target.closest("[data-bina-accept-proposal]");
      if (!btn) return;
      const item = e.target.closest("[data-bina-proposal-item]");
      if (!item) return;
      const proposalId = item.getAttribute("data-proposal-id") || "";
      const msg = item.querySelector("[data-bina-proposal-action-msg]");
      if (btn.dataset.loading === "1") return;
      btn.dataset.loading = "1";
      if (msg) msg.textContent = "جارٍ قبول العرض...";
      btn.disabled = true;
      btn.textContent = "جارٍ القبول...";

      try {
        const json = await post(ajaxUrl, {
          action: "bina_accept_proposal",
          nonce,
          proposal_id: proposalId,
        });
        if (!json.success) {
          throw new Error((json.data && json.data.message) || "تعذر قبول العرض.");
        }
        if (msg) msg.textContent = "تم قبول العرض، جارٍ تحديث الحالة...";

        // Keep loading state until refreshed with server-confirmed status.
        root.querySelectorAll("[data-bina-accept-proposal]").forEach((b) => {
          b.disabled = true;
          b.dataset.loading = "1";
          b.classList.add("opacity-60", "cursor-not-allowed");
          b.textContent = "جارٍ القبول...";
        });

        const u = new URL(window.location.href);
        u.searchParams.set("_", String(Date.now()));
        window.location.replace(u.toString());
        return;
      } catch (err) {
        if (msg) msg.textContent = (err && err.message) || "تعذر قبول العرض.";
        btn.dataset.loading = "0";
        btn.disabled = false;
        btn.textContent = "قبول العرض";
      }
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-bina-customer-proposals]").forEach(init);
  });
})();


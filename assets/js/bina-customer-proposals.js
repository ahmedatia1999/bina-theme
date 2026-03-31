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
      if (msg) msg.textContent = "جارٍ التنفيذ...";
      btn.disabled = true;

      try {
        const json = await post(ajaxUrl, {
          action: "bina_accept_proposal",
          nonce,
          proposal_id: proposalId,
        });
        if (!json.success) {
          throw new Error((json.data && json.data.message) || "تعذر قبول العرض.");
        }
        if (msg) msg.textContent = "";
        btn.remove();
        const ok = document.createElement("div");
        ok.className = "text-sm text-emerald-700 font-medium";
        ok.textContent = "تم قبول العرض";
        item.appendChild(ok);

        // Disable/hide other accept buttons (project is locked after acceptance).
        root.querySelectorAll("[data-bina-accept-proposal]").forEach((b) => {
          b.disabled = true;
          b.classList.add("opacity-60", "cursor-not-allowed");
        });

        window.setTimeout(() => window.location.reload(), 900);
      } catch (err) {
        if (msg) msg.textContent = (err && err.message) || "تعذر قبول العرض.";
        btn.disabled = false;
      }
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-bina-customer-proposals]").forEach(init);
  });
})();


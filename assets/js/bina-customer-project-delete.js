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

  document.addEventListener("DOMContentLoaded", () => {
    const root = document.querySelector("[data-bina-customer-proposals]");
    if (!root) return;

    const ajaxUrl = root.getAttribute("data-ajaxurl") || "";
    const nonce = root.getAttribute("data-delete-nonce") || "";
    const projectId = root.getAttribute("data-project-id") || "";
    const backUrl = root.getAttribute("data-my-projects-url") || "/";
    if (!ajaxUrl || !nonce || !projectId) return;

    root.addEventListener("click", async (e) => {
      const btn = e.target.closest("[data-bina-delete-project]");
      if (!btn) return;

      const ok = window.confirm("هل أنت متأكد من حذف المشروع؟ سيتم نقله لسلة المهملات.");
      if (!ok) return;

      btn.disabled = true;
      btn.classList.add("opacity-60", "cursor-not-allowed");

      try {
        const json = await post(ajaxUrl, {
          action: "bina_delete_project",
          nonce,
          post_id: projectId,
        });
        if (!json.success) {
          throw new Error((json.data && json.data.message) || "تعذر حذف المشروع.");
        }
        window.location.href = backUrl;
      } catch (err) {
        window.alert((err && err.message) || "تعذر حذف المشروع.");
        btn.disabled = false;
        btn.classList.remove("opacity-60", "cursor-not-allowed");
      }
    });
  });
})();


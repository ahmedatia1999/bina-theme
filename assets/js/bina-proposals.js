(() => {
  function qs(root, sel) {
    return root.querySelector(sel);
  }

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

  function toMoney(value) {
    const n = Number(String(value || "").replace(",", "."));
    if (!isFinite(n)) return 0;
    return Math.round(n * 100) / 100;
  }

  function splitEqual(total, n) {
    const t = toMoney(total);
    const nn = Number(n || 0);
    if (!isFinite(nn) || nn < 1) return [];
    const base = Math.round((t / nn) * 100) / 100;
    const out = Array.from({ length: nn }, () => base);
    const sum = Math.round(out.reduce((a, b) => a + b, 0) * 100) / 100;
    const diff = Math.round((t - sum) * 100) / 100;
    out[nn - 1] = Math.round((out[nn - 1] + diff) * 100) / 100;
    return out;
  }

  function safeJsonParse(s) {
    try {
      const v = JSON.parse(String(s || ""));
      return v && typeof v === "object" ? v : null;
    } catch (e) {
      return null;
    }
  }

  function serializePlanMeta(planKey, total, items) {
    return JSON.stringify({
      plan_key: planKey,
      total: isFinite(total) ? Number(total.toFixed(2)) : 0,
      items,
    });
  }

  function init(root) {
    const ajaxUrl = root.getAttribute("data-ajaxurl") || "";
    const nonce = root.getAttribute("data-nonce") || "";
    if (!ajaxUrl || !nonce) return;

    function renderBreakdown(form) {
      const planSel = form.querySelector('select[name="plan_key"]');
      const priceEl = form.querySelector('input[name="price_total"]');
      const durationRow = form.querySelector("[data-bina-duration-row]");
      const durationEl = form.querySelector('input[name="duration_days"]');
      const bdRoot = form.querySelector("[data-bina-plan-breakdown-root]");
      const bd = form.querySelector("[data-bina-plan-breakdown]");
      const hint = form.querySelector("[data-bina-plan-total-hint]");
      const planMetaInput = form.querySelector('input[name="plan_meta"]');
      if (!bdRoot || !bd || !planSel || !priceEl || !planMetaInput) return;

      const planKey = String(planSel.value || "pay_at_completion");
      const total = toMoney(priceEl.value || "");
      const existing = safeJsonParse(planMetaInput.value);

      if (durationRow && durationEl) {
        if (planKey === "four_installments_equal") {
          durationRow.classList.add("hidden");
          durationEl.value = durationEl.value && String(durationEl.value).trim() !== "" ? durationEl.value : "120";
        } else if (planKey === "eleven_months") {
          durationRow.classList.add("hidden");
          durationEl.value = durationEl.value && String(durationEl.value).trim() !== "" ? durationEl.value : "330";
        } else {
          durationRow.classList.remove("hidden");
        }
      }

      if (planKey === "pay_at_completion") {
        bdRoot.classList.add("hidden");
        planMetaInput.value = "";
        if (hint) {
          hint.textContent = "";
          hint.classList.remove("text-destructive");
        }
        return;
      }
      bdRoot.classList.remove("hidden");

      const n = planKey === "four_installments_equal" ? 4 : 11;
      const defaultAmounts = splitEqual(total, n);
      const prevItems = Array.isArray(existing?.items) ? existing.items : [];
      const items = [];

      function updateSummary() {
        const sum = Math.round(items.reduce((a, it) => a + (Number(it.amount) || 0), 0) * 100) / 100;
        if (hint) {
          hint.textContent = `Ø§Ù„Ø¥Ø¬Ù…Ø§Ù„ÙŠ: ${sum.toFixed(2)} Ø±.Ø³ (Ø§Ù„Ù…Ø·Ù„ÙˆØ¨: ${total.toFixed(2)} Ø±.Ø³)`;
          hint.classList.toggle("text-destructive", Math.abs(sum - total) > 0.01);
        }
        planMetaInput.value = serializePlanMeta(planKey, total, items);
      }

      bd.innerHTML = "";

      for (let i = 0; i < n; i++) {
        const idx = i + 1;
        const prev = prevItems[i] && typeof prevItems[i] === "object" ? prevItems[i] : {};
        const title = planKey === "eleven_months" ? `Ø´Ù‡Ø± ${idx}` : `Ø¯ÙØ¹Ø© ${idx}`;
        const amount = prev.amount !== undefined && prev.amount !== null && prev.amount !== ""
          ? toMoney(prev.amount)
          : (defaultAmounts[i] ?? 0);

        const row = document.createElement("div");
        row.className = "rounded-md border border-border/60 bg-background p-2 space-y-2";
        row.innerHTML = `
          <div class="flex items-center justify-between gap-3">
            <div class="text-xs font-medium">${title}</div>
            <input type="number" min="0" step="0.01" class="w-28 rounded-md border border-input bg-transparent px-2 py-1.5 text-xs text-start" placeholder="0.00" value="${amount.toFixed(2)}" />
          </div>
          <textarea rows="2" class="w-full rounded-md border border-input bg-transparent px-2 py-1.5 text-xs" placeholder="Ø§ÙƒØªØ¨ ØªÙØ§ØµÙŠÙ„ Ù…Ø§ Ø³ÙŠØªÙ… ØªÙ†ÙÙŠØ°Ù‡ ÙÙŠ Ù‡Ø°Ø§ Ø§Ù„Ø´Ù‡Ø±/Ø§Ù„Ø¯ÙØ¹Ø©..."></textarea>
        `;

        const amountInput = row.querySelector('input[type="number"]');
        const ta = row.querySelector("textarea");
        if (ta) ta.value = String(prev.description || "").trim();

        const item = {
          no: idx,
          title,
          amount: Number(amount.toFixed(2)),
          description: ta ? ta.value : "",
        };
        items.push(item);

        function syncItem() {
          item.amount = amountInput ? toMoney(amountInput.value || 0) : item.amount;
          item.description = ta ? (ta.value || "").trim() : item.description;
          updateSummary();
        }

        if (amountInput) {
          amountInput.addEventListener("input", syncItem);
          amountInput.addEventListener("change", syncItem);
        }
        if (ta) {
          ta.addEventListener("input", syncItem);
        }

        bd.appendChild(row);
      }

      updateSummary();
    }

    root.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-bina-proposal-open]");
      if (!btn) return;
      const card = e.target.closest("[data-bina-proposal-card]");
      if (!card) return;
      const form = qs(card, "[data-bina-proposal-form]");
      if (form) {
        form.classList.remove("hidden");
        renderBreakdown(form);
      }
    });

    root.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-bina-proposal-cancel]");
      if (!btn) return;
      const card = e.target.closest("[data-bina-proposal-card]");
      if (!card) return;
      const form = qs(card, "[data-bina-proposal-form]");
      const msg = qs(card, "[data-bina-proposal-msg]");
      if (msg) msg.textContent = "";
      if (form) form.classList.add("hidden");
    });

    root.addEventListener("change", (e) => {
      const sel = e.target.closest('select[name="plan_key"]');
      if (!sel) return;
      const form = e.target.closest("[data-bina-proposal-form]");
      if (form) renderBreakdown(form);
    });

    root.addEventListener("input", (e) => {
      const inp = e.target.closest('input[name="price_total"]');
      if (!inp) return;
      const form = e.target.closest("[data-bina-proposal-form]");
      if (form) renderBreakdown(form);
    });

    root.addEventListener("submit", async (e) => {
      const form = e.target.closest("[data-bina-proposal-form]");
      if (!form) return;
      e.preventDefault();

      const card = e.target.closest("[data-bina-proposal-card]");
      if (!card) return;
      const projectId = card.getAttribute("data-project-id");

      const msg = qs(card, "[data-bina-proposal-msg]");
      const submit = qs(form, "[data-bina-proposal-submit]");
      if (msg) msg.textContent = "Ø¬Ø§Ø±Ù Ø§Ù„Ø¥Ø±Ø³Ø§Ù„...";
      if (submit) submit.disabled = true;

      renderBreakdown(form);

      const fd = new FormData(form);
      const priceTotal = (fd.get("price_total") || "").toString().trim();
      const durationDays = (fd.get("duration_days") || "").toString().trim();
      const message = (fd.get("message") || "").toString().trim();
      const planKey = (fd.get("plan_key") || "pay_at_completion").toString().trim();
      const planMeta = (fd.get("plan_meta") || "").toString().trim();

      try {
        if (planKey !== "pay_at_completion" && !planMeta) {
          throw new Error("Ø£ÙƒÙ…Ù„ ØªÙØ§ØµÙŠÙ„ Ø§Ù„Ø¯ÙØ¹Ø§Øª Ø£ÙˆÙ„Ø§Ù‹.");
        }

        if (planKey !== "pay_at_completion") {
          const parsed = safeJsonParse(planMeta);
          const items = Array.isArray(parsed?.items) ? parsed.items : [];
          const sum = Math.round(items.reduce((acc, item) => acc + toMoney(item?.amount || 0), 0) * 100) / 100;
          const total = toMoney(priceTotal);
          const hasEmptyDescription = items.some((item) => !String(item?.description || "").trim());

          if (!items.length || Math.abs(sum - total) > 0.01) {
            throw new Error("Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ø¯ÙØ¹Ø§Øª ÙŠØ¬Ø¨ Ø£Ù† ÙŠØ³Ø§ÙˆÙŠ Ø§Ù„Ù…Ø¨Ù„Øº Ø§Ù„Ø¥Ø¬Ù…Ø§Ù„ÙŠ.");
          }
          if (hasEmptyDescription) {
            throw new Error("Ø§ÙƒØªØ¨ ÙˆØµÙÙ‹Ø§ Ù„ÙƒÙ„ Ø¯ÙØ¹Ø©.");
          }
        }

        const json = await post(ajaxUrl, {
          action: "bina_submit_proposal",
          nonce,
          project_id: projectId || "",
          price_total: priceTotal,
          duration_days: durationDays,
          message,
          plan_key: planKey,
          plan_meta: planMeta,
        });

        if (!json.success) {
          throw new Error((json.data && json.data.message) || "ØªØ¹Ø°Ø± Ø¥Ø±Ø³Ø§Ù„ Ø§Ù„Ø¹Ø±Ø¶.");
        }

        if (msg) msg.textContent = (json.data && json.data.message) || "ØªÙ… Ø¥Ø±Ø³Ø§Ù„ Ø§Ù„Ø¹Ø±Ø¶.";
        form.reset();
        form.classList.add("hidden");
        const openBtn = card.querySelector("[data-bina-proposal-open]");
        const sentEl = card.querySelector("[data-bina-proposal-sent]");
        if (openBtn) openBtn.classList.add("hidden");
        if (sentEl) sentEl.classList.remove("hidden");
        window.setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        if (msg) msg.textContent = err && err.message ? err.message : "ØªØ¹Ø°Ø± Ø¥Ø±Ø³Ø§Ù„ Ø§Ù„Ø¹Ø±Ø¶.";
      } finally {
        if (submit) submit.disabled = false;
      }
    });

    root.querySelectorAll("[data-bina-proposal-form]").forEach((f) => {
      if (!f.classList.contains("hidden")) renderBreakdown(f);
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-bina-proposals]").forEach(init);
  });
})();

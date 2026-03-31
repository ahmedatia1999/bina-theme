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

  function init(root) {
    const ajaxUrl = root.getAttribute("data-ajaxurl") || "";
    const nonce = root.getAttribute("data-nonce") || "";
    if (!ajaxUrl || !nonce) return;

    function splitEqual(total, n) {
      const t = Number(total || 0);
      const nn = Number(n || 0);
      if (!isFinite(t) || !isFinite(nn) || nn < 1) return [];
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
      const total = Number(String(priceEl.value || "").replace(",", "."));
      const existing = safeJsonParse(planMetaInput.value);

      // Duration: for installment plans it's fixed (4/11 months), so hide the input and set a stable value.
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

      // pay_at_completion: hide the breakdown UI (no schedule needed).
      if (planKey === "pay_at_completion") {
        bdRoot.classList.add("hidden");
        planMetaInput.value = "";
        return;
      }
      bdRoot.classList.remove("hidden");

      const n = planKey === "four_installments_equal" ? 4 : 11;
      const amounts = splitEqual(isFinite(total) ? total : 0, n);

      const items = [];
      const prevItems = Array.isArray(existing?.items) ? existing.items : [];

      bd.innerHTML = "";
      for (let i = 0; i < n; i++) {
        const idx = i + 1;
        const amount = amounts[i] ?? 0;
        const prev = prevItems[i] && typeof prevItems[i] === "object" ? prevItems[i] : {};
        const title =
          planKey === "eleven_months" ? `شهر ${idx}` : `دفعة ${idx}`;

        const row = document.createElement("div");
        row.className = "rounded-md border border-border/60 bg-background p-2 space-y-2";
        row.innerHTML = `
          <div class="flex items-center justify-between gap-2">
            <div class="text-xs font-medium">${title}</div>
            <div class="text-xs text-muted-foreground tabular-nums">${amount.toFixed(2)} ر.س</div>
          </div>
          <textarea rows="2" class="w-full rounded-md border border-input bg-transparent px-2 py-1.5 text-xs" placeholder="اكتب تفاصيل ما سيتم تنفيذه في هذا الشهر/الدفعة..."></textarea>
        `;
        const ta = row.querySelector("textarea");
        if (ta) ta.value = String(prev.description || "").trim();
        bd.appendChild(row);

        const item = { no: idx, title, amount: Number(amount.toFixed(2)), description: ta ? ta.value : "" };
        items.push(item);

        // Keep meta in sync as user types.
        if (ta) {
          ta.addEventListener("input", () => {
            // update this item description
            item.description = ta.value || "";
            planMetaInput.value = JSON.stringify({ plan_key: planKey, total: isFinite(total) ? Number(total.toFixed(2)) : 0, items });
          });
        }
      }

      const sum = Math.round(items.reduce((a, it) => a + (Number(it.amount) || 0), 0) * 100) / 100;
      if (hint) {
        const t = isFinite(total) ? Math.round(total * 100) / 100 : 0;
        hint.textContent = `الإجمالي: ${sum.toFixed(2)} ر.س (المطلوب: ${t.toFixed(2)} ر.س)`;
      }
      planMetaInput.value = JSON.stringify({ plan_key: planKey, total: isFinite(total) ? Number(total.toFixed(2)) : 0, items });
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

    // Update breakdown when plan or total changes.
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
      if (msg) msg.textContent = "جارٍ الإرسال...";
      if (submit) submit.disabled = true;

      // Ensure computed schedule + duration are up-to-date before sending.
      renderBreakdown(form);

      const fd = new FormData(form);
      const priceTotal = (fd.get("price_total") || "").toString().trim();
      const durationDays = (fd.get("duration_days") || "").toString().trim();
      const message = (fd.get("message") || "").toString().trim();
      const planKey = (fd.get("plan_key") || "pay_at_completion").toString().trim();
      const planMeta = (fd.get("plan_meta") || "").toString().trim();

      try {
        // Basic client validation for installment plans (must have meta JSON).
        if (planKey !== "pay_at_completion" && !planMeta) {
          throw new Error("أكمل تفاصيل الدفعات أولاً.");
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
          throw new Error((json.data && json.data.message) || "تعذر إرسال العرض.");
        }

        if (msg) msg.textContent = (json.data && json.data.message) || "تم إرسال العرض.";
        form.reset();
        form.classList.add("hidden");
        const openBtn = card.querySelector("[data-bina-proposal-open]");
        const sentEl = card.querySelector("[data-bina-proposal-sent]");
        if (openBtn) openBtn.classList.add("hidden");
        if (sentEl) sentEl.classList.remove("hidden");
        // Force reload so server-rendered state always matches DB.
        window.setTimeout(() => window.location.reload(), 700);
      } catch (err) {
        if (msg) msg.textContent = err && err.message ? err.message : "تعذر إرسال العرض.";
      } finally {
        if (submit) submit.disabled = false;
      }
    });

    // Render for any pre-opened forms (edge cases).
    root.querySelectorAll("[data-bina-proposal-form]").forEach((f) => {
      if (!f.classList.contains("hidden")) renderBreakdown(f);
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-bina-proposals]").forEach(init);
  });
})();


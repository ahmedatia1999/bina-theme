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

  function getInstallmentsCount(planKey) {
    if (planKey === "four_installments_equal") return 4;
    if (planKey === "eleven_installments_equal" || planKey === "eleven_months") return 11;
    return 0;
  }

  function init(root) {
    const ajaxUrl = root.getAttribute("data-ajaxurl") || "";
    const nonce = root.getAttribute("data-nonce") || "";
    if (!ajaxUrl || !nonce) return;

    function openProposalForm(form) {
      if (!form) return;
      form.classList.remove("hidden");
      form.style.display = "block";
      form.style.overflow = "hidden";
      form.style.willChange = "max-height, opacity, transform";
      form.style.transition =
        "max-height .34s cubic-bezier(.22,.61,.36,1), opacity .28s ease, transform .34s cubic-bezier(.22,.61,.36,1)";
      // start state
      form.style.maxHeight = "0px";
      form.style.opacity = "0";
      form.style.transform = "translateY(-6px)";
      // next frame -> animate to content height
      requestAnimationFrame(() => {
        const target = Math.max(form.scrollHeight + 24, 240);
        form.style.maxHeight = target + "px";
        form.style.opacity = "1";
        form.style.transform = "translateY(0)";
      });
    }

    function closeProposalForm(form) {
      if (!form) return;
      const finish = () => {
        form.classList.add("hidden");
        form.style.removeProperty("max-height");
        form.style.removeProperty("opacity");
        form.style.removeProperty("transform");
        form.style.removeProperty("transition");
        form.style.removeProperty("overflow");
        form.style.removeProperty("will-change");
      };
      form.style.overflow = "hidden";
      form.style.willChange = "max-height, opacity, transform";
      form.style.transition =
        "max-height .34s cubic-bezier(.22,.61,.36,1), opacity .28s ease, transform .34s cubic-bezier(.22,.61,.36,1)";
      form.style.maxHeight = Math.max(form.scrollHeight + 24, 240) + "px";
      form.style.opacity = "1";
      form.style.transform = "translateY(0)";
      requestAnimationFrame(() => {
        form.style.maxHeight = "0px";
        form.style.opacity = "0";
        form.style.transform = "translateY(-6px)";
      });
      window.setTimeout(finish, 340);
    }

    function renderBreakdown(form) {
      const planSel = form.querySelector('select[name="plan_key"]');
      const priceEl = form.querySelector('input[name="price_total"]');
      const bdRoot = form.querySelector("[data-bina-plan-breakdown-root]");
      const bd = form.querySelector("[data-bina-plan-breakdown]");
      const hint = form.querySelector("[data-bina-plan-total-hint]");
      const planMetaInput = form.querySelector('input[name="plan_meta"]');
      if (!bdRoot || !bd || !planSel || !priceEl || !planMetaInput) return;

      const planKey = String(planSel.value || "pay_at_completion");
      const total = toMoney(priceEl.value || "");
      const existing = safeJsonParse(planMetaInput.value);
      const n = getInstallmentsCount(planKey);

      if (n < 1) {
        bdRoot.classList.add("hidden");
        planMetaInput.value = "";
        if (hint) {
          hint.textContent = "";
          hint.classList.remove("text-destructive");
        }
        return;
      }

      bdRoot.classList.remove("hidden");

      const defaultAmounts = splitEqual(total, n);
      const prevItems = Array.isArray(existing?.items) ? existing.items : [];
      const items = [];

      function updateSummary() {
        const sum = Math.round(items.reduce((a, it) => a + (Number(it.amount) || 0), 0) * 100) / 100;
        if (hint) {
          hint.textContent = `الإجمالي: ${sum.toFixed(2)} ر.س (المطلوب: ${total.toFixed(2)} ر.س)`;
          hint.classList.toggle("text-destructive", Math.abs(sum - total) > 0.01);
        }
        planMetaInput.value = serializePlanMeta(planKey, total, items);
      }

      bd.innerHTML = "";

      for (let i = 0; i < n; i++) {
        const idx = i + 1;
        const prev = prevItems[i] && typeof prevItems[i] === "object" ? prevItems[i] : {};
        const title = `دفعة ${idx}`;
        const amount =
          prev.amount !== undefined && prev.amount !== null && prev.amount !== ""
            ? toMoney(prev.amount)
            : defaultAmounts[i] ?? 0;

        const row = document.createElement("div");
        row.className = "rounded-md border border-border/60 bg-background p-2 space-y-2";
        row.innerHTML = `
          <div class="flex items-center justify-between gap-3">
            <div class="text-xs font-medium">${title}</div>
            <input type="number" min="0" step="0.01" class="w-28 rounded-md border border-input bg-transparent px-2 py-1.5 text-xs text-start" placeholder="0.00" value="${amount.toFixed(2)}" />
          </div>
          <textarea rows="2" class="w-full rounded-md border border-input bg-transparent px-2 py-1.5 text-xs" placeholder="اكتب تفاصيل ما سيتم تنفيذه في هذه الدفعة..."></textarea>
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
        if (ta) ta.addEventListener("input", syncItem);

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
        openProposalForm(form);
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
      if (form && form.dataset.submitting !== "1") closeProposalForm(form);
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
      if (form.dataset.submitting === "1" || form.dataset.submitted === "1") return;

      const projectId = card.getAttribute("data-project-id");
      const msg = qs(card, "[data-bina-proposal-msg]");
      const submit = qs(form, "[data-bina-proposal-submit]");
      form.dataset.submitting = "1";
      if (msg) msg.textContent = "جارٍ إرسال العرض...";
      if (submit) submit.disabled = true;

      renderBreakdown(form);

      const fd = new FormData(form);
      const priceTotal = (fd.get("price_total") || "").toString().trim();
      const durationDays = (fd.get("duration_days") || "").toString().trim();
      const message = (fd.get("message") || "").toString().trim();
      const planKey = (fd.get("plan_key") || "pay_at_completion").toString().trim();
      const planMeta = (fd.get("plan_meta") || "").toString().trim();

      try {
        if (getInstallmentsCount(planKey) > 0 && !planMeta) {
          throw new Error("أكمل تفاصيل الدفعات أولاً.");
        }

        if (getInstallmentsCount(planKey) > 0) {
          const parsed = safeJsonParse(planMeta);
          const items = Array.isArray(parsed?.items) ? parsed.items : [];
          const sum = Math.round(items.reduce((acc, item) => acc + toMoney(item?.amount || 0), 0) * 100) / 100;
          const total = toMoney(priceTotal);
          const hasEmptyDescription = items.some((item) => !String(item?.description || "").trim());

          if (!items.length || Math.abs(sum - total) > 0.01) {
            throw new Error("إجمالي الدفعات يجب أن يساوي السعر الإجمالي.");
          }
          if (hasEmptyDescription) {
            throw new Error("اكتب وصفًا لكل دفعة.");
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
          throw new Error((json.data && json.data.message) || "تعذر إرسال العرض.");
        }

        form.dataset.submitted = "1";
        if (msg) msg.textContent = "تم إرسال العرض بنجاح، جارٍ تحديث الحالة...";
        if (submit) {
          submit.disabled = true;
          submit.textContent = "جارٍ التحديث...";
        }

        // Keep loading until server-confirmed state is reflected after refresh.
        const u = new URL(window.location.href);
        u.searchParams.set("_", String(Date.now()));
        window.location.replace(u.toString());
        return;
      } catch (err) {
        if (msg) msg.textContent = err && err.message ? err.message : "تعذر إرسال العرض.";
      } finally {
        if (form.dataset.submitted !== "1") {
          form.dataset.submitting = "0";
          if (submit) submit.disabled = false;
        }
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

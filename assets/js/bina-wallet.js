(function () {
	'use strict';

	const roots = Array.from(document.querySelectorAll('[data-bina-wallet]'));
	if (!roots.length) return;

	function post(params) {
		const ajaxurl = params.__ajaxurl || '';
		delete params.__ajaxurl;
		const p = new URLSearchParams();
		Object.keys(params).forEach((k) => p.append(k, params[k]));
		return fetch(ajaxurl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: p.toString()
		}).then((r) => r.json());
	}

	roots.forEach((root) => {
		const ajaxurl = root.dataset.ajaxurl || '';
		const nonce = root.dataset.nonce || '';

		// Save payout methods.
		root.querySelectorAll('[data-bina-payout-form]').forEach((form) => {
			const msg = form.querySelector('[data-bina-payout-msg]') || root.querySelector('[data-bina-payout-msg]');
			form.addEventListener('submit', (e) => {
				e.preventDefault();
				const fd = new FormData(form);
				const kind = (form.dataset.binaPayoutKind || '').toLowerCase();
				const data = {
					__ajaxurl: ajaxurl,
					action: 'bina_save_payout_methods',
					nonce
				};
				fd.forEach((v, k) => (data[k] = String(v || '')));

				// Client-side validation to avoid "saved" with empty payload.
				if (kind === 'bank') {
					const holder = (data.bank_holder || '').trim();
					const name = (data.bank_name || '').trim();
					const iban = (data.bank_iban || '').trim();
					if (!holder || !name || !iban) {
						if (msg) msg.textContent = 'أكمل بيانات البنك أولاً';
						return;
					}
				}
				if (kind === 'stc') {
					const phone = (data.stc_phone || '').trim();
					if (!phone) {
						if (msg) msg.textContent = 'أدخل رقم STC Pay أولاً';
						return;
					}
				}

				if (msg) msg.textContent = '...';
				post(data).then((res) => {
					const ok = !!(res && res.success);
					if (msg) {
						msg.textContent = ok ? 'تم الحفظ' : (res?.data?.message || 'تعذر الحفظ');
					}
					if (ok && res?.data?.saved) {
						const saved = res.data.saved || {};
						if (kind === 'bank') {
							const v1 = String(saved.bank_holder || '');
							const v2 = String(saved.bank_name || '');
							const v3 = String(saved.bank_iban || '');
							const i1 = form.querySelector('input[name="bank_holder"]');
							const i2 = form.querySelector('input[name="bank_name"]');
							const i3 = form.querySelector('input[name="bank_iban"]');
							if (i1) i1.value = v1;
							if (i2) i2.value = v2;
							if (i3) i3.value = v3;
						}
						if (kind === 'stc') {
							const v = String(saved.stc_phone || '');
							const i = form.querySelector('input[name="stc_phone"]');
							if (i) i.value = v;
						}
					}
					// Ensure the refreshed HTML reflects saved user_meta.
					if (ok) {
						setTimeout(() => {
							try {
								const u = new URL(window.location.href);
								u.searchParams.set('_', String(Date.now()));
								window.location.href = u.toString();
							} catch (e) {}
						}, 700);
					}
				});
			});
		});

		// Withdraw request form (payments page).
		const wForm = root.querySelector('[data-bina-withdraw-form]');
		if (wForm) {
			const msg = root.querySelector('[data-bina-withdraw-msg]');
			const submit = wForm.querySelector('button[type="submit"], [data-bina-withdraw-submit]');
			wForm.addEventListener('submit', (e) => {
				e.preventDefault();
				if (wForm.dataset.loading === '1') return;
				wForm.dataset.loading = '1';
				const fd = new FormData(wForm);
				const amount = String(fd.get('amount') || '');
				const method = String(fd.get('method') || 'bank');
				const originalText = submit ? submit.textContent : '';

				if (submit) {
					submit.disabled = true;
					submit.classList.add('opacity-60', 'cursor-not-allowed');
					submit.textContent = 'جارٍ الإرسال...';
				}

				if (msg) msg.textContent = '...';
				post({
					__ajaxurl: ajaxurl,
					action: 'bina_create_withdraw_request',
					nonce,
					amount,
					method
				}).then((res) => {
					const ok = !!(res && res.success);
					if (msg) {
						msg.textContent = ok ? (res?.data?.message || 'تم إرسال الطلب') : (res?.data?.message || 'تعذر إرسال الطلب');
					}
					if (ok) {
						// Refresh balances/history from server-confirmed state.
						setTimeout(() => {
							try {
								const u = new URL(window.location.href);
								u.searchParams.set('_', String(Date.now()));
								window.location.replace(u.toString());
							} catch (err) {
								window.location.reload();
							}
						}, 500);
						return;
					}
					wForm.dataset.loading = '0';
					if (submit) {
						submit.disabled = false;
						submit.classList.remove('opacity-60', 'cursor-not-allowed');
						submit.textContent = originalText || 'طلب سحب';
					}
				}).catch(() => {
					if (msg) msg.textContent = 'تعذر إرسال الطلب';
					wForm.dataset.loading = '0';
					if (submit) {
						submit.disabled = false;
						submit.classList.remove('opacity-60', 'cursor-not-allowed');
						submit.textContent = originalText || 'طلب سحب';
					}
				});
			});
		}
	});
})();


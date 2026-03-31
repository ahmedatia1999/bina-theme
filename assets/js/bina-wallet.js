(function () {
	'use strict';

	const root = document.querySelector('[data-bina-wallet]');
	if (!root) return;

	const ajaxurl = root.dataset.ajaxurl || '';
	const nonce = root.dataset.nonce || '';

	function post(params) {
		const p = new URLSearchParams();
		Object.keys(params).forEach((k) => p.append(k, params[k]));
		return fetch(ajaxurl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: p.toString()
		}).then((r) => r.json());
	}

	// Save payout methods (can exist twice on the page).
	root.querySelectorAll('[data-bina-payout-form]').forEach((form) => {
		const msg = form.querySelector('[data-bina-payout-msg]') || root.querySelector('[data-bina-payout-msg]');
		form.addEventListener('submit', (e) => {
			e.preventDefault();
			const fd = new FormData(form);
			const kind = (form.dataset.binaPayoutKind || '').toLowerCase();
			const data = {
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
				if (msg) {
					msg.textContent = res && res.success ? 'تم الحفظ' : (res?.data?.message || 'تعذر الحفظ');
				}
				// Ensure the refreshed HTML reflects saved user_meta (avoid stale render/cache).
				if (res && res.success) {
					setTimeout(() => {
						try {
							window.location.reload();
						} catch (e) {}
					}, 250);
				}
			});
		});
	});

	// Withdraw request form (payments page).
	const wForm = root.querySelector('[data-bina-withdraw-form]');
	if (wForm) {
		const msg = root.querySelector('[data-bina-withdraw-msg]');
		wForm.addEventListener('submit', (e) => {
			e.preventDefault();
			const fd = new FormData(wForm);
			const amount = String(fd.get('amount') || '');
			const method = String(fd.get('method') || 'bank');

			if (msg) msg.textContent = '...';
			post({
				action: 'bina_create_withdraw_request',
				nonce,
				amount,
				method
			}).then((res) => {
				if (msg) {
					msg.textContent = res && res.success ? 'تم إرسال الطلب' : (res?.data?.message || 'تعذر إرسال الطلب');
				}
			});
		});
	}
})();


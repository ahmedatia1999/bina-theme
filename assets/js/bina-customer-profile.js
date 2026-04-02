(function () {
	'use strict';

	const root = document.querySelector('[data-bina-customer-profile]');
	if (!root) return;

	const ajaxurl = root.dataset.ajaxurl || '';
	const nonce = root.dataset.nonce || '';
	const form = root.querySelector('form[data-bina-customer-profile-form]');
	const msg = root.querySelector('[data-bina-customer-profile-msg]');
	if (!form) return;

	function post(params) {
		const p = new URLSearchParams();
		Object.keys(params).forEach((k) => p.append(k, params[k]));
		return fetch(ajaxurl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: p.toString()
		}).then((r) => r.json());
	}

	form.addEventListener('submit', (e) => {
		e.preventDefault();
		const fd = new FormData(form);
		const display_name = String(fd.get('display_name') || '').trim();
		const phone = String(fd.get('phone') || '').trim();

		if (!display_name) {
			if (msg) msg.textContent = 'اكتب الاسم';
			return;
		}
		if (phone) {
			const digits = phone.replace(/\D+/g, '');
			if (digits.length < 9) {
				if (msg) msg.textContent = 'رقم الجوال غير صحيح';
				return;
			}
		}

		if (msg) msg.textContent = '...';
		post({
			action: 'bina_save_customer_profile',
			nonce,
			display_name,
			phone
		}).then((res) => {
			const ok = !!(res && res.success);
			if (msg) msg.textContent = ok ? 'تم الحفظ' : (res?.data?.message || 'تعذر الحفظ');
			if (!ok || !res?.data?.saved) return;

			const saved = res.data.saved || {};
			const nameInput = form.querySelector('input[name="display_name"]');
			const phoneInput = form.querySelector('input[name="phone"]');
			if (nameInput) nameInput.value = String(saved.display_name || '');
			if (phoneInput) phoneInput.value = String(saved.phone || '');

			window.setTimeout(() => {
				try {
					const u = new URL(window.location.href);
					u.searchParams.set('_', String(Date.now()));
					window.location.href = u.toString();
				} catch (e2) {
					window.location.reload();
				}
			}, 700);
		});
	});
})();

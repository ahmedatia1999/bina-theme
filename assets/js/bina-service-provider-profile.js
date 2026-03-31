(function () {
	'use strict';

	const root = document.querySelector('[data-bina-service-provider-profile]');
	if (!root) return;

	const ajaxurl = root.dataset.ajaxurl || '';
	const nonce = root.dataset.nonce || '';
	const form = root.querySelector('form[data-bina-service-provider-profile-form]');
	const msg = root.querySelector('[data-bina-service-provider-profile-msg]');
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
		const city = String(fd.get('city') || '').trim();

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
			action: 'bina_save_service_provider_profile',
			nonce,
			display_name,
			phone,
			city
		}).then((res) => {
			const ok = !!(res && res.success);
			if (msg) msg.textContent = ok ? 'تم الحفظ' : (res?.data?.message || 'تعذر الحفظ');
			if (ok) {
				window.setTimeout(() => window.location.reload(), 700);
			}
		});
	});
})();


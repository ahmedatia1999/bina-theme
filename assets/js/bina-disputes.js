(function () {
	'use strict';

	document.querySelectorAll('[data-bina-disputes]').forEach((root) => {
		const ajaxurl = root.dataset.ajaxurl || '';
		const nonce = root.dataset.nonce || '';
		const form = root.querySelector('form[data-bina-dispute-form]');
		const msg = root.querySelector('[data-bina-dispute-msg]');
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
			const project_id = String(fd.get('project_id') || '').trim();
			const message = String(fd.get('message') || '').trim();
			if (!project_id) {
				if (msg) msg.textContent = 'اختر مشروع';
				return;
			}
			if (!message) {
				if (msg) msg.textContent = 'اكتب الشكوى';
				return;
			}
			if (msg) msg.textContent = '...';
			post({
				action: 'bina_create_dispute',
				nonce,
				project_id,
				message
			}).then((res) => {
				const ok = !!(res && res.success);
				if (msg) msg.textContent = ok ? 'تم إرسال الشكوى' : (res?.data?.message || 'تعذر الإرسال');
				if (ok) {
					form.reset();
					window.setTimeout(() => window.location.reload(), 700);
				}
			});
		});
	});
})();


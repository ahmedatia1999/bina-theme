(function () {
	'use strict';

	const root = document.querySelector('[data-bina-customer-payments]');
	if (!root) return;

	const ajaxurl = root.dataset.ajaxurl || '';
	const nonce = root.dataset.nonce || '';

	const form = root.querySelector('form[data-bina-customer-payments-form]');
	const msg = root.querySelector('[data-bina-customer-payments-msg]');
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

	function setVisibleBlocks(method) {
		root.querySelectorAll('[data-bina-method-block]').forEach((el) => {
			el.classList.add('hidden');
		});
		const active = root.querySelector(`[data-bina-method-block="${method}"]`);
		if (active) active.classList.remove('hidden');
	}

	const methodSel = form.querySelector('select[name="method"]');
	if (methodSel) {
		setVisibleBlocks(methodSel.value || '');
		methodSel.addEventListener('change', () => setVisibleBlocks(methodSel.value || ''));
	}

	form.addEventListener('submit', (e) => {
		e.preventDefault();
		const fd = new FormData(form);
		const method = String(fd.get('method') || '').trim();

		const data = { action: 'bina_save_customer_payment_method', nonce, method };
		fd.forEach((v, k) => (data[k] = String(v || '')));

		// Validate before sending.
		if (!method) {
			if (msg) msg.textContent = 'اختر طريقة دفع';
			return;
		}
		if (method === 'bank') {
			if (!String(data.bank_holder || '').trim() || !String(data.bank_name || '').trim() || !String(data.bank_iban || '').trim()) {
				if (msg) msg.textContent = 'أكمل بيانات التحويل البنكي';
				return;
			}
		}
		if (method === 'stc') {
			if (!String(data.stc_phone || '').trim()) {
				if (msg) msg.textContent = 'أدخل رقم STC Pay';
				return;
			}
		}

		if (msg) msg.textContent = '...';
		post(data).then((res) => {
			if (msg) msg.textContent = res && res.success ? 'تم الحفظ' : (res?.data?.message || 'تعذر الحفظ');
		});
	});
})();


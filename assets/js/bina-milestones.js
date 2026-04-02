(function () {
	'use strict';

	function init(root) {
		const ajaxurl = root.dataset.ajaxurl || '';
		const nonce = root.dataset.nonce || '';
		if (!ajaxurl || !nonce) return;

		function post(params) {
			const p = new URLSearchParams();
			Object.keys(params).forEach((k) => p.append(k, params[k]));
			return fetch(ajaxurl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: p.toString(),
				credentials: 'same-origin'
			}).then((r) => r.json());
		}

		root.addEventListener('click', (e) => {
			const btn = e.target.closest('[data-bina-ms-action]');
			if (!btn) return;
			if (!root.contains(btn)) return;
			e.preventDefault();

			const action = btn.getAttribute('data-bina-ms-action') || '';
			const milestoneId = btn.getAttribute('data-milestone-id') || '';
			const msgEl = btn.closest('[data-bina-ms-row]')?.querySelector('[data-bina-ms-msg]') || null;

			if (!action || !milestoneId) return;

			const confirmText = btn.getAttribute('data-confirm') || '';
			if (confirmText && !window.confirm(confirmText)) return;

			btn.disabled = true;
			if (msgEl) msgEl.textContent = '...';

			const map = {
				request: 'bina_request_milestone_funding',
				fund: 'bina_fund_milestone',
				submit: 'bina_submit_milestone',
				approve: 'bina_approve_milestone'
			};
			const wpAction = map[action] || '';
			if (!wpAction) {
				btn.disabled = false;
				if (msgEl) msgEl.textContent = '';
				return;
			}

			post({ action: wpAction, nonce, milestone_id: milestoneId, return_url: window.location.href }).then((res) => {
				if (res && res.success) {
					const redirectUrl = res?.data?.redirect_url || '';
					if (redirectUrl) {
						window.location.href = redirectUrl;
						return;
					}
					if (msgEl) msgEl.textContent = 'تم';
					setTimeout(() => window.location.reload(), 250);
				} else {
					if (msgEl) msgEl.textContent = res?.data?.message || 'تعذر التنفيذ';
					btn.disabled = false;
				}
			});
		});
	}

	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('[data-bina-milestones]').forEach(init);
	});
})();


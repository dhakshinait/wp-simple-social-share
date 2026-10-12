/**
 * Simple Social Share — copy link handler.
 */
(function () {
	'use strict';

	function copiedLabel(btn) {
		if (btn.getAttribute('data-wpsss-copied-label')) {
			return btn.getAttribute('data-wpsss-copied-label');
		}
		if (typeof wpsssPublic !== 'undefined' && wpsssPublic.copiedLabel) {
			return wpsssPublic.copiedLabel;
		}
		return 'Copied!';
	}

	function flashTitle(btn) {
		var original = btn.getAttribute('title') || '';
		var label = copiedLabel(btn);
		btn.setAttribute('title', label);
		btn.setAttribute('aria-label', label);
		window.setTimeout(function () {
			btn.setAttribute('title', original);
			btn.setAttribute('aria-label', original);
		}, 1500);
	}

	/**
	 * Only allow http(s) URLs to be copied.
	 *
	 * @param {string} value
	 * @return {string}
	 */
	function sanitizeHttpUrl(value) {
		if (!value || typeof value !== 'string') {
			return '';
		}

		var trimmed = value.trim();
		try {
			var parsed = new URL(trimmed, window.location.href);
			if (parsed.protocol !== 'http:' && parsed.protocol !== 'https:') {
				return '';
			}
			return parsed.href;
		} catch (err) {
			return '';
		}
	}

	function copyText(text) {
		if (navigator.clipboard && navigator.clipboard.writeText) {
			return navigator.clipboard.writeText(text);
		}

		return new Promise(function (resolve, reject) {
			var input = document.createElement('textarea');
			input.value = text;
			input.setAttribute('readonly', '');
			input.style.position = 'absolute';
			input.style.left = '-9999px';
			document.body.appendChild(input);
			input.select();
			try {
				document.execCommand('copy');
				resolve();
			} catch (err) {
				reject(err);
			} finally {
				document.body.removeChild(input);
			}
		});
	}

	document.addEventListener('click', function (event) {
		var btn = event.target.closest('a.wpsss-copy');
		if (!btn || !btn.closest('.wpsss-share')) {
			return;
		}

		event.preventDefault();

		var url = sanitizeHttpUrl(
			btn.getAttribute('data-wpsss-copy') || btn.getAttribute('href') || ''
		);
		if (!url) {
			return;
		}

		copyText(url).then(function () {
			flashTitle(btn);
		}).catch(function () {
			window.prompt('Copy this link:', url);
		});
	});
})();

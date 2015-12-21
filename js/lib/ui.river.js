elgg.provide('elgg.ui.river');

elgg.ui.river.init = function() {
	$(document).on('change', '#elgg-river-selector', function() {
		var url = window.location.href;
		if (window.location.search.length) {
			url = url.substring(0, url.indexOf('?'));
		}
		url += '?' + $(this).val();
		elgg.forward(url);
	});

	$(document).on('elgg_ui_toggle', function (e, data) {
		var $toggle = $(e.target);
		var $elements = data.$toggled_elements;

		if ($elements.is('.elgg-river-responses > .elgg-form-comment-save')) {
			if ($toggle.hasClass('elgg-state-active')) {
				$elements.find('.elgg-input-text').focus();
			} else {
				$toggle.blur();
			}
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.ui.river.init);
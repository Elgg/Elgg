elgg.provide('elgg.ui.river');

elgg.ui.river.init = function() {
	$('#elgg-river-selector').change(function() {
		var url = window.location.href;
		if (window.location.search.length) {
			url = url.substring(0, url.indexOf('?'));
		}
		url += '?' + $(this).val();
		elgg.forward(url);
	});
};

elgg.register_hook_handler('init', 'system', elgg.ui.river.init);

elgg.register_hook_handler('ui_toggle', 'system', function (h, t, params) {
	var $toggler = $(params.toggler);
	var $target = $(params.target_selector);
	if ($target.is('.elgg-river-responses > .elgg-form-comment-save')) {
		if ($toggler.hasClass('elgg-state-active')) {
			$target.find('.elgg-input-text').focus();
		} else {
			$toggler.blur();
		}
	}
});

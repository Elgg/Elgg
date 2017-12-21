require(['jquery', 'elgg'], function ($, elgg) {
	elgg.register_hook_handler('init', 'system', function () {
		$('.elgg-menu-item-bookmark > a').each(function () {
			this.href += '&title=' + encodeURIComponent(document.title);
		});
	});
});

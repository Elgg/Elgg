require(['jquery'], function ($) {
	$(document).on('click', '.elgg-menu-item-bookmark > a', function () {
		this.href += '&title=' + encodeURIComponent(document.title);
	});
});

//<script>

elgg.provide('elgg.bookmarks');

elgg.bookmarks.init = function() {
	$('.elgg-menu-item-bookmark > a').each(function () {
		this.href += '&title=' + encodeURIComponent(document.title);
	});
};

elgg.register_hook_handler('init', 'system', elgg.bookmarks.init);

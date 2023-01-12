define(['jquery'], function ($) {
	$(document).on('click', '.elgg-nav-button', function () {
		$('html').toggleClass('elgg-nav-collapsed');
	});
});

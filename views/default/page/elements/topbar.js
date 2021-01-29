define(['jquery'], function ($) {
	$(document).on('click', '.elgg-nav-button', function () {
		$('body').toggleClass('elgg-nav-collapsed');
	});
});

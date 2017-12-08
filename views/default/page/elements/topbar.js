define(function (require) {

	var $ = require('jquery');

	$(document).on('click', '.elgg-nav-button', function () {
		$('body').toggleClass('elgg-nav-collapsed');
	});
});

define(function(require) {

	var $ = require('jquery');

	$(document).on('click', '.elgg-menu-require .elgg-menu-item-require > a', function(e) {
		e.preventDefault();
		alert('Thank you for trying me');
	});
});

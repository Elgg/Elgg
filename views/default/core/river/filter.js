/**
 * Initiates page reload when river selector value changes
 * @module core/river/filter
 */
define(function (require) {
	var $ = require('jquery');
	$(document).on('change', '#elgg-river-selector', function () {
		var url = window.location.href;
		if (window.location.search.length) {
			url = url.substring(0, url.indexOf('?'));
		}
		url += '?' + $(this).val();
		window.location.href = url;
	});
});

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

require(['elgg/hooks/register'], function(register) {
	register('init', 'system', elgg.ui.river.init);
});

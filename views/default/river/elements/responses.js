define(function(require) {

	var elgg = require('elgg');
	var $ = require('jquery');
	
	$(document).on('click', '.elgg-menu-river .elgg-menu-item-comment > a', function(e) {
		var $elem = $(this);
		var href = elgg.getSelectorFromUrlFragment($elem.attr('href'));

		var $target = $(href);
		if (!$target.length) {
			return;
		}

		e.preventDefault();

		if ($target.is('.hidden')) {
			$target.parent().removeClass('hidden');
			$target.removeClass('hidden').find('input[type="text"],textarea').first().focus();
		} else {
			if (!$target.siblings().length) {
				$target.parent().addClass('hidden');
			}
			$target.addClass('hidden');
		}
	});
});
define(function (require) {

	var $ = require('jquery');

	$(document).on('keyup keydown', '.elgg-friendspicker-filter', function () {
		var $container = $(this).closest('.elgg-input-friendspicker');
		var $items = $container.find('.elgg-item');
		var q = $(this).val();

		if (q === "") {
			$items.show();
		} else {
			$items.hide();
			$items.filter(function () {
				return $(this).text().toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}).show();
		}
	});

	$(document).on('change', '.elgg-friendspicker-toggle', function() {
		var $container = $(this).closest('.elgg-input-friendspicker');
		$container.find('.elgg-friendspicker-checkbox:visible').prop('checked', $(this).prop('checked'));
	});
});



/**
 * WARNING! This view is internal and may change at any time.
 * Plugins should not use/modify/override this view.
 */

define(function (require) {
	var $ = require('jquery');

	var $selected;

	$(document).on('click', '.elgg-api-func > h4', function (e) {
		var $func = $(this).parent();

		if ($selected) {
			$selected.removeClass('selected');

			if ($func[0] == $selected[0]) {
				$selected = null;
				return false;
			}
		}

		$selected = $func;
		$selected.addClass('selected');
		location.hash = '#' + $selected.attr('id');
	});

	var m = location.hash.match(/^#(.*)/);
	if (m) {
		$('.elgg-api-func#' + m[1]).trigger('click');
	}
});

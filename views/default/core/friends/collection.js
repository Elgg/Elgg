define(function (require) {

	var elgg = require('elgg');
	var $ = require('jquery');

	$(document).on('submit', '.elgg-form-friends-collections-edit', function (e) {
		e.preventDefault();
		var $form = $(this);
		var $collection = $form.closest('.elgg-friends-collection');
		elgg.action($form.attr('action'), {
			data: $form.serialize(),
			success: function(response) {
				if (response.status >= 0) {
					$collection.find('.elgg-friends-collection-membership').replaceWith(response.output.membership);
					$collection.find('.elgg-friends-collection-membership-count').text(response.output.count);
				}
			},
		})
	});
});


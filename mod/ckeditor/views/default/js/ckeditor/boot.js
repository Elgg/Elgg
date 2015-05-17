define(function (require) {
	var $ = require('jquery');

	return {
		addBehavior: function (context) {
			$longtexts = $('.elgg-input-longtext:not([data-cke-init])', context);
			if ($longtexts.length) {
				require(['elgg/ckeditor'], function(elggCKEditor) {
					$longtexts
						.attr('data-cke-init', true)
						.ckeditor(elggCKEditor.init, elggCKEditor.config);
				});
			}
		}
	};
});

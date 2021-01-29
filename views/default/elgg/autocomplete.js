define(['jquery', 'jquery-ui/widgets/autocomplete', 'jquery.ui.autocomplete.html'], function ($) {

	return {
		init: function () {
			$('.elgg-input-autocomplete').each(function () {
				var $this = $(this);
				if (!$this.data('autocompleteInitialized')) {
					$this.data('autocompleteInitialized', true);
					$this.autocomplete({
						source: $this.data('source'),
						minLength: 2,
						html: "html",

						// turn off experimental live help - no i18n support and a little buggy
						messages: {
							noResults: '',
							results: function() {}
						}
					});
				}
			});
		}
	};
});


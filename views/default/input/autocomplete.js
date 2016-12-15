define(['jquery', 'jquery.ui.autocomplete.html'], function ($) {

	var init = function (selector, custom_options = {}) {
		var default_options = {
			minLength: 2,
			html: 'html',

			// turn off experimental live help - no i18n support and a little buggy
			messages: {
				noResults: '',
				results: function() {}
			}
		};
		
		options = $.extend(default_options, custom_options);
		$(selector).each(function () {
			var $this = $(this);
			if ($this.data('autocompleteInitialized')) {
				// allow custom options to be set on already initialized autocompletes
				if (typeof custom_options !== undefined) {
					$this.autocomplete(custom_options);
				}
				return;
			}
			
			if ($this.data('source')) {
				options.source = $this.data('source');
			}
			
			if ($this.data('minLength')) {
				options.minLength = $this.data('minLength');
			}
						
			if ($this.data('options')) {
				$.extend(options, $this.data('options'))
			}
			
			$this.data('autocompleteInitialized', true);
			$this.autocomplete(options);
		});
	}
	
	return {
		init: init
	};
});

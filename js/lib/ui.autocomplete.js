/**
 *
 */
elgg.provide('elgg.autocomplete');

/**
 * @requires jqueryui.autocomplete
 */
elgg.autocomplete.init = function() {
	$('.elgg-input-autocomplete').autocomplete({
		source: elgg.autocomplete.url, //gets set by input/autocomplete view
		minLength: 2,
		html: "html",

		// turn off experimental live help - no i18n support and a little buggy
		messages: {
			noResults: '',
			results: function() {}
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);
define(['jquery', 'elgg', 'jquery.ui.autocomplete.html'], function ($, elgg) {
	
	var queryurl = $('.elgg-input-autocomplete').data('queryurl');

	$('.elgg-input-autocomplete').autocomplete({
		source: queryurl,
		minLength: 2,
		html: "html",

		// turn off experimental live help - no i18n support and a little buggy
		messages: {
			noResults: '',
			results: function() {}
		}
	});
});
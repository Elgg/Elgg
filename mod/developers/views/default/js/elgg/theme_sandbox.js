define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	$(function() {
		$('#developers-system-message').click(function() {
			elgg.system_message('Elgg System Message');
		})

		$('#developers-error-message').click(function() {
			elgg.register_error('Elgg Error Message');
		});
		
		// widgets do not have guids so we override the edit toggle and delete button
		$('.elgg-widget-edit-button').unbind('click');
		$('.elgg-widget-edit-button').click(function() {
			$(this).closest('.elgg-module-widget').find('.elgg-widget-edit').slideToggle('medium');
			return false;
		});

		$('.elgg-widget-delete-button').click(function() {
			$(this).closest('.elgg-module-widget').remove();
			return false;
		});
	});
});
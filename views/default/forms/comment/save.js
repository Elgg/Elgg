/**
 * Autofocuses first text input in a comment form when toggled
 * @module forms/comment/save
 */
define(function (require) {
	var $ = require('jquery');
	$(document).on('elgg_ui_toggle', function (e, data) {
		var $toggle = $(e.target);
		var $elements = data.$toggled_elements;

		if ($elements.is('.elgg-river-responses > .elgg-form-comment-save')) {
			if ($toggle.hasClass('elgg-state-active')) {
				$elements.find('.elgg-input-text').focus();
			} else {
				$toggle.blur();
			}
		}
	});
});
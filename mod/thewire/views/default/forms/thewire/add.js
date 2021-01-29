define(['jquery'], function ($) {

	/**
	 * Update the number of characters left with every keystroke
	 *
	 * @param {Object}  textarea
	 * @param {Object}  status
	 * @param {integer} limit
	 * @return void
	 */
	var textCounter = function(textarea, status, limit) {

		var remaining_chars = limit - $(textarea).val().length;
		status.html(remaining_chars);
		var $submit = $(textarea).closest('form').find('#thewire-submit-button');

		if (remaining_chars < 0) {
			status.parent().addClass("thewire-characters-remaining-warning");
			$submit.prop('disabled', true);
			$submit.addClass('elgg-state-disabled');
		} else {
			status.parent().removeClass("thewire-characters-remaining-warning");
			$submit.prop('disabled', false);
			$submit.removeClass('elgg-state-disabled');
		}
	};

	var checkMaxLength = function() {
		var maxLength = $(this).data('max-length');
		if (maxLength) {
			textCounter(this, $(this).closest('form').find("#thewire-characters-remaining span"), maxLength);
		}
	};

	$(document).on('input propertychange', "#thewire-textarea", checkMaxLength);
});

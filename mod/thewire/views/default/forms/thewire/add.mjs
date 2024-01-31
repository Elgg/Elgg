import 'jquery';

/**
 * Update the number of characters left with every keystroke
 *
 * @param {Object}  textarea
 * @param {Object}  wrapper
 * @param {integer} limit
 * @return void
 */
function textCounter(textarea, wrapper, limit) {
	var remaining_chars = limit - $(textarea).val().length;
	var $container = $(wrapper).find('> div');
	var $form = $(textarea).closest('form');
	
	$container.find('> span').text(remaining_chars);
	
	var $submit = $form.find('button[type="submit"]');

	if (remaining_chars < 0) {
		$container.addClass("thewire-characters-remaining-warning");
		$submit.prop('disabled', true);
		$submit.addClass('elgg-state-disabled');
	} else {
		$container.removeClass("thewire-characters-remaining-warning");
		$submit.prop('disabled', false);
		$submit.removeClass('elgg-state-disabled');
	}
};

function checkMaxLength() {
	var maxLength = $(this).data('max-length');
	if (maxLength) {
		textCounter(this, $(this).closest('form').find(".thewire-characters-wrapper"), maxLength);
	}
};

$(document).on('input propertychange', ".thewire-textarea", checkMaxLength);

define(['jquery'], function($) {

	var checkbox_selector = 'input[type="checkbox"][name="autogen_password"]';

	/**
	 * Toggle password input visibility based on the value
	 * of the autogenerate password checkbox
	 *
	 * @param {jQuery} $checkbox Checkbox input
	 * @returns {void}
	 */
	function togglePasswordInput($checkbox) {
		var $form = $checkbox.closest('.elgg-form-useradd');
		if (!$form.length) {
			return;
		}
		if ($checkbox.is(':checked')) {
			$('[name="password"],[name="password2"]', $form).each(function() {
				$(this).prop('required', false);
				$(this).closest('.elgg-field').addClass('hidden');
			});
		} else {
			$('[name="password"],[name="password2"]', $form).each(function() {
				$(this).prop('required', true);
				$(this).closest('.elgg-field').removeClass('hidden');
			});
		}
	}

	$(document).on('change', checkbox_selector, function() {
		togglePasswordInput($(this));
	});

	togglePasswordInput($(checkbox_selector));
});

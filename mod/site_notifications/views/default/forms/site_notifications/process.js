/**
 * Site notifications JavaScript
 */
define(['jquery'], function ($) {

	/**
	 * Toggle the checkboxes in the site notification listing
	 *
	 * @return void
	 */
	var click_all_checkboxes = function() {
		var $checkboxes = $('.site-notifications-container input[type="checkbox"]');
		if ($checkboxes.eq(0).is(':checked')) {
			// uncheck all
			$checkboxes.filter(':checked').click();
		} else {
			// check all
			$checkboxes.not(':checked').click();
		}
	};
	
	/**
	 * Change the state of the submit buttons based on selected items
	 *
	 * @return void
	 */
	var change_submit_button_state = function() {
		var $form = $('.elgg-form-site-notifications-process');
		
		if ($form.find('input[type="checkbox"]:checked').length) {
			// enable submit buttons
			$form.find('button[type="submit"]').prop('disabled', false);
		} else {
			// nothing selected so disable submit buttons
			$form.find('button[type="submit"]').prop('disabled', true);
		}
	};
	
	$(document).on('click', '#site-notifications-toggle', click_all_checkboxes);
	$(document).on('change', '.elgg-form-site-notifications-process input[type="checkbox"]', change_submit_button_state);
});

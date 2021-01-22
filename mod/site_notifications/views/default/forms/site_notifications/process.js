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
		$('.site-notifications-container input[type=checkbox]').click();
	};
	
	$(document).on('click', '#site-notifications-toggle', click_all_checkboxes);
});

define(['jquery', 'elgg/spinner'], function($, spinner) {
	
	var get_checkboxes = function () {
		return $('#admin-users-unvalidated-bulk .elgg-input-checkbox[name="user_guids[]"]');
	};
	
	var bulk_select_toggle = function() {
		
		var $checkboxes = get_checkboxes();
		
		if ($(this).is(':checked')) {
			$checkboxes.not(':checked').prop('checked', true);
		} else {
			$checkboxes.filter(':checked').prop('checked', false);
		}
	};
	
	var bulk_submit = function() {
		
		var $checkboxes = get_checkboxes().filter(':checked');
		if (!$checkboxes.length) {
			return false;
		}
		
		var $form = $('#admin-users-unvalidated-bulk');
		$form.prop('action', $(this).prop('href'));
		
		spinner.start();
		$form.submit();
		
		return false;
	};
	
	$(document).on('change', '#admin-users-unvalidated-bulk-select', bulk_select_toggle);
	$(document).on('click', '#admin-users-unvalidated-bulk-delete', bulk_submit);
	$(document).on('click', '#admin-users-unvalidated-bulk-validate', bulk_submit);
});

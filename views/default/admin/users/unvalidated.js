define(['jquery', 'elgg/spinner'], function($, spinner) {
	
	function get_checkboxes() {
		return $('#admin-users-unvalidated-bulk .elgg-input-checkbox[name="user_guids[]"]');
	};
	
	function bulk_select_toggle() {
		var $checkboxes = get_checkboxes();
		
		if ($(this).is(':checked')) {
			$checkboxes.not(':checked').prop('checked', true);
		} else {
			$checkboxes.filter(':checked').prop('checked', false);
		}
	};
	
	function bulk_submit() {
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
	$(document).on('click', '#admin-users-unvalidated-bulk-delete, #admin-users-unvalidated-bulk-validate', bulk_submit);
});

define(['jquery', 'elgg/spinner'], function ($, spinner) {

	var bulk_submit = function() {
		
		var $checkboxes = $('#admin-users-unvalidated-bulk .elgg-input-checkbox[name="user_guids[]"]').filter(':checked');
		if (!$checkboxes.length) {
			return false;
		}
		
		var $form = $('#admin-users-unvalidated-bulk');
		$form.prop('action', $(this).prop('href'));
		
		spinner.start();
		$form.submit();
		
		return false;
	};
	
	$(document).on('click', '#uservalidationbyemail-bulk-resend', bulk_submit);
});

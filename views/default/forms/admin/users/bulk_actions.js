define(['jquery', 'elgg/spinner'], function ($, spinner) {
	
	function disable_submit() {
		$('form.elgg-form-admin-users-bulk-actions').find('button[type="submit"]').prop('disabled', true);
	};
	
	function enable_submit() {
		$('form.elgg-form-admin-users-bulk-actions').find('button[type="submit"]').prop('disabled', false);
	}
	
	$(document).on('change', 'form.elgg-form-admin-users-bulk-actions input[name="user_guids"]', function() {
		if ($(this).is(':checked')) {
			$('form.elgg-form-admin-users-bulk-actions input[name="user_guids[]"]:not(:checked)').prop('checked', true);
		} else {
			$('form.elgg-form-admin-users-bulk-actions input[name="user_guids[]"]:checked').prop('checked', false);
		}
	});
	
	$(document).on('submit', '.elgg-form-admin-users-bulk-actions', function() {
		var $checkboxes = $(this).find('input[type="checkbox"]:checked');
		if (!$checkboxes.length) {
			// no users selected for bulk action
			return false;
		}
		
		spinner.start();
		disable_submit();
	});
	
	$(document).on('change', '.elgg-form-admin-users-bulk-actions input[type="checkbox"]', function() {
		var $checkboxes = $('.elgg-form-admin-users-bulk-actions input[type="checkbox"]:checked');
		if ($checkboxes.length) {
			// enable submit
			enable_submit();
		} else {
			// disable submit
			disable_submit();
		}
	});
});

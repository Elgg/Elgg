define(['jquery', 'elgg/system_messages'], function ($, system_messages) {
	$(document).on('change', 'input[type="file"][data-max-size]', function (e) {
		var data = $(this).data();
		
		if (this.files[0].size >= data.maxSize) {
			system_messages.error(data.maxSizeMessage, 5000);
			
			if (data.files_backup) {
				this.files = data.files_backup;
				$(this).removeData('files_backup');
			} else {
				this.value = '';
			}
			
			e.stopPropagation();
			e.stopImmediatePropagation();

			return false;
		};
		
		$(this).data('files_backup', this.files);
	});
});

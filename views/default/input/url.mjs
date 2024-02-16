import 'jquery';

$(document).on('blur', 'input[type="url"].elgg-input-url', function() {
	var val = $(this).val();
	if (val === '' || val === undefined) {
		return;
	}
	
	if ($(this).is('[pattern]')) {
		return;
	}
	
	var pattern = new RegExp(/[a-z]+:\S+/gi);
	if (pattern.test(val)) {
		return;
	}
	
	$(this).val('http://' + val);
});

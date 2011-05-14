
// prevent double-submission of forms
$(function() {
	$('form').submit(function() {
		if (this.data('submitted')) {
			return false;
		}
		this.data('submitted', true);
		return true;
	});
});

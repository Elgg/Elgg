
$(function() {
	// prevent double-submission of forms
	$('form').submit(function() {
		if ($(this).data('submitted')) {
			return false;
		}
		$(this).data('submitted', true);
		return true;
	});

	// toggle the disable attribute of text box based on checkbox
	$('.elgg-combo-checkbox').click(function() {
		if ($(this).is(':checked')) {
			$(this).prev().attr('disabled', true);
			$(this).prev().val('');
		} else {
			$(this).prev().attr('disabled', false);
		}
	});
	
	$('.elgg-install-language').change(function(index, elem) {
		location.href = $(this).val();
	});
});

elgg = {
	installer: {}
};

/**
 * Check the rewrite address for "success" and then allows the installation to proceed.
 */
elgg.installer.rewriteTest = function(url, success_msg, nextURL) {
	$.ajax(url, {
		success: function(data, status, xhr) {
			if (data == 'success') {
				$('.elgg-require-rewrite li').attr('class', 'pass elgg-message elgg-message-success');
				$('.elgg-require-rewrite li').html('<p>' + success_msg + '</p>');
				$('.elgg-install-nav a.elgg-state-disabled')
					.removeClass('elgg-state-disabled')
					.attr('href', nextURL);
			}
		}
	});
};


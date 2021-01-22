define(['jquery', 'elgg/Ajax'], function ($, Ajax) {

	$('#elgg-popup-test2').on('open', function () {
		var $module = $(this);
		var $trigger = $module.data('trigger');

		var ajax = new Ajax(false);
		ajax.view('developers/ajax', {
			beforeSend: function () {
				$trigger.addClass('elgg-state-disabled').prop('disabled', true);
				$module.html('').addClass('elgg-ajax-loader');
			},
			success: function (output) {
				$module.removeClass('elgg-ajax-loader').html(output);
			}
		});
	}).on('close', function () {
		var $trigger = $(this).data('trigger');
		$trigger.removeClass('elgg-state-disabled').prop('disabled', false);
	});

});

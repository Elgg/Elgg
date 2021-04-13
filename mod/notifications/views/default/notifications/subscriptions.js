define(['jquery', 'elgg/Ajax'], function($, Ajax) {
	$(document).on('click', '.elgg-subscription-details-toggle', function() {
		var ajax = new Ajax();
		$subscription_container = $(this).closest('.elgg-subscription-container');
		$details_container = $subscription_container.next();
		if ($details_container.is(':visible')) {
			$details_container.hide();
		} else {
			if (!$details_container.is(':empty')) {
				// already loaded... just toggle the details
				$details_container.show();
			} else {
				ajax.view($(this).data('view'), {
					success: function(output) {
						$details_container.html(output).show();
					}
				});
			}
		}
	});

	$(document).on('change', '.elgg-subscription-details .elgg-input-checkbox', function() {
		// mark container checkboxes as disabled
		$record = $(this).closest('.elgg-subscription-record');
		$record.find('.elgg-subscription-methods .elgg-input-checkbox').prop('disabled', true).prop('checked', false);
		$record.find('.elgg-subscription-details-toggle').addClass('elgg-state-active');
	});
	
	$(document).on('click', '.elgg-subscription-container-details .elgg-subscriptions-details-reset', function() {
		$record = $(this).closest('.elgg-subscription-record');
		$details = $record.find('.elgg-subscription-container-details');
		$details.find('.elgg-input-checkbox').prop('checked', false);
		$details.hide();
		
		$record.find('.elgg-subscription-methods input').prop('disabled', false);
		$record.find('.elgg-subscription-details-toggle').removeClass('elgg-state-active');
		
	});
});

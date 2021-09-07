define(['jquery', 'elgg/Ajax'], function($, Ajax) {
	$(document).on('click', '.elgg-subscription-details-toggle', function() {
		var ajax = new Ajax();
		
		$subscription_container = $(this).closest('.elgg-subscription-container');
		$subscription_container.find('.elgg-subscription-details-toggle').toggleClass('hidden');
		
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
		$record.find('.elgg-subscription-details-toggle').removeClass('elgg-state-active').toggleClass('hidden');
	});
	
	// prevent email and delayed email from being enabled at the same time
	$(document).on('change', '.elgg-subscription-record .elgg-input-checkbox:checked', function() {
		if ($(this).val() !== 'delayed_email' && $(this).val() !== 'email') {
			return;
		}
		
		var $methods = $(this).closest('.elgg-field-input');
		if ($(this).val() === 'delayed_email') {
			$methods.find('.elgg-input-checkbox[value="email"]').prop('checked', false);
		} else {
			$methods.find('.elgg-input-checkbox[value="delayed_email"]').prop('checked', false);
		}
	});
});

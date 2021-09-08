define(['jquery'], function($) {
	// click on the next tab
	$('#elgg-groups-edit-footer-navigate-next').on('click', function() {
		$('.elgg-form-groups-edit .elgg-components-tabs .elgg-state-selected').next().find('> a').click();
	});
	
	// switch button banks
	$('.elgg-form-groups-edit .elgg-components-tab > a').on('click', function() {
		var $li = $(this).closest('.elgg-components-tab');
		var $next_bank = $('.elgg-groups-edit-footer-navigate');
		if ($next_bank.length === 0) {
			return;
		}
		
		var $submit_bank = $('.elgg-groups-edit-footer-submit');
		
		if ($li.is(':last-child')) {
			$next_bank.addClass('hidden');
			$submit_bank.removeClass('hidden');
		} else {
			$next_bank.removeClass('hidden');
			$submit_bank.addClass('hidden');
		}
	});
	
	// if not on the last tab, click on the next button (if available)
	$('.elgg-form-groups-edit').on('submit', function(event) {
		var $next_bank = $('.elgg-groups-edit-footer-navigate');
		if ($next_bank.length === 0 || !$next_bank.is(':visible')) {
			return;
		}
		
		event.preventDefault();
		$('#elgg-groups-edit-footer-navigate-next').click();
	});
});

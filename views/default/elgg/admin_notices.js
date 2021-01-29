/**
 * Handle deleting admin notices
 */
define(['jquery', 'elgg/Ajax'], function($, Ajax) {

	var ajax = new Ajax();

	$(document).on('click', '.elgg-admin-notice-dismiss', function (e) {
		e.preventDefault();
		var $li = $(this).closest('.elgg-item-object-admin_notice');

		// slideUp allows dismissals without notices shifting around unpredictably
		$li.slideUp(100);

		function restore() {
			$li.show();
		}

		ajax.action(this.href, {
			showSuccessMessages: false
		}).done(function() {
			// When none left, remove container so it doesn't take up space. A few CSS solutions were
			// tried, but left jerky transitions at the end of the animations.
			if (!$('.elgg-item-object-admin_notice:visible').length) {
				$('.elgg-admin-notices').remove();
			}
		}).fail(restore);
	});
});

define(['jquery', 'elgg/Ajax', 'elgg/lightbox'], function ($, Ajax, lightbox) {

	var submitReportedContent = function (event) {
		event.preventDefault();
		
		var $form = $(this);
		var ajax = new Ajax();

		ajax.action($form.attr('action'), {
			data: ajax.objectify(this),
			success: function () {
				lightbox.close();
			}
		});
	};
	
	var cancelReportedContent = function () {
		lightbox.close();
		
		return false;
	};
	
	$('.elgg-menu-item-report-this a, .elgg-menu-item-reportuser a').each(function () {
		if (!/address=/.test(this.href)) {
			this.href += '?address=' + encodeURIComponent(location.href) +
						'&title=' + encodeURIComponent(document.title);
		}
	});

	$(document).on('submit', '.elgg-form-reportedcontent-add', submitReportedContent);
	$(document).on('click', '.elgg-form-reportedcontent-add .elgg-button-cancel', cancelReportedContent);
});

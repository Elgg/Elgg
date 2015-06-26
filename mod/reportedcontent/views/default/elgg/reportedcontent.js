define(function (require) {
	var elgg = require('elgg'),
		$ = require('jquery');

	$('.elgg-menu-item-report-this a, .elgg-menu-item-reportuser a').each(function () {
		if (!/address=/.test(this.href)) {
			this.href += '?address=' + encodeURIComponent(location.href) +
						'&title=' + encodeURIComponent(document.title);
		}
	});

	$(document).on('submit', '.elgg-form-reportedcontent-add', function (e) {
		e.preventDefault();
		var $form = $(this);
		elgg.action($form[0].action, {
			data: $form.serialize(),
			success: function (data) {
				if (data.status == 0) {
					elgg.ui.lightbox.close();
				}
			}
		});
	});

	$(document).on('click', '.elgg-form-reportedcontent-add .elgg-button-cancel', function (e) {
		if ($(this).is('#colorbox *')) {
			elgg.ui.lightbox.close();
		} else {
			if (history.length > 1) {
				history.go(-1);
			} else {
				location.href = elgg.get_site_url();
			}
		}
		return false;
	});

	$(document).on('click', '.elgg-item-object-reported_content', function (e) {
		var $clicked = $(e.target),
			$li = $(this);

		if (!$clicked.is('button[data-elgg-action]')) {
			return;
		}

		var action = $clicked.data('elggAction');
		elgg.action(action.name, {
			data: action.data,
			success: function (data) {
				if (data.status == -1) {
					return;
				}

				if (action.name === 'reportedcontent/delete') {
					$li.slideUp();
				} else {
					$clicked.fadeOut();
					$li.find('.reported-content-active')
						.removeClass('reported-content-active')
						.addClass('reported-content-archived');
				}

				if (!$('.reported-content-refresh').length) {
					$li.parent().after('<p class="reported-content-refresh mtm ptm elgg-divide-top center">' +
						'<a href="">' + elgg.echo('reportedcontent:refresh') + '</a></p>');
				}
			}
		});
		return false;
	})
});

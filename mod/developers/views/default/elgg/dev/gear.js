/**
 * Note, depends on $.colorbox!
 */
define(['jquery', 'elgg', 'elgg/spinner', 'text!elgg/dev/gear.html', 'elgg/lightbox'], function ($, elgg, spinner, gear_html, lightbox) {

	$(gear_html)
		.appendTo('body')
		.find('.elgg-icon')
		.prop('title', elgg.echo('admin:developers:settings'))
		.on('click', function () {
			lightbox.open({
				href: elgg.get_site_url() + 'ajax/view/developers/gear_popup',
				initialWidth: '90%',
				maxWidth: false,
				width: '90%',
				speed: 0,
				onComplete: function () {
					$('#developer-settings-form')
						.on('submit', spinner.start)
						.find('fieldset > div')
						.each(function () {
							var $help = $('span.elgg-text-help', this),
								$label = $('label', this);

							if ($help.length != 1 || $label.length != 1) {
								return;
							}

							var $icon = $('<span class="elgg-icon-info elgg-icon" />'),
								$both = $([$icon[0], $help[0]])
									.appendTo($label)
									.on('click', function () {
										$both.toggle();
										$.colorbox.resize();
										return false;
									});
						});
					lightbox.resize();
				}
			});
		});

	$(document).on('click', '.developers-gear-popup a', function() {
		if ($(this).is('.elgg-menu-parent')) {
			return false;
		}
		spinner.start();
	});
});


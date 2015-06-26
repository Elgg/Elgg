/**
 * Note, depends on $.colorbox!
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

	$('<div class="developers-gear"><span class="elgg-icon-settings-alt elgg-icon"></span></div>')
		.appendTo('body')
		.find('.elgg-icon')
		.prop('title', elgg.echo('admin:developers:settings'))
		.on('click', function () {
			$.colorbox({
				href: elgg.get_site_url() + 'ajax/view/developers/gear_popup',
				initialWidth: '90%',
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
					$.colorbox.resize();
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

/**
 * Tabbed module
 *
 * @module page/components/tabs
 */
define(function (require) {

	var elgg = require('elgg');
	require('elgg/ready');
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax(false);

	function changeTab($tab) {

		$tab.siblings().andSelf().removeClass('elgg-state-selected');
		$tab.addClass('elgg-state-selected');

		var $target = $tab.data('target');
		if (!$target || !$target.length) {
			return false;
		}

		$target.siblings().addClass('hidden').removeClass('elgg-state-active');
		$target.removeClass('hidden').addClass('elgg-state-active');

		return true;
	}

	$(document).on('click', '.elgg-tabs-component .elgg-tabs > li > a', function (e) {
		e.preventDefault();

		var $link = $(this);
		var $tab = $(this).parent();
		var $component = $(this).closest('.elgg-tabs-component');
		var $content = $component.find('.elgg-tabs-content');

		var href = $link.data('ajaxHref') || $link.attr('href');
		var $target = $tab.data('target');

		if (href.indexOf('#') === 0) {
			// Open inline tab
			if (!$target || !$target.length) {
				var $target = $content.find(href);
				$tab.data('target', $target);
			}

			if (changeTab($tab)) {
				$tab.trigger('open');
				return;
			}
		} else {
			// Load an ajax tab
			if (!$target || !$target.length) {
				var $target = $('<div>').addClass('elgg-content hidden');
				$content.append($target);
				$tab.data('target', $target);
			}

			if ($tab.data('loaded') && !$link.data('ajaxReload')) {
				if (changeTab($tab)) {
					$tab.trigger('open');
					return;
				}
			}
			
			ajax.path(href, {
				data: $link.data('ajaxQuery') || {},
				beforeSend: function () {
					changeTab($tab);
					$target.addClass('elgg-ajax-loader');
				}
			}).done(function (output, statusText, jqXHR) {
				$tab.data('loaded', true);
				$target.removeClass('elgg-ajax-loader');
				if (jqXHR.AjaxData.status === -1) {
					$target.html(elgg.echo('ajax:error'));
					return;
				} else {
					$target.html(output);
				}

				if (changeTab($tab)) {
					$tab.trigger('open');
				}
			});
		}
	});

	// Open selected tabs
	// This will load any selected tabs that link to ajax views
	$('.elgg-tabs-component .elgg-tabs > li.elgg-state-selected > a').trigger('click');
});


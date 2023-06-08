/**
 * Tabbed module
 */
define(['jquery', 'elgg/Ajax'], function ($, Ajax) {

	var ajax = new Ajax(false);

	function changeTab($link_item, clearing_tab, trigger_open) {

		clearing_tab = typeof clearing_tab == 'boolean' ? clearing_tab : false;
		trigger_open = typeof trigger_open == 'boolean' ? trigger_open : true;

		var $target = $link_item.data('target');
		if (!$target || !$target.length) {
			return false;
		}
		
		// only change tab content if not already showing (or if clearing the tab)
		if ($target.hasClass('elgg-state-active') && !clearing_tab) {
			return true;
		}

		// find the tabs that have the selected state and remove that state
		var $tabs_component = $target.closest('.elgg-tabs-component');
		$tabs_component.find('.elgg-tabs').eq(0).find('.elgg-state-selected').removeClass('elgg-state-selected');
		
		$link_item.addClass('elgg-state-selected');
		
		$target.siblings().addClass('hidden').removeClass('elgg-state-active');
		$target.removeClass('hidden').addClass('elgg-state-active');

		require(['elgg/popup'], function(popup) {
			popup.close();
		});
		
		if (trigger_open) {
			$link_item.trigger('open');
			
			// scroll tabs into view if needed
			var rect = $link_item[0].getBoundingClientRect();
			
			var isInViewport = rect.top >= 0 &&
				rect.left >= 0 &&
				rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
				rect.right <= (window.innerWidth || document.documentElement.clientWidth);
				
			if (!isInViewport) {
				$tabs_component[0].scrollIntoView();
			}
		}
		
		return true;
	};
	
	function clickLink(event) {

		var $link = $(this);
		if ($link.hasClass('elgg-non-link')) {
			return;
		}

		event.preventDefault();
		
		var $tab = $(this).parent();
		var $content = $('.elgg-tabs-content');

		var href = $link.data('ajaxHref') || $link.attr('href');
		var $target = $tab.data('target');
		if (!$target || !$target.length) {
			// store $tagret for future use
			$target = $($link.data('target'));
			$tab.data('target', $target);
		}
		
		if (href.indexOf('#') === 0) {
			// Open inline tab
			if (changeTab($tab)) {
				return;
			}
		} else {
			// Load an ajax tab
			if ($tab.data('loaded') && !$link.data('ajaxReload')) {
				if (changeTab($tab)) {
					return;
				}
			}
			
			ajax.path(href, {
				data: $link.data('ajaxQuery') || {},
				beforeSend: function () {
					changeTab($tab, true, false);
					$target.html('');
					$target.addClass('elgg-ajax-loader');
				}
			}).done(function (output, statusText, jqXHR) {
				$tab.data('loaded', true);
				$target.removeClass('elgg-ajax-loader').html(output);
				
				changeTab($tab, true);
			}).fail(function() {
				require(['elgg/i18n'], function(i18n) {
					$target.removeClass('elgg-ajax-loader').html(i18n.echo('ajax:error'));
				});
			});
		}
	};

	// register click event
	$(document).on('click', '.elgg-components-tab > a', clickLink);

	// Open selected tabs
	// This will load any selected tabs that link to ajax views
	$('.elgg-tabs-component').each(function() {
		var $tabs = $(this).find('.elgg-components-tab:not(.elgg-menu-item-has-dropdown)');
		if (!$tabs.length) {
			return;
		}
		
		if ($tabs.hasClass('elgg-state-selected')) {
			$tabs.filter('.elgg-state-selected').eq(0).find(' > a').trigger('click');
		} else {
			$tabs.eq(0).find(' > a').trigger('click');
		}
	});
});

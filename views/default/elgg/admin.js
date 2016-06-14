/**
 * Admin-area specific javascript functions.
 *
 * @since 1.8
 */
define(function(require) {
	var $ = require('jquery');
	var ui = require('jquery-ui');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

	function init () {
		// system messages do not fade in admin area, instead slide up when clicked
		$('.elgg-system-messages li').stop(true);
		$(document).off('click', '.elgg-system-messages li');
		$(document).on('click', '.elgg-system-messages li', function(e) {
			if (!$(e.target).is('a')) {
				$(this).stop().slideUp('medium');
			}
		});

		// draggable plugin reordering
		$('#elgg-plugin-list > ul').sortable({
			items:                'li:has(> .elgg-state-draggable)',
			handle:               '.elgg-head',
			forcePlaceholderSize: true,
			placeholder:          'elgg-widget-placeholder',
			opacity:              0.8,
			revert:               500,
			stop:                 movePlugin
		});

		// in-line editing for custom profile fields.
		// @note this requires jquery.jeditable plugin
		$(".elgg-state-editable").editable(editProfileField, {
			type:   'text',
			onblur: 'submit',
			width:  '300px',
			height: 'none',
			style:  'display:inline;'
		});

		// draggable profile field reordering.
		$('#elgg-profile-fields').sortable({
			items: 'li',
			handle: 'span.elgg-state-draggable',
			stop: moveProfileField
		});

		// admin notices delete ajax
		$('a.elgg-admin-notice').click(deleteNotice);

		// disable checkboxes (readonly does not work for them)
		$(document).on('click', 'input:checkbox.elgg-state-disabled, label.elgg-state-disabled > input:checkbox', function() {
			return false;
		});

		// disable simple cache compress settings if simple cache is off
		$('[name=simplecache_enabled]').click(simplecacheToggle);
				
		// plugin category filtering
		$(document).on('click', '.elgg-admin-plugins-categories a', filterPluginCategory);
		
		// plugin details selection
		$(document).on('click', '.elgg-plugin-details-container > ul a', showPluginDetails);

		$(document).on('click', '.elgg-plugins-toggle', toggleAllPlugins);

		// plugin screenshots
		$(document).on('mouseenter', '.elgg-plugin-details-screenshots .elgg-plugin-screenshot', showPluginScreenshot);
	}

	/**
	 * Active or deactivate all the visible plugins
	 *
	 * @param {Event} e click event
	 * @return void
	 */
	function toggleAllPlugins(e) {
		e.preventDefault();

		if (!confirm(elgg.echo('question:areyousure'))) {
			return;
		}

		var guids = [],
			state = $(this).data('desiredState'),
			find_state = state == 'active' ? 'inactive' : 'active';

		$('.elgg-plugin.elgg-state-' + find_state + ':visible').each(function () {
			var guid = $(this).data('guid');
			if (guid) {
				guids.push(guid);
			}
		});

		if (!guids.length) {
			elgg.register_error(elgg.echo('admin:plugins:already:' + state));
			return;
		}

		spinner.start();

		// We create a regular form and submit it. This is the simplest way to send the
		// data, have the page refreshed, and make sure error messages are still shown on
		// the new page. Using ajax leads to complexity because Elgg wants to send the error
		// messages back to the client.
		var $form = $('<form method="post" />');
		$form.prop('action', elgg.security.addToken(this.href));
		$form.append('<input type="hidden" name="guids" value="' + guids.join(',') + '" />');
		$form.trigger('submit');
	}

	/**
	 * Save the plugin order after a move event.
	 *
	 * @param {Object} e  Event object.
	 * @param {Object} ui jQueryUI object
	 * @return void
	 */
	function movePlugin (e, ui) {
		// get guid from id like elgg-object-<guid>
		var pluginGuid = ui.item.attr('id');
		pluginGuid = pluginGuid.replace('elgg-object-', '');

		elgg.action('admin/plugins/set_priority', {
			data: {
				plugin_guid: pluginGuid,
				// we start at priority 1
				priority: ui.item.index() + 1
			},
			success: function() {
				// update plugins with priority dependences
				var priorityDep = new RegExp(elgg.echo('ElggPlugin:Dependencies:Priority'));
				ui.item.siblings().andSelf().each(function() {
					if (priorityDep.test($(this).find('.elgg-dependency-requires').text())) {
						updatePluginView($(this));
					}
				});
			}
		});
	}

	/**
	 * Update the plugin view.
	 *
	 * @param {Object} pluginView Plugin view element to update
	 * @return void
	 */
	function updatePluginView (pluginView) {
		// get guid from id like elgg-object-<guid>
		var pluginGuid = pluginView.attr('id');
		pluginGuid = pluginGuid.replace('elgg-object-', '');

		elgg.get({
			url: elgg.config.wwwroot + "ajax/view/object/plugin/full",
			dataType: "html",
			cache: false,
			data: {
				guid: pluginGuid,
				display_reordering: true
			},
			success: function(htmlData) {
				if (htmlData.length > 0) {
					pluginView.html(htmlData);
				}
			}
		});
	}

	/**
	 * In-line editing for custom profile fields
	 *
	 * @param {String} value    The new value
	 * @param {Object} settings The settings used for editable
	 * @return void
	 */
	function editProfileField (value, settings) {
		var id = $(this).attr('id');
		id = id.replace('elgg-profile-field-', '');

		var data = {
			id:    id,
			label: value
		};

		elgg.action('profile/fields/edit', data);
		return value;
	}

	/**
	 * Save the plugin profile order after a move event.
	 *
	 * @param {Object} e  Event object.
	 * @param {Object} ui jQueryUI object
	 * @return void
	 */
	function moveProfileField (e, ui) {
		var orderArr = $('#elgg-profile-fields').sortable('toArray');
		var orderStr = orderArr.join(',');

		elgg.action('profile/fields/reorder', {
			fieldorder: orderStr
		});
	}

	/**
	 * Fires the ajax action to delete the admin notice then hides the notice.
	 *
	 * @return void
	 */
	function deleteNotice (e) {
		e.preventDefault();
		var $container = $(this).closest('p');

		elgg.action($(this).attr('href'), {
			success: function(json) {
				$container.slideUp('medium');
			}
		});
	}

	/**
	 * Toggles the display of the compression settings for simplecache
	 *
	 * @return void
	 */
	function simplecacheToggle () {
		// when the checkbox is disabled, do not toggle the compression checkboxes
		if (!$(this).hasClass('elgg-state-disabled')) {
			var names = ['simplecache_minify_js', 'simplecache_minify_css'];
			for (var i = 0; i < names.length; i++) {
				var $input = $('input[type!=hidden][name="' + names[i] + '"]');
				if ($input.length) {
					$input.parent().toggleClass('elgg-state-disabled');
				}
			}
		}
	}
	
	/**
	 * Filters the plugin list based on a selected category
	 *
	 * @return void
	 */
	function filterPluginCategory (e) {
		e.preventDefault();
		
		// remove selected state from all buttons
		$(".elgg-admin-plugins-categories > li").removeClass("elgg-state-selected");
	
		// show plugins with the selected category
		$(".elgg-plugin").hide();
		$(".elgg-plugin-category-" + $(this).attr("rel")).show();
		$(this).closest('li').addClass("elgg-state-selected");
	}
	
	/**
	 * Shows the details of the selected plugin
	 *
	 * @return void
	 */
	function showPluginDetails () {
		// remove selected state from all buttons
		$(".elgg-plugin-details-container > ul > li").removeClass("elgg-state-selected");

		// must use .hide/show() to work with elgg-tabs
		$(".elgg-plugin-details-container > div > div").hide();
		$(".elgg-plugin-details-container ." + $(this).attr("rel")).show();
		
		$(this).parent().addClass("elgg-state-selected");
	}
	
	/**
	 * Handles mouseenters to show plugin screenshots
	 *
	 * @return void
	 */
	function showPluginScreenshot () {
		$(this).parent().find(".elgg-plugin-screenshot").removeClass("elgg-state-selected");
		$(this).addClass("elgg-state-selected");

		// must use .hide/show() to work with elgg-tabs
		$(".elgg-plugin-details-screenshots > div > img").hide();
		$(".elgg-plugin-details-screenshots > div > img[rel='" + $(this).attr("rel") + "']").show();
	}

	init();
});
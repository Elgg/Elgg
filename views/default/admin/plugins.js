define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');
	var Ajax = require('elgg/Ajax');

	var ajax = new Ajax();

	function init () {

		initPluginReordering();
	
		// plugin category filtering
		$(document).on('click', '.elgg-admin-plugins-categories a', filterPluginCategory);
		
		// plugin details selection
		$(document).on('click', '.elgg-plugin-details-container > ul a', showPluginDetails);

		$(document).on('click', '.elgg-plugins-toggle', toggleAllPlugins);

		$(document).on('click', '.elgg-plugin-state-change', toggleSinglePlugin);

		// plugin screenshots
		$(document).on('mouseenter', '.elgg-plugin-details-screenshots .elgg-plugin-screenshot', showPluginScreenshot);
	};

	function freezePlugins() {
		$('#elgg-plugin-list-cover').css('display', 'block');
	};
	function unfreezePlugins() {
		$('#elgg-plugin-list-cover').css('display', 'none');
	};

	function initPluginReordering() {
		$('#elgg-plugin-list > ul').sortable({
			items:                'li:has(> .elgg-state-draggable)',
			handle:               '.elgg-body',
			forcePlaceholderSize: true,
			placeholder:          'elgg-plugin-placeholder',
			opacity:              0.8,
			revert:               500,
			stop:                 movePlugin
		});
	};

	function toggleSinglePlugin(e) {
		freezePlugins();

		e.preventDefault();

		ajax.action(this.href)
			.done(function (output, statusText, jqXHR) {
				if (jqXHR.AjaxData.status == -1) {
					// don't know status :/
					location.reload();
					return;
				}

				// second request because views list must be rebuilt and this can't be done
				// within the first.
				ajax.path('admin_plugins_refresh')
					.done(function (output) {

						$('#elgg-plugin-list').html(output.list);
						$('.elgg-sidebar').html(output.sidebar);

						// reapply category filtering
						$(".elgg-admin-plugins-categories > li.elgg-state-selected > a").trigger('click');
						initPluginReordering();
						unfreezePlugins();
					});
			});
	};

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

		freezePlugins();

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
			unfreezePlugins();
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
		$form.appendTo("body").submit();
	};

	/**
	 * Save the plugin order after a move event.
	 *
	 * @param {Object} e  Event object.
	 * @param {Object} ui jQueryUI object
	 * @return void
	 */
	function movePlugin (e, ui) {
		freezePlugins();

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
				unfreezePlugins();
			}
		});
	};

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
	};
	
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
	};
	
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
	};
	
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
	};

	init();
});

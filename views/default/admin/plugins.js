define(['jquery', 'elgg', 'elgg/spinner', 'elgg/Ajax', 'jquery-ui/widgets/sortable'], function($, elgg, spinner, Ajax) {

	function freezePlugins() {
		$('#elgg-plugin-list-cover').css('display', 'block');
	};
	function unfreezePlugins() {
		$('#elgg-plugin-list-cover').css('display', 'none');
	};

	function initPluginReordering() {
		$('#elgg-plugin-list > .elgg-list-container > ul').sortable({
			items:                'li:has(> .elgg-state-draggable)',
			handle:               '.elgg-body',
			forcePlaceholderSize: true,
			placeholder:          'elgg-plugin-placeholder',
			opacity:              0.8,
			revert:               500,
			stop:                 movePlugin
		});
	};

	function toggleSinglePlugin(event) {
		freezePlugins();

		event.preventDefault();

		var ajax = new Ajax();
		
		ajax.action(this.href)
			.done(function() {
				// second request because views list must be rebuilt and this can't be done
				// within the first.
				ajax.path('admin_plugins_refresh')
					.done(function(output) {

						$('#elgg-plugin-list').html(output.list);
						$('.elgg-sidebar').html(output.sidebar);

						// reapply category filtering
						$(".elgg-admin-plugins-categories > li.elgg-state-selected > a").trigger('click');
						initPluginReordering();
						unfreezePlugins();
					});
			}).fail(function() {
				location.reload();
			});
	};

	/**
	 * Active or deactivate all the visible plugins
	 *
	 * @param {Event} e click event
	 * @return void
	 */
	function toggleAllPlugins(event) {
		event.preventDefault();

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
	function movePlugin(e, ui) {
		freezePlugins();

		// get guid from id like elgg-object-<guid>
		var pluginGuid = ui.item.attr('id');
		pluginGuid = pluginGuid.replace('elgg-object-', '');

		var ajax = new Ajax();
		ajax.action('admin/plugins/set_priority', {
			data: {
				plugin_guid: pluginGuid,
				// we start at priority 1
				priority: ui.item.index() + 1
			},
			success: function() {
				unfreezePlugins();
			}
		});
	};
	
	/**
	 * Filters the plugin list based on a selected category
	 *
	 * @return void
	 */
	function filterPluginCategory(event) {
		event.preventDefault();
		
		// remove selected state from all buttons
		$(".elgg-admin-plugins-categories > li").removeClass("elgg-state-selected");
	
		// show plugins with the selected category
		$(".elgg-plugin").hide();
		$(".elgg-plugin-category-" + $(this).attr("rel")).show();
		$(this).closest('li').addClass("elgg-state-selected");
	};

	initPluginReordering();
	
	$(document).on('click', '.elgg-admin-plugins-categories a', filterPluginCategory);
	$(document).on('click', '.elgg-plugins-toggle', toggleAllPlugins);
	$(document).on('click', '.elgg-plugin-state-change', toggleSinglePlugin);
});

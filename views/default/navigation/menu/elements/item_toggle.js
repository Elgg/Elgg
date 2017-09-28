/**
 * Adds menu item toggle features
 */
define(['elgg', 'jquery'], function (elgg, $) {
	// This function toggles a menu item that has a related toggleable menu item
	$(document).on('click', '.elgg-menu a[data-toggle]', function(event) {
		var $item_clicked = $(this).closest('li');
		var $menu = $item_clicked.closest('.elgg-menu');
		var other_menuitem_name = $(this).data().toggle.replace('_', '-');
		var $other_item = $menu.find('.elgg-menu-item-' + other_menuitem_name).eq(0);
		
		if (!$other_item) {
			return;
		}
		
		var $both_items = $item_clicked.add($other_item);
		// Be optimistic about success
		$both_items.toggleClass('hidden');
		$other_item.focus();

		// Send the ajax request
		elgg.action($(this).attr('href'), {
			success: function(json) {
				if (json.system_messages.error.length) {
					// Something went wrong, so undo the optimistic changes
					$both_items.toggleClass('hidden');
					$item_clicked.focus();
				} else {
					// let others know we toggled the menu item
					elgg.trigger_hook('toggle', 'menu_item', {
						itemClicked: $item_clicked,
						itemToggled: $other_item,
						menu: $menu,
						data: json
					});
				}
			},
			error: function() {
				// Something went wrong, so undo the optimistic changes
				$both_items.toggleClass('hidden');
				$item_clicked.focus();
			}
		});
		
		// Don't want to actually click the link
		return false;
	});
});

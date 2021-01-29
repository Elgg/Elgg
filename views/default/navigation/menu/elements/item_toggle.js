/**
 * Adds menu item toggle features
 */
define(['jquery', 'elgg', 'elgg/Ajax'], function ($, elgg, Ajax) {
	
	// This function toggles a menu item that has a related toggleable menu item
	var toggle_menu_item = function() {
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
		
		var ajax = new Ajax();
		ajax.action($(this).attr('href'), {
			success: function(result) {
				// let others know we toggled the menu item
				elgg.trigger_hook('toggle', 'menu_item', {
					itemClicked: $item_clicked,
					itemToggled: $other_item,
					menu: $menu,
					data: result
				});
			},
			error: function() {
				// Something went wrong, so undo the optimistic changes
				$both_items.toggleClass('hidden');
				$item_clicked.focus();
			}
		});
		
		// Don't want to actually click the link
		return false;
	};
	
	$(document).on('click', '.elgg-menu a[data-toggle]', toggle_menu_item);
});

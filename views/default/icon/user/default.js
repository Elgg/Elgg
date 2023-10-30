/**
 * Handles user hover menu
 */
define(['jquery'], function ($) {

	/**
	 * For a menu clicked, load the menu into all matching placeholders
	 *
	 * @param {String}   mac      Machine authorization code for the menu clicked
	 * @param {Function} callback a callback function to call when the loading of het menu was succesfull
	 */
	function loadMenu(mac, callback) {
		var $all_placeholders = $(".elgg-menu-hover[data-menu-id='" + mac + "']");
		
		if (!$all_placeholders.length) {
			return;
		}
		
		require(['elgg/Ajax'], function(Ajax) {
			var ajax = new Ajax();
			ajax.view('navigation/menu/user_hover/contents', {
				data: $all_placeholders.eq(0).data('elggMenuData'),
				success: function(data) {
					if (data) {
						// replace all existing placeholders with new menu
						$all_placeholders.html($(data));
					}
					
					if (typeof callback === 'function') {
						callback();
					}
				},
				complete: function() {
					$all_placeholders.removeAttr('data-menu-id data-elgg-menu-data');
				}
			});
		});
	};
	
	/**
	 * Show the hover menu in a popup module
	 *
	 * @params {jQuery} $icon the user icon which was clicked
	 */
	function showPopup($icon) {
		// check if we've attached the menu to this element already
		var $hovermenu = $icon.data('hovermenu') || null;

		if (!$hovermenu) {
			$hovermenu = $icon.parent().find('.elgg-menu-hover');
			$icon.data('hovermenu', $hovermenu);
		}

		$hovermenu.on('open', function() {
			$hovermenu.find('a:first').focus();
		});

		require(['elgg/popup'], function(popup) {
			if ($hovermenu.is(':visible')) {
				// close hovermenu if arrow is clicked & menu already open
				popup.close($hovermenu);
			} else {
				popup.open($icon, $hovermenu, {
					'my': 'left top',
					'at': 'left top',
					'of': $icon.closest('.elgg-avatar'),
					'collision': 'fit fit'
				});
			}
		});
	};

	// avatar contextual menu
	$(document).on('click', '.elgg-avatar-menu > a', function(e) {
		e.preventDefault();

		var $icon = $(this);

		var $placeholder = $icon.parent().find('.elgg-menu-hover[data-menu-id]');

		if ($placeholder.length) {
			loadMenu($placeholder.attr('data-menu-id'), function() {
				showPopup($icon);
			});
		} else {
			showPopup($icon);
		}
	});
});

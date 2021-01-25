/**
 * Admin-area specific javascript functions.
 *
 * @since 1.8
 */
define(['jquery'], function($) {
	
	function init () {
		// disable checkboxes (readonly does not work for them)
		$(document).on('click', 'input:checkbox.elgg-state-disabled, label.elgg-state-disabled > input:checkbox', function() {
			return false;
		});

		// disable simple cache compress settings if simple cache is off
		$('[name=require_admin_validation]').click(adminValidationToggle);
		$('[name=simplecache_enabled]').click(simplecacheToggle);
	}

	/**
	 * Toggles the display of the compression settings for simplecache
	 *
	 * @return void
	 */
	function simplecacheToggle () {
		// when the checkbox is disabled, do not toggle the compression checkboxes
		if (!$(this).hasClass('elgg-state-disabled')) {
			var names = ['simplecache_minify_js', 'simplecache_minify_css', 'cache_symlink_enabled'];
			for (var i = 0; i < names.length; i++) {
				var $input = $('input[type!=hidden][name="' + names[i] + '"]');
				if ($input.length) {
					$input.parent().toggleClass('elgg-state-disabled');
				}
			}
		}
	}
	
	function adminValidationToggle() {
		if ($(this).prop('checked')) {
			$('.elgg-admin-users-admin-validation-notification').show();
		} else {
			$('.elgg-admin-users-admin-validation-notification').hide();
		}
	}

	init();
});

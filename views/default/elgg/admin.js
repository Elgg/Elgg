/**
 * Admin-area specific javascript functions.
 *
 * @since 1.8
 */
define(function(require) {
	var $ = require('jquery');

	function init () {
		// system messages do not fade in admin area, instead slide up when clicked
		$('.elgg-system-messages li').stop(true);
		$(document).off('click', '.elgg-system-messages li');
		$(document).on('click', '.elgg-system-messages li', function(e) {
			if (!$(e.target).is('a')) {
				var $this = $(this);

				// slideUp allows dismissals without notices shifting around unpredictably
				$this.clearQueue().slideUp(100, function () {
					$this.remove();
				});
			}
		});

		// disable checkboxes (readonly does not work for them)
		$(document).on('click', 'input:checkbox.elgg-state-disabled, label.elgg-state-disabled > input:checkbox', function() {
			return false;
		});

		// disable simple cache compress settings if simple cache is off
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

	init();
});

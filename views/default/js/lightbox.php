<?php
/**
 * Elgg lightbox
 *
 * Usage
 * ---------------
 * Call elgg_load_js('lightbox') and elgg_load_css('lightbox').
 * Then apply the class elgg-lightbox to links.
 *
 *
 * Advanced Usage
 * -----------------
 * Elgg is distributed with the Colorbox jQuery library. Please go to
 * http://www.jacklmoore.com/colorbox for more information on the options of this lightbox.
 *
 * You can change global options by overriding the js/lightbox/settings view.
 *
 * You may apply colorbox options to an individual .elgg-lightbox element
 * by setting the attribute data-colorbox-opts to a JSON settings object. You
 * can also set options in the elgg.ui.lightbox.bind() method, but data
 * attributes will take precedence.
 *
 * To support a hidden div as the source, add "inline: true" as a
 * data-colorbox-opts option. For example, using the output/url view, add:
 *    'data-colorbox-opts' => '{"inline": true}',
 *
 *
 * Overriding with a different lightbox
 * -------------------------------------
 * In a plugin, override this view and override the registration for the
 * lightbox JavaScript and CSS (@see elgg_views_boot()).
 */

?>
//<script>

elgg.provide('elgg.ui.lightbox');

<?php echo elgg_view('js/lightbox/settings'); ?>

/**
 * Lightbox initialization
 */
elgg.ui.lightbox.init = function() {
	function registerDeprecationError() {
		elgg.register_error("fancybox lightbox has been replaced by colorbox", 9999999999999);
	}

	elgg.ui.lightbox.bind(".elgg-lightbox");
	elgg.ui.lightbox.bind(".elgg-lightbox-photo", {photo: true});

	if (typeof $.fancybox === 'undefined') {
		$.fancybox = {
			// error message for firefox users
			__noSuchMethod__ : registerDeprecationError,
			close: function () {
				registerDeprecationError();
				$.colorbox.close();
			}
		};
		// support $().fancybox({type:'image'})
		$.fn.fancybox = function (arg) {
			registerDeprecationError();
			if (arg.type === 'image') {
				arg.photo = true;
			}
			this.colorbox(arg);
			return this;
		};
	}
};

/**
 * Bind colorbox lightbox click to HTML
 *
 * @param {Object} selector CSS selector matching colorbox openers
 * @param {Object} opts     Colorbox options. These are overridden by data-colorbox-opts options
 */
elgg.ui.lightbox.bind = function (selector, opts) {
	if (!$.isPlainObject(opts)) {
		opts = {};
	}

	// merge opts into defaults
	opts = $.extend({}, elgg.ui.lightbox.getSettings(), opts);

	$(document).on('click', selector, function (e) {
		var $this = $(this),
			href = $this.prop('href') || $this.prop('src'),
			dataOpts = $this.data('colorboxOpts');
		// Q: why not use "colorbox"? A: https://github.com/jackmoore/colorbox/issues/435

		if (!$.isPlainObject(dataOpts)) {
			dataOpts = {};
		}

		// merge data- options into opts
		$.colorbox($.extend({href: href}, opts, dataOpts));
		e.preventDefault();
	});
};

/**
 * Close the colorbox
 *
 */
elgg.ui.lightbox.close = function() {
	$.colorbox.close();
};

elgg.register_hook_handler('init', 'system', elgg.ui.lightbox.init);

<?php

$js_path = elgg_get_config('path');
$js_path = "{$js_path}vendors/jquery/colorbox/jquery.colorbox-min.js";
readfile($js_path);

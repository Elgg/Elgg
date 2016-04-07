<?php
/**
 * Elgg lightbox
 *
 * Elgg is distributed with the Colorbox jQuery library. Please go to
 * http://www.jacklmoore.com/colorbox for more information on the options of this lightbox.
 * 
 * Use .elgg-lightbox or .elgg-lightbox-photo class on your anchor element to
 * bind it to a lightbox.
 *
 * You may apply colorbox options to an individual .elgg-lightbox element
 * by setting the attribute data-colorbox-opts to a JSON settings object.
 * You can use "getOptions", "ui.lightbox" plugin hook to filter options before
 * they are passed to $.colorbox().
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
 *
 * @deprecated 2.2
 */
?>
//<script>

// We don't depend on elgg/lightbox because it blocks until after elgg/init.
// We want this API available immediately.
require(['elgg', 'jquery', 'jquery.colorbox'], function (elgg, $) {

	elgg.deprecated_notice('lightbox.js library has been deprecated. Avoid using elgg_load_js("lightbox.js"), ' +
		'use elgg/lightbox AMD module instead');

	elgg.provide('elgg.ui.lightbox');

	if (typeof elgg.ui.lightbox.getSettings === 'function') {
		elgg.ui.lightbox.deprecated_settings = elgg.ui.lightbox.getSettings();
	}

	/**
	 * Lightbox initialization
	 * @deprecated 2.2
	 */
	elgg.ui.lightbox.init = function () {
		elgg.deprecated_notice('elgg.ui.lightbox.init() has been deprecated and should not be called directly. ' +
			'Lightbox is initialized automatically in elgg AMD module', '2.2');
	};

	/**
	 * Lightbox settings
	 * @deprecated 2.2
	 */
	elgg.ui.lightbox.getSettings = function (opts) {
		elgg.deprecated_notice('elgg.ui.lightbox.getSettings() has been deprecated and should not be called ' +
			'directly. Use elgg/lightbox AMD module instead', '2.2');

		if (!$.isPlainObject(opts)) {
			opts = {};
		}

		// Note: keep these in sync with /views/default/elgg/lightbox.js
		var settings = {
			current: elgg.echo('js:lightbox:current', ['{current}', '{total}']),
			previous: elgg.echo('previous'),
			next: elgg.echo('next'),
			close: elgg.echo('close'),
			xhrError: elgg.echo('error:default'),
			imgError: elgg.echo('error:default'),
			opacity: 0.5,
			maxWidth: '100%',
			// don't move colorbox on small viewports https://github.com/Elgg/Elgg/issues/5312
			reposition: $(window).height() > 600
		};

		elgg.provide('elgg.ui.lightbox');

		if ($.isPlainObject(elgg.ui.lightbox.deprecated_settings)) {
			$.extend(settings, elgg.ui.lightbox.deprecated_settings, opts);
		} else {
			$.extend(settings, opts);
		}

		return elgg.trigger_hook('getOptions', 'ui.lightbox', null, settings);
	};

	/**
	 * Bind colorbox lightbox click to HTML
	 * @deprecated 2.2
	 */
	elgg.ui.lightbox.bind = function () {
		elgg.deprecated_notice('elgg.ui.lightbox.bind() has been deprecated. Use elgg/lightbox AMD module ' +
			'instead', '2.2');

		require(['elgg/lightbox'], function (lightbox) {
			return lightbox.bind.apply(this, arguments);
		});
	};
	/**
	 * Close the colorbox
	 * @deprecated 2.2
	 */
	elgg.ui.lightbox.close = function () {
		elgg.deprecated_notice('elgg.ui.lightbox.close() has been deprecated. Use elgg/lightbox AMD module ' +
			'instead', '2.2');

		require(['elgg/lightbox'], function (lightbox) {
			return lightbox.close.apply(this, arguments);
		});
	};

	function registerDeprecationError() {
		elgg.register_error("fancybox lightbox has been replaced by colorbox.", 9999999999999);
	}

	if (typeof $.fancybox === 'undefined') {
		$.fancybox = {
			// error message for firefox users
			__noSuchMethod__: registerDeprecationError,
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
});

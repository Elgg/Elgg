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
 */
?>
//<script>

	require(['elgg', 'jquery'], function (elgg, $) {

		function init() {
			require(['elgg/lightbox'], function (lightbox) {

				lightbox.bind(".elgg-lightbox");
				lightbox.bind(".elgg-lightbox-photo", {photo: true});
				lightbox.bind(".elgg-lightbox-inline", {inline: true});
				lightbox.bind(".elgg-lightbox-iframe", {iframe: true});

				function registerDeprecationError() {
					elgg.register_error("fancybox lightbox has been replaced by colorbox", 9999999999999);
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
		};

		elgg.provide('elgg.ui.lightbox');

		if (typeof elgg.ui.lightbox.getSettings === 'function') {
			elgg.ui.lightbox.deprecated_settings = elgg.ui.lightbox.getSettings();
		}

		/**
		 * Lightbox settings
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.getSettings = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.getSettings() has been deprecated and should not be called directly. Use elgg/lightbox AMD module intsead', '2.2');
			var lightbox = require('elgg/lightbox');
			return lightbox.getOptions();
		};

		/**
		 * Lightbox initialization
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.init = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.init() has been deprecated and should not be called directly. Use elgg/lightbox AMD module intsead', '2.2');
			init();
		};
		/**
		 * Bind colorbox lightbox click to HTML
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.bind = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.bind() has been deprecated. Use elgg/lightbox AMD module intsead', '2.2');
			require(['elgg/lightbox'], function (lightbox) {
				lightbox.bind.apply(this, arguments);
			});
		};
		/**
		 * Close the colorbox
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.close = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.close() has been deprecated. Use elgg/lightbox AMD module intsead', '2.2');
			require(['elgg/lightbox'], function (lightbox) {
				lightbox.close.apply(this, arguments);
			});
		};

		require(['elgg/init'], init);
	});
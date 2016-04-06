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

	require(['elgg'], function (elgg) {

		elgg.deprecated_notice('lightbox.js library has been deprecated. Avoid using elgg_load_js("lightbox.js"), use elgg/lightbox AMD module instead');

		elgg.provide('elgg.ui.lightbox');

		if (typeof elgg.ui.lightbox.getSettings === 'function') {
			elgg.ui.lightbox.deprecated_settings = elgg.ui.lightbox.getSettings();
		}

		/**
		 * Lightbox initialization
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.init = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.init() has been deprecated and should not be called directly. Lightbox is initialized automatically in elgg AMD module', '2.2');
		};

		/**
		 * Lightbox settings
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.getSettings = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.getSettings() has been deprecated and should not be called directly. Use elgg/lightbox AMD module intsead', '2.2');
			var lightbox = require('elgg/lightbox');
			return lightbox.getOptions.apply(this, arguments);
		};

		/**
		 * Bind colorbox lightbox click to HTML
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.bind = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.bind() has been deprecated. Use elgg/lightbox AMD module intsead', '2.2');
			require(['elgg/lightbox'], function (lightbox) {
				return lightbox.bind.apply(this, arguments);
			});
		};
		/**
		 * Close the colorbox
		 * @deprecated 2.2
		 */
		elgg.ui.lightbox.close = function () {
			elgg.deprecated_notice('elgg.ui.lightbox.close() has been deprecated. Use elgg/lightbox AMD module intsead', '2.2');
			require(['elgg/lightbox'], function (lightbox) {
				return lightbox.close.apply(this, arguments);
			});
		};
	});
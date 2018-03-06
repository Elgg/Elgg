/**
 * Lightbox module
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
 * Overriding with a different lightbox
 * -------------------------------------
 * In a plugin, override this view and override the registration for the
 * lightbox JavaScript and CSS (@see elgg_views_boot()).
 *
 * @module elgg/lightbox
 */
define(function (require) {

	var elgg = require('elgg');
	var $ = require('jquery');
	require('elgg/init');
	require('jquery.colorbox');

	var lightbox = {

		/**
		 * Returns lightbox settings
		 *
		 * @param {Object} opts Additional options
		 * @return {Object}
		 */
		getOptions: function (opts) {
			if (!$.isPlainObject(opts)) {
				opts = {};
			}

			// data set server side using elgg.data,site hook
			var defaults = elgg.data.lightbox;

			if (!defaults.reposition) {
				// don't move colorbox on small viewports https://github.com/Elgg/Elgg/issues/5312
				defaults.reposition = $(window).height() > 600;
			}

			elgg.provide('elgg.ui.lightbox');
			
			var settings = $.extend({}, defaults, opts);

			return elgg.trigger_hook('getOptions', 'ui.lightbox', null, settings);
		},

		/**
		 * Bind colorbox lightbox click to HTML
		 *
		 * @param {Object}  selector         CSS selector matching colorbox openers
		 * @param {Object}  opts             Colorbox options. These are overridden by data-colorbox-opts options
		 * @param {Boolean} use_element_data If set to false, selector will be bound directly as `$(selector).colorbox()`
		 * @return void
		 */
		bind: function (selector, opts, use_element_data) {
			if (!$.isPlainObject(opts)) {
				opts = {};
			}

			// Allow direct binding to allow grouping by rel attribute
			if (use_element_data === false) {
				$(selector).colorbox(lightbox.getOptions(opts));
				return;
			}

			$(document)
				.off('click.lightbox', selector)
				.on('click.lightbox', selector, function (e) {
					// trigger a click event on document to close open menus / dropdowns like the entity menu #11748
					$(document).click();
					
					// remove system messages when opening a lightbox
					$('.elgg-system-messages .elgg-message').remove();
					
					e.preventDefault();
					var $this = $(this),
							href = $this.prop('href') || $this.prop('src'),
							// Note: data-colorbox was reserved https://github.com/jackmoore/colorbox/issues/435
							dataOpts = $this.data('colorboxOpts'),
							currentOpts = {};

					if (!$.isPlainObject(dataOpts)) {
						dataOpts = {};
					}

					if (!dataOpts.href && href) {
						dataOpts.href = href;
					}

					// merge data- options into opts
					$.extend(currentOpts, opts, dataOpts);
					if (currentOpts.inline && currentOpts.href) {
						currentOpts.href = elgg.getSelectorFromUrlFragment(currentOpts.href);
					}

					if (currentOpts.photo || currentOpts.inline || currentOpts.iframe || currentOpts.html) {
						lightbox.open(currentOpts);
						return;
					}
						
					href = currentOpts.href;
					currentOpts.href = false;
					var data = currentOpts.data;
					currentOpts.data = undefined;
					
					// open lightbox without a href so we get a loader
					lightbox.open(currentOpts);
					
					require(['elgg/Ajax'], function(Ajax) {
						var ajax = new Ajax(false);
						ajax.path(href, {data: data}).done(function(output) {
							currentOpts.html = output;
							lightbox.open(currentOpts);
							
							// clear data so next fetch will refresh contents
							currentOpts.html = undefined;
						});
					});
				});

			$(window)
				.off('resize.lightbox')
				.on('resize.lightbox', function() {
					elgg.data.lightbox.reposition = $(window).height() > 600;
					lightbox.resize();
				});
		},

		/**
		 * Open the colorbox
		 *
		 * @param {object} opts Colorbox options
		 * @return void
		 */
		open: function (opts) {
			$.colorbox(lightbox.getOptions(opts));
		},

		/**
		 * Close the colorbox
		 * @return void
		 */
		close: $.colorbox.close,
		
		/**
		 * Resizes the colorbox
		 * @return void
		 */
		resize: $.colorbox.resize
	};

	lightbox.bind(".elgg-lightbox");
	lightbox.bind(".elgg-lightbox-photo", {photo: true});
	lightbox.bind(".elgg-lightbox-inline", {inline: true});
	lightbox.bind(".elgg-lightbox-iframe", {iframe: true});

	return lightbox;
});

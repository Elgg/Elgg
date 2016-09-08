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
			
			$.extend(settings, opts);

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

			//console.log(use_element_data);

			// Allow direct binding to allow grouping by rel attribute
			if (use_element_data === false) {
				$(selector).colorbox(lightbox.getOptions(opts));
				return;
			}

			$(document)
				.off('click.lightbox', selector)
				.on('click.lightbox', selector, function (e) {
					e.preventDefault();
					var $this = $(this),
							href = $this.prop('href') || $this.prop('src'),
							// Note: data-colorbox was reserved https://github.com/jackmoore/colorbox/issues/435
							dataOpts = $this.data('colorboxOpts');

					if (!$.isPlainObject(dataOpts)) {
						dataOpts = {};
					}

					if (!dataOpts.href && href) {
						dataOpts.href = href;
					}

					// merge data- options into opts
					$.extend(opts, dataOpts);
					if (opts.inline && opts.href) {
						opts.href = elgg.getSelectorFromUrlFragment(opts.href);
					}

					lightbox.open(opts);
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
/**
 * Lightbox module
 * We use a named module and inline it in elgg.js. This allows us to deprecate the old
 * elgg.ui.lightbox library.
 * 
 * @module elgg/lightbox
 */
define('elgg/lightbox', function (require) {

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

			// Note: keep these in sync with /views/default/lightbox.js.php
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
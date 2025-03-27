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
 */
 
import 'jquery'; 
import '../jquery.colorbox.js'; 
import elgg from 'elgg'; 
import Ajax from 'elgg/Ajax'; 
import hooks from 'elgg/hooks';
import * as focusTrap from 'focus-trap';

let menuTrap;

var lightbox = {

	/**
	 * Returns lightbox settings
	 *
	 * @param {Object} opts Additional options
	 *
	 * @return {Object}
	 */
	getOptions: function (opts) {
		if (!$.isPlainObject(opts)) {
			opts = {};
		}

		// data set server side using 'elgg.data', 'page' hook
		var defaults = elgg.data.lightbox;

		if (!defaults.reposition) {
			// don't move colorbox on small viewports https://github.com/Elgg/Elgg/issues/5312
			defaults.reposition = $(window).height() > 600;
		}
		
		var settings = $.extend({}, defaults, opts);

		settings = hooks.trigger('getOptions', 'ui.lightbox', null, settings);
		
		// always let focusTrap logic handle trapping the focus in a lightbox
		settings.trapFocus = false;
		
		const customOnComplete = settings.onComplete;
		
		settings.onComplete = function() {
			if (typeof customOnComplete === 'function') {
				customOnComplete();
			}
			
			if ($('#cboxContent .elgg-input-longtext').length > 0) {
				// editors such as CKEditor currently conflict with focus trapping, so we do not trap focus if
				// we potentially have a text editor present in the lightbox
				return;
			}
			
			menuTrap = focusTrap.createFocusTrap('#cboxContent', {
				returnFocusOnDeactivate: false,
				clickOutsideDeactivates: false,
				allowOutsideClick: true,
				escapeDeactivates: false
			});
			menuTrap.activate();
		};
		
		const customOnClosed = settings.onClosed;
		
		settings.onClosed = function() {
			if (typeof customOnClosed === 'function') {
				customOnClosed();
			}
			
			if (typeof menuTrap !== 'undefined') {
				menuTrap.deactivate();
			}
		};
		
		return settings;
	},

	/**
	 * Bind colorbox lightbox click to HTML
	 *
	 * @param {Object}  selector         CSS selector matching colorbox openers
	 * @param {Object}  opts             Colorbox options. These are overridden by data-colorbox-opts options
	 * @param {Boolean} use_element_data If set to false, selector will be bound directly as `$(selector).colorbox()`
	 *
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
				
				currentOpts.ajaxLoadWithDependencies = true;
				lightbox.open(currentOpts);
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
	 *
	 * @return void
	 */
	open: function (opts) {
		var currentOpts = lightbox.getOptions(opts);
		
		if (!currentOpts.ajaxLoadWithDependencies) {
			$.colorbox(currentOpts);
			return;
		}
		
		var href = currentOpts.href;
		currentOpts.href = false;
		var data = currentOpts.data;
		currentOpts.data = undefined;
		
		// open lightbox without a href so we get a loader
		$.colorbox(currentOpts);

		var ajax = new Ajax(false);
		ajax.path(href, {
			data: data
		}).done(function(output) {
			currentOpts.html = output;
			$.colorbox(currentOpts);
			
			// clear data so next fetch will refresh contents
			currentOpts.html = undefined;
		}).fail(function() {
			$.colorbox.close();
		});
	},

	/**
	 * Close the colorbox
	 *
	 * @return void
	 */
	close: $.colorbox.close,
	
	/**
	 * Resizes the colorbox
	 *
	 * @return void
	 */
	resize: $.colorbox.resize
};

lightbox.bind(".elgg-lightbox");
lightbox.bind(".elgg-lightbox-photo", {photo: true});
lightbox.bind(".elgg-lightbox-inline", {inline: true});
lightbox.bind(".elgg-lightbox-iframe", {iframe: true});

export default lightbox;

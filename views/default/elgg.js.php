<?php
/**
 * Core Elgg JavaScript file
 */

echo elgg_view('core/js/elgglib.js');
echo elgg_view('core/js/deprecated.js');
echo elgg_view('core/js/hooks.js');

foreach (_elgg_get_js_site_data() as $expression => $value) {
	$value = json_encode($value);
	echo "{$expression} = {$value};" . PHP_EOL;
}
?>

// page data overrides site data
elgg.data = $.extend(true, {}, elgg.data, elgg._data);
delete elgg._data;

// jQuery and UI must be loaded sync in 2.x but modules should depend on these AMD modules
define('jquery', function () {
	return jQuery;
});

define('elgg', ['jquery'], function($) {

	// @todo we need better documentation for this hack
	// iOS Hover Event Class Fix
	$('.elgg-page').attr('onclick', 'return true');
	
	// Allow element to be highlighted using CSS if its id is found from the URL
	var elementId = elgg.getSelectorFromUrlFragment(document.URL);
	$(elementId).addClass('elgg-state-highlight');
	
	require(['elgg/popup'], function(popup) {
		// @todo remove the require here in Elgg 5.0
		popup.bind($('[rel="popup"]'));
	});
	
	/**
	 * Toggles an element based on clicking a separate element (deprecated, use 'elgg/toggle')
	 *
	 * Use rel="toggle" on the toggler element
	 * Set the href to target the item you want to toggle (<a rel="toggle" href="#id-of-target">)
	 * or use data-toggle-selector="your_jquery_selector" to have an advanced selection method
	 *
	 * By default elements perform a slideToggle.
	 * If you want a normal toggle (hide/show) you can add data-toggle-slide="0" on the elements to prevent a slide.
	 *
	 * @param {Object} event
	 * @return void
	 */
	function toggles(event) {
		event.preventDefault();
		var $this = $(this),
			selector = $this.data().toggleSelector;
	
		if (!selector) {
			// @todo we can switch to elgg.getSelectorFromUrlFragment() in 1.x if
			// we also extend it to support href=".some-class"
			selector = $this.attr('href');
		}
	
		var $elements = $(selector);
	
		$this.toggleClass('elgg-state-active');
	
		$elements.each(function(index, elem) {
			var $elem = $(elem);
			if ($elem.data().toggleSlide != false) {
				$elem.slideToggle('medium');
			} else {
				$elem.toggle();
			}
		});
	
		$this.trigger('elgg_ui_toggle', [{
			$toggled_elements: $elements
		}]);
	};
	
	/**
	 * Calls a confirm() and returns false if denied.
	 *
	 * @param {Object} e
	 * @return void
	 */
	function requiresConfirmation(e) {
		var i18n = require('elgg/i18n');
		var confirmText = $(this).data('confirm') || i18n.echo('question:areyousure');
		if (!confirm(confirmText)) {
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			return false;
		}
	};

	$(document).on('click', '[rel=toggle]', toggles);
	$(document).on('click', '*[data-confirm]', requiresConfirmation);

	return elgg;
});

// @todo no longer require elgg/i18n in Elgg 5.0
require(['elgg', 'elgg/i18n']); // Forces the define() function to always run

// Process queue. We have to wait until now because many modules depend on 'elgg' and we can't load
// it asynchronously.
if (!window._require_queue) {
	if (window.console) {
		console.log('Elgg\'s require() shim is not defined. Do not override the view "page/elements/head".');
	}
} else {
	while (_require_queue.length) {
		require.apply(null, _require_queue.shift());
	}
	delete window._require_queue;
}

elgg.trigger_hook('init', 'system');

require(['elgg/lightbox', 'elgg/security']);

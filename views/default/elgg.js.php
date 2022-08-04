<?php
/**
 * Core Elgg JavaScript file
 */

echo elgg_view('core/js/elgglib.js');

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
	
	/**
	 * Calls a confirm() and returns false if denied.
	 *
	 * @param {Object} e
	 * @return void
	 */
	function requiresConfirmation(e) {
		var confirmText = $(this).data('confirm');
		if (!confirm(confirmText)) {
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			return false;
		}
	};

	$(document).on('click', '*[data-confirm]', requiresConfirmation);

	return elgg;
});

require(['elgg']); // Forces the define() function to always run

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

require(['elgg/lightbox', 'elgg/security']);

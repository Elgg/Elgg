<?php
/**
 * Processes functions queued by elgg_defer() and defines it.
 *
 * @internal Do not alter or use this view.
 *
 * @note Early in the boot sequence elgg_defer is defined in the view "page/elements/head"
 */
if (0) { ?><script><?php } ?>

!function () {
	var c = window.console || {};
	c.log = c.log || elgg.nullFunction;
	c.error = c.error || elgg.nullFunction;

	// handle a queued function call
	function process_deferred(f) {
		if (typeof f !== 'function') {
			c.error('elgg_defer() accepts only functions. Given:', f);
			return;
		}
		$(f);
		c.log('elgg_defer() is deprecated. Please convert inline scripts to AMD.');
	}

	if (typeof window.elgg_defer !== 'function') {
		c.error('elgg_defer() is not defined. Do not override the view "page/elements/head".');
		return;
	}

	// empty the queue and redefine
	while (elgg_defer._queue.length) {
		process_deferred(elgg_defer._queue.shift());
	}

	elgg_defer = function (f) {
		process_deferred(f);
	};
}();

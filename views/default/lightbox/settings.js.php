<?php
/**
 * Elgg lightbox settings view
 *
 * Override this view to change the default colorbox settings. 
 * See the js/lightbox view for more ways to change lightbox behavior.
 */
?>
//<script>

/**
 * @param {Function} current  Translator
 * @param {Function} previous Translator
 * @param {Function} next     Translator
 * @param {Function} close    Translator
 * @param {Function} error    Translator
 * @returns {Object}
 */
elgg.ui.lightbox.getSettings = function (current, previous, next, close, error) {
	return {
		current: current(['{current}', '{total}']),
		previous: previous(),
		next: next(),
		close: close(),
		xhrError: error(),
		imgError: error(),
		opacity: 0.5,
		maxWidth: '100%',

		// don't move colorbox on small viewports https://github.com/Elgg/Elgg/issues/5312
		reposition: $(window).height() > 600
	};
};

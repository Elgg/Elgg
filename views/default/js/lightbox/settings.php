<?php
/**
 * Elgg lightbox settings view
 *
 * Override this view to change the default colorbox settings. 
 * See the js/lightbox view for more ways to change lightbox behavior.
 */
?>
//<script>

elgg.ui.lightbox.getSettings = function () {
	return {
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
};

<?php
/**
 * @deprecated 2.2
 */
elgg_deprecated_notice('lightbox/settings.js view has been deprecated. Use "getOptions","ui.lightbox" plugin hook or data-colorbox-opts attribute instead', '2.2');
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

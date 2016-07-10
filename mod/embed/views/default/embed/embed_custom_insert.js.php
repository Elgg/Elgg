<?php
/**
 * This is a temporary view used to properly deprecated embed/custom_insert_js view
 * Do not use or extend this view
 * @deprecated 1.9
 */
?>
//<script>
	require(['elgg'], function(elgg) {
		elgg.provide('elgg.embed');
		/**
		 * Adds support for plugins that extends embed/custom_insert_js deprecated views
		 *
		 * @param {String} hook
		 * @param {String} type
		 * @param {Object} params
		 * @param {String|Boolean} value
		 * @returns {String|Boolean}
		 * @private
		 */
		elgg.embed._deprecated_custom_insert_js = function (hook, type, params, value) {
			var textAreaId = params.textAreaId;
			var content = params.content;
			var event = params.event;
			<?= elgg_view('embed/custom_insert_js') ?>
		};
	});
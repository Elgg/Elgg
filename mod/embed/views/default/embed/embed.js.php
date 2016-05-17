<?php
/**
 * @deprecated 2.2
 */
elgg_deprecated_notice('elgg/embed.js view has been deprecated and should not be included or loaded as a JS library. Use elgg/embed AMD module instead', '2.2');
?>
//<script>
require(['elgg', 'elgg/embed'], function(elgg) {
	elgg.deprecated_notice('elgg.embed JS library has been deprecated. Use elgg/embed AMD module', '2.2');
});
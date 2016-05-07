<?php
/**
 * @package ElggGroups
 * @deprecated 2.2
 */
elgg_deprecated_notice('groups/js view has been deprecated. Instead define dependency on "groups/navigation" AMD module for "feature" and "unfeature" menu items', '2.2');
?>
//<script>
require(['elgg'], function(elgg) {
	elgg.ui.registerTogglableMenuItems('feature', 'unfeature');
});

<?php
/**
 * Javascript for Groups forms
 *
 * @package ElggGroups
 */
?>
elgg.deprecated_notice('Use of elgg.groups is deprecated in favor of the elgg/groups AMD module', '1.9');
// this adds a class to support IE8 and older
elgg.register_hook_handler('init', 'system', function() {
	// jQuery uses 0-based indexing
	$('#groups-tools').children('li:even').addClass('odd');
});

elgg.ui.registerTogglableMenuItems('feature', 'unfeature');

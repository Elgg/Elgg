<?php
/**
 * Javascript for Groups forms
 *
 * @package ElggGroups
 */
?>

// this adds a class to support IE8 and older
elgg.register_hook_handler('init', 'system', function() {
	// jQuery uses 0-based indexing
	$('#groups-tools').children('li:even').addClass('odd');
	
	// Show/hide additional option for group content accessibility on group edit pages
	$('select[name="content_access_mode"]').change(function() {
		if ($(this).val() == 'members_only') {
			$('fieldset.group_access_mode_change').show();
		} else {
			$('fieldset.group_access_mode_change').hide();
		}
	});
});

elgg.ui.registerTogglableMenuItems('feature', 'unfeature');

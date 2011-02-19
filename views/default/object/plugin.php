<?php
/**
 * Used to show plugin user settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 *
 * @todo This view really should be used to display visualization on the admin panel, \
 * rather than emitting the settings forms
 */

// Do we want to show admin settings or user settings
$type = elgg_extract('type', $vars, '');

if ($type != 'user') {
	$type = '';
}

?>
<div>
	<?php echo elgg_view_form("plugins/{$type}settings/save", array(), $vars); ?>
</div>
<?php
/**
 * Show a notice about the retention period of trashed items
 */

$retention = (int) elgg_get_config('trash_retention');
if ($retention < 1) {
	return;
}

echo elgg_view_message('notice', elgg_echo('trash:notice:retention', [elgg_format_element('strong', [], $retention)]), [
	'title' => false,
]);

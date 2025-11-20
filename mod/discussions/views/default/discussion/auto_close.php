<?php
/**
 * Status notification about auto closing of discussions
 *
 * @uses $vars['entity']       The discussion (when editing)
 * @uses $vars['warning_days'] The number of days before the auto closing is affected and presenting the
 *                             informational message as a warning (default: 10)
 */

use Elgg\Values;

$days = (int) elgg_get_plugin_setting('auto_close_days', 'discussions');
if ($days < 1) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggDiscussion) {
	echo elgg_view_message('info', elgg_echo('discussion:auto_close:new', [$days]), ['title' => false]);
	return;
}

if ($entity->status === 'closed') {
	return;
}

$warning_days = (int) elgg_extract('warning_days', $vars, 10);

$auto_close_date = Values::normalizeTime($entity->last_action);
$auto_close_date->modify("+{$days} days");

$remaining_days = (int) $auto_close_date->diff(new \DateTime())->days;
if ($remaining_days < 1) {
	$remaining_days = 0;
}

$type = $remaining_days < $warning_days ? 'warning' : 'info';

echo elgg_view_message($type, elgg_echo('discussion:auto_close:edit', [$remaining_days]), ['title' => false]);

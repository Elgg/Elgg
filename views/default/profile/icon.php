<?php
/**
 * Elgg profile icon
 *
 * @deprecated 1.8 use elgg_view_entity_icon()
 *
 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed.
 * @uses $vars['override']
 * @uses $vars['js']
 */
elgg_deprecated_notice('The profile/icon view was deprecated.  Use elgg_view_entity_icon()', 1.8);

$override = elgg_extract('override', $vars, false);
$vars['hover'] = !$override;

echo elgg_view('icon/user/default', $vars);

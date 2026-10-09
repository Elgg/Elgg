<?php
/**
 * ElggUpgrade view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUpgrade) {
	return;
}

$component = $entity->getComponent();
$version = $entity->getVersion();

$description = elgg_echo("{$component}:upgrade:{$version}:description");

$plugin = $component !== 'core' ? elgg_get_plugin_from_id($component) : null;

$vars['title'] = ($plugin instanceof \ElggPlugin ? $plugin->getDisplayName() . ': ' : '') . $entity->getDisplayName();

if (!$entity->isCompleted()) {
	$vars['subtitle'] = $description;
	
	echo elgg_view('object/elgg_upgrade/pending', $vars);
	return;
}

$vars['content'] = $description;

echo elgg_view('object/elgg_upgrade/completed', $vars);

<?php

/**
 * Default profile layout header
 *
 * @uses $vars['entity'] Entity
 */

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggObject) {
	return;
}

$container = $entity->getContainerEntity();

$title = elgg_view_title($entity->getDisplayName(), [
	'class' => 'elgg-profile-heading display-4',
	'tag' => 'h1',
]);

$params['show_icons'] = true;
$imprint = elgg_view('object/elements/imprint', $vars + $params);

$params = $vars;
$params['sort_by'] = 'priority';
$menu = elgg_view_menu('title', $params);

?>
<div class="elgg-profile-layout-header">
	<?= $title ?>
	<?= $imprint ?>
	<?= $menu ?>
</div>
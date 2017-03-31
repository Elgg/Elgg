<?php

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggGroup) {
	return;
}

$icon = elgg_view_entity_icon($entity, 'large', [
	'use_hover' => false,
	'use_link' => false,
	'href' => false,
	'img_class' => 'rounded-circle',
]);

groups_register_profile_buttons($entity);

$title = elgg_view_title($entity->getDisplayName(), ['class' => 'groups-layout-heading display-4']);

$lead = '';
if ($entity->briefdescription) {
	$lead = elgg_view('output/longtext', [
		'value' => $entity->briefdescription,
		'class' => 'lead',
	]);
}

$params = $vars;
$params['sort_by'] = 'priority';
$menu = elgg_view_menu('title', $params);

?>
<div class="groups-layout-header">
	<?= $icon ?>
	<?= $title ?>
	<?= $lead ?>
	<?= $menu ?>
</div>
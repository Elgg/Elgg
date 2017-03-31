<?php

/**
 * User profile layout header
 */
$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggUser) {
	return;
}

$icon = elgg_view('output/img', [
	'src' => $entity->getIconURL('large'),
	'class' => 'rounded-circle',
	'alt' => $entity->getDisplayName(),
]);

$title = elgg_view_title($entity->getDisplayName(), [
	'class' => 'elgg-profile-heading display-4',
]);

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
<div class="profile-layout-header">
	<?= $icon ?>
	<?= $title ?>
	<?= $lead ?>
	<?= $menu ?>
</div>
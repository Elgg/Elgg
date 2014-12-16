<?php
/**
 * Site notification view
 */

$entity = $vars['entity'];

$text = $entity->description;
$actor = $entity->getActor();
if ($actor) {
	$icon = elgg_view_entity_icon($actor, 'tiny');
}
$url = $entity->getURL();
if ($url) {
	$text = elgg_view('output/url', array(
		'text' => $text,
		'href' => $url,
		'is_trusted' => true,
		'class' => 'site-notifications-link',
		'id' => 'site-notifications-link-' . $entity->guid,
	));
}

elgg_register_menu_item('site_notifications', array(
	'name' => 'time',
	'href' => false,
	'text' => elgg_view_friendly_time($entity->getTimeCreated()),
));
elgg_register_menu_item('site_notifications', array(
	'name' => 'delete',
	'href' => 'action/site_notifications/delete?guid=' . $entity->guid,
	'text' => elgg_view_icon('delete'),
	'is_action' => true,
	'link_class' => 'site-notifications-delete',
	'id' => 'site-notifications-delete-' . $entity->guid,
));
$menu = elgg_view_menu('site_notifications', array(
	'class' => 'elgg-menu-hz elgg-menu-entity',
));

$checkbox = elgg_view('input/checkbox', array(
	'name' => 'notification_id[]', 
	'value' => $entity->getGUID(), 
	'default' => false
));

$list_body = elgg_view_image_block($icon, $text, array(
	'image_alt' => $menu,
	'class' => 'pvn'
));

echo elgg_view_image_block($checkbox, $list_body);
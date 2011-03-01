<?php
/**
 * Elgg default annotation view
 *
 * @uses $vars['annotation']
 */

$annotation = $vars['annotation'];

$owner = get_entity($annotation->owner_guid);
if (!$owner) {
	return true;
}
$icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = "<a href=\"{$owner->getURL()}\">$owner->name</a>";

$delete_action = elgg_extract('delete_action', $vars, '');

$text = elgg_view("output/longtext", array("value" => $annotation->value));

$friendlytime = elgg_view_friendly_time($annotation->time_created);

$delete_button = '';
if ($delete_action && $annotation->canEdit()) {
	$url = elgg_http_add_url_query_elements($delete_action, array(
		'annotation_id' => $annotation->id,
	));
	$delete_button = elgg_view("output/confirmlink", array(
						'href' => $url,
						'text' => elgg_echo('delete'),
						'confirm' => elgg_echo('deleteconfirm')
					));
	$delete_button = "<span class=\"elgg-button elgg-button-delete\">$delete_button</span>";
}

$body = <<<HTML
<div class="mbn">
	$delete_button
	$owner_link
	<span class="elgg-subtext">
		$friendlytime
	</span>
	$text
</div>
HTML;

echo elgg_view_image_block($icon, $body);

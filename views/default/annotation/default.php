<?php
/**
 * Elgg default annotation view
 *
 * @note To add or remove from the annotation menu, register handlers for the menu:annotation hook.
 *
 * @uses $vars['annotation']
 */

$annotation = elgg_extract('annotation', $vars);

$owner = get_entity($annotation->owner_guid);
if (!$owner) {
	return true;
}
$icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', [
	'text' => $owner->getDisplayName(),
	'href' => $owner->getURL(),
	'is_trusted' => true,
]);

$menu = elgg_view_menu('annotation', [
	'annotation' => $annotation,
	'class' => 'elgg-menu-hz float-alt',
]);

$text = elgg_view("output/longtext", ["value" => $annotation->value]);

$friendlytime = elgg_view_friendly_time($annotation->time_created);

$body = <<<HTML
<div class="mbn">
	$menu
	$owner_link
	<span class="elgg-subtext">
		$friendlytime
	</span>
	$text
</div>
HTML;

echo elgg_view_image_block($icon, $body);

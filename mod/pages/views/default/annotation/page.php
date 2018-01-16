<?php
/**
 * Revision view for history page
 *
 * @package ElggPages
 */

$annotation = elgg_extract('annotation', $vars);
if (!($annotation instanceof \ElggAnnotation)) {
	return;
}

$page = $annotation->getEntity();
if (!$page instanceof ElggPage) {
	return;
}

$owner = $annotation->getOwnerEntity();
if (!$owner instanceof ElggEntity) {
	return;
}
$icon = elgg_view_entity_icon($owner, 'small');

$owner_link = elgg_view('output/url', [
	'href' => $owner->getURL(),
	'text' => $owner->getDisplayName(),
	'is_trusted' => true,
]);

$date = elgg_view_friendly_time($annotation->time_created);

$title_link = elgg_view('output/url', [
	'href' => $annotation->getURL(),
	'text' => $page->getDisplayName(),
	'is_trusted' => true,
]);

$subtitle = elgg_echo('pages:revision:subtitle', [$date, $owner_link]);

$menu = '';
if (!elgg_in_context('widgets')) {
	// only show annotation menu outside of widgets
	$menu = elgg_view_menu('annotation', [
		'annotation' => $annotation,
		'class' => 'elgg-menu-hz float-alt',
	]);
}

$body = <<<HTML
<div class="mbn">
	$menu
	<h3>$title_link</h3>
	<span class="elgg-subtext">
		$subtitle
	</span>
</div>
HTML;

echo elgg_view_image_block($icon, $body);

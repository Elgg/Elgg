<?php
/**
 * Body of river item
 *
 * @uses $vars['item']
 */

$item = $vars['item'];

$menu = elgg_view_menu('river', array('item' => $item, 'sort_by' => 'priority'));

// river item header
$timestamp = elgg_get_friendly_time($item->getPostedTime());

$summary = elgg_extract('summary', $vars, elgg_view('river/elements/summary', array('item' => $vars['item'])));
if ($summary === false) {
	$subject = $item->getSubjectEntity();
	$summary = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
	));
}

$message = elgg_extract('message', $vars, false);
if ($message !== false) {
	$message = "<div class=\"elgg-river-message\">$message</div>";
}

$attachments = elgg_extract('attachments', $vars, false);
if ($attachments !== false) {
	$attachments = "<div class=\"elgg-river-attachments\">$attachments</div>";
}

$footer = elgg_view('river/elements/footer', $vars);

$group_string = '';
$object = $item->getObjectEntity();
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
	));
	$group_string = elgg_echo('river:ingroup', array($group_link));
}

echo <<<RIVER
$menu
<div class="elgg-river-summary">$summary $group_string <span class="elgg-river-timestamp">$timestamp</span></div>
$message
$attachments
$footer
RIVER;

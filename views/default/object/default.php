<?php
/**
 * ElggEntity default view.
 *
 * @package Elgg
 * @subpackage Core
 */

if ($vars['full']) {
	echo elgg_view('export/entity', $vars);
} else {

	$icon = elgg_view(
			'graphics/icon', array(
			'entity' => $vars['entity'],
			'size' => 'small',
		)
	);


	$title = $vars['entity']->title;
	if (!$title) {
		$title = $vars['entity']->name;
	}
	if (!$title) {
		$title = get_class($vars['entity']);
	}

	$controls = "";
	if ($vars['entity']->canEdit()) {
		$delete = elgg_view('output/confirm_link', array(
			'href' => "action/entities/delete?guid={$vars['entity']->guid}",
			'text' => elgg_echo('delete')
		));
		$controls .= " ($delete)";
	}

	$info = "<div><p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $title . "</a></b> $controls </p></div>";

	if (get_input('listtype') == "gallery") {
		$icon = "";
	}

	$owner = $vars['entity']->getOwnerEntity();
	$ownertxt = elgg_echo('unknown');
	if ($owner) {
		$ownertxt = "<a href=\"" . $owner->getURL() . "\">" . $owner->name . "</a>";
	}

	$info .= "<div>" . elgg_echo("entity:default:strapline", array(
		elgg_view_friendly_time($vars['entity']->time_created),
		$ownertxt
	));

	$info .= "</div>";

	$info = "<span title=\"" . elgg_echo('entity:default:missingsupport:popup') . "\">$info</span>";
	$icon = "<span title=\"" . elgg_echo('entity:default:missingsupport:popup') . "\">$icon</span>";

	echo elgg_view_listing($icon, $info);
}

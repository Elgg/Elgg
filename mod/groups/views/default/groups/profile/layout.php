<?php
/**
 * Layout of the groups profile page
 *
 * @uses $vars['entity']
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

echo elgg_view('groups/profile/summary', $vars);

if (elgg_is_logged_in()) {
	$relationship = $group->getRelationship(elgg_get_logged_in_user_guid(), 'invited');
	if ($relationship) {
		$menu = elgg_view_menu('groups:invite', ['items' => [groups_get_group_join_menu_item($group)]]);
		$date = \Elgg\Values::normalizeTime($relationship->getTimeCreated())->formatLocale(elgg_echo('friendlytime:date_format:short'));
		$message = elgg_echo('groups:invite:message', [elgg_format_element('strong', [], $date)]);
		echo elgg_view_message('notice', $message, ['class' => 'mtl', 'title' => false, 'link' => $menu]);
	}
}

if ($group->canAccessContent()) {
	echo elgg_view('groups/profile/widgets', $vars);
}

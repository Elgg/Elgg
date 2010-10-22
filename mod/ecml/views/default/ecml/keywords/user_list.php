<?php
/**
 * Lists users
 *
 * @package SitePages
 */

$only_with_avatars = (isset($vars['only_with_avatars'])) ? $vars['only_with_avatars'] : TRUE;
$list_type = (isset($vars['list_type'])) ? $vars['list_type'] : 'newest';
$limit = (isset($vars['limit'])) ? $vars['limit'] : 10;

$options = array(
	'type' => 'user',
	'limit' => $limit
);

if ($only_with_avatars == TRUE) {
	$options['metadata_name_value_pairs'] = array('name' => 'icontime', 'operand' => '!=', 'value' => 0);
}

switch ($list_type) {
	case 'newest':
		$options['order_by'] = 'e.time_created DESC';
		break;

	case 'online':
		// show people with a last action of < 10 minutes.
		$last_action = time() - 10 * 60;
		$options['joins'] = array("JOIN {$vars['config']->dbprefix}users_entity ue on ue.guid = e.guid");
		$options['wheres'] = array("ue.last_action > $last_action");
		break;

	case 'random':
		$options['order_by'] = 'RAND()';
		break;

	default:
		break;
}

$users = elgg_get_entities_from_metadata($options);

echo elgg_view_entity_list($users, count($users), 0, $limit, FALSE, FALSE, FALSE);
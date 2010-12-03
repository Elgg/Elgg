<?php
/**
 * ECML activity support
 *
 * @package ECML
 */

// get_loggedin_user() is apparently the loggedin user.
$owner = (isset($vars['owner'])) ? $vars['owner'] : NULL;
$type = (isset($vars['type'])) ? $vars['type'] : NULL;
$subtype = (isset($vars['subtype'])) ? $vars['subtype'] : NULL;
$limit = (isset($vars['limit'])) ? $vars['limit'] : 10;

// resolve usernames to guids
if ($owner && !is_numeric($owner)) {
	if ($user_obj = get_user_by_username($owner)) {
		$owner = $user_obj->getGUID();
	}
}

$river = elgg_view_river-items($owner, NULL, NULL, $type, $subtype, '', $limit, 0, 0, true, false)  . "</div>";

// Replacing callback calls in the nav with something meaningless
$river = str_replace('callback=true','replaced=88,334', $river);

echo $river;
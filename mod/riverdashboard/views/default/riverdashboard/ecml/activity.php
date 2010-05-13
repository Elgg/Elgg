<?php
/**
 * ECML activity support
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

// $vars['user'] is apparently the loggedin user.
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

$river = elgg_view_river_items($owner, NULL, NULL, $type, $subtype, '', $limit, 0, 0, true, false)  . "</div>";

// Replacing callback calls in the nav with something meaningless
$river = str_replace('callback=true','replaced=88,334', $river);

echo $river;
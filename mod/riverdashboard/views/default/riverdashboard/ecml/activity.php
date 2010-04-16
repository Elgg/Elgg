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

$user = (isset($vars['user'])) ? $vars['user'] : NULL;
$type = (isset($vars['type'])) ? $vars['type'] : NULL;
$subtype = (isset($vars['subtype'])) ? $vars['subtype'] : NULL;
$limit = (isset($vars['limit'])) ? $vars['limit'] : 10;

// resolve usernames to guids
if (!is_numeric($user)) {
	if ($user_obj = get_user_by_username($user)) {
		$user = $user_obj->getGUID();
	}
}

$river = elgg_view_river_items($user, NULL, NULL, $type, $subtype, '', $limit, 0, 0, true, false)  . "</div>";

// Replacing callback calls in the nav with something meaningless
$river = str_replace('callback=true','replaced=88,334',$river);

echo $river;
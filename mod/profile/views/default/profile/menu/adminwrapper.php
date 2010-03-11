<?php
/**
 * Wraps the admin links
 * 
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 * 
 */

$adminlinks = elgg_view('profile/menu/adminlinks', $vars);

if (!empty($adminlinks)) {
	echo "<li class='user_menu_admin'>{$adminlinks}</li>";
}
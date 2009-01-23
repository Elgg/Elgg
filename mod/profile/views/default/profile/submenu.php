<?php

	/**
	 * Elgg profile submenu links
	 * These sit in the submenu when the profile editing is on view
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */
	 
?>

<ul>
    <li><a href="<?php echo $CONFIG->wwwroot . "mod/profile/edit.php"; ?>"><?php echo elgg_echo('profile:details'); ?></a></li>
    <li><a href="<?php echo $CONFIG->wwwroot."mod/profile/editicon.php"; ?>"><?php echo elgg_echo('profile:editicon'); ?></a></li>
    <li><a href="<?php echo $CONFIG->wwwroot."pg/profile/" . $_SESSION['user']->username; ?>"><?php echo elgg_echo('profile:back'); ?></a></li>
</ul>
<?php

	/**
	 * Elgg standard top level menu
	 * The standard user top level navigation; dashboard, profile, account, logout
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 */
	 
?>

<?php
     if (isloggedin()) {
?>
<div id="topmenu">
	<div class="usericon">
	<?php

    	echo elgg_view("profile/icon",array('entity' => $vars['user'], 'size' => 'small'));
    
    ?>
    </div>
    <ul>
        <li><a href="<?php echo $vars['url']; ?>pg/dashboard/"><?php echo elgg_echo('dashboard'); ?></a></li>
        <li><a href="<?php echo $vars['url']; ?>pg/settings/"><?php echo elgg_echo('settings'); ?></a></li>
<?php

		// The administration link is for admin or site admin users only
		if ($vars['user']->admin || $vars['user']->siteadmin) { 

?>

		<li><a href="<?php echo $vars['url']; ?>pg/admin/"><?php echo elgg_echo("admin"); ?></a></li>

<?php

		}

?>
        <li><a href="<?php echo $vars['url']; ?>action/logout"><?php echo elgg_echo('logout'); ?></a></li>
    </ul>
</div>
<?php
    }
?>
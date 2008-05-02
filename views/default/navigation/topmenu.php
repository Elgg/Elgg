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
	<div style="float:right">
	<?php

    	echo elgg_view("profile/icon",array('entity' => $vars['user'], 'size' => 'small'));
    
    ?>
    </div>
    <ul>
        <li><a href="<?php echo $vars['url']; ?>mod/dashboard/"><?php echo elgg_echo('dashboard'); ?></a></li>
        <li><a href=""><?php echo elgg_echo('profile'); ?></a></li>
        <li><a href=""><?php echo elgg_echo('account'); ?></a></li>
        <li><a href="<?php echo $vars['url']; ?>action/logout"><?php echo elgg_echo('logout'); ?></a></li>
    </ul>
</div>
<?php
    }
?>
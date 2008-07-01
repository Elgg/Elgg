<?php

	/**
	 * Elgg profile icon hover over: actions
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

	if (isloggedin()) {
		if ($_SESSION['user']->getGUID() != $vars['entity']->getGUID()) {
			if ($vars['entity']->isFriend()) {
				echo "<p class=\"user_menu_removefriend\"><a href=\"{$vars['url']}action/friends/remove?friend={$vars['entity']->getGUID()}\">" . elgg_echo("friend:remove") . "</a></p>";
			} else {
				echo "<p><a href=\"{$vars['url']}action/friends/add?friend={$vars['entity']->getGUID()}\">" . elgg_echo("friend:add") . "</a></p>";
			}
		}
	}
	
	if (isadminloggedin()){
		if ($_SESSION['user']->getGUID() != $vars['entity']->guid){
?>				
				
				<p class="user_menu_banuser"><a href="<?php echo $vars['url']; ?>actions/admin/user/ban?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("ban"); ?></a></p>
				
				<p class="user_menu_delete"><a href="<?php echo $vars['url']; ?>actions/admin/user/delete?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("delete"); ?></a></p>
				
				<p class="user_menu_resetpassword"><a href="<?php echo $vars['url']; ?>actions/admin/user/resetpassword?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("resetpassword"); ?></a></p>
				
				<?php if (!$vars['entity']->admin) { ?>
					<p class="user_menu_makeadmin"><a href="<?php echo $vars['url']; ?>actions/admin/user/makeadmin?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("makeadmin"); ?></a></p>
				<?php } ?>
					
<?php 
		}
	}
?>
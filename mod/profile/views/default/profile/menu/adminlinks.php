<?php
	/**
	 * Profile admin context links
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

			// TODO: Add admin console options here
			if (isadminloggedin()){
				if ($_SESSION['id']!=$vars['entity']->guid){
?>
				<p class="user_menu_admin">
				<a href="<?php echo $vars['url']; ?>actions/admin/user/ban?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("ban"); ?></a>
				<a href="<?php echo $vars['url']; ?>actions/admin/user/delete?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("delete"); ?></a>				
				<a href="<?php echo $vars['url']; ?>actions/admin/user/resetpassword?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("resetpassword"); ?></a>				
				<?php if (!$vars['entity']->admin) { ?><a href="<?php echo $vars['url']; ?>actions/admin/user/makeadmin?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("makeadmin"); ?></a> <?php } ?>
				</p>
				
<?php 
				}
			}
?>
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
					
					$ts = time();
					$token = generate_action_token($ts);
					
?>
				<a href="<?php echo $vars['url']; ?>pg/settings/user/<?php echo $vars['entity']->username; ?>/"><?php echo elgg_echo('profile:editdetails'); ?></a>
				<?php 
				if ($vars['entity']->isEnabled()) {
					?><a href="<?php echo $vars['url']; ?>actions/admin/user/ban?guid=<?php echo $vars['entity']->guid; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("ban"); ?></a><?php
				} else { 
					?><a href="<?php echo $vars['url']; ?>actions/admin/user/unban?guid=<?php echo $vars['entity']->guid; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("unban"); ?></a><?php 
				}
				?><a href="<?php echo $vars['url']; ?>actions/admin/user/delete?guid=<?php echo $vars['entity']->guid; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("delete"); ?></a>				
				<a href="<?php echo $vars['url']; ?>actions/admin/user/resetpassword?guid=<?php echo $vars['entity']->guid; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("resetpassword"); ?></a>				
				<?php if (!$vars['entity']->admin) { ?><a href="<?php echo $vars['url']; ?>actions/admin/user/makeadmin?guid=<?php echo $vars['entity']->guid; ?>&__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>"><?php echo elgg_echo("makeadmin"); ?></a> <?php } ?>
				
<?php 
				}
			}
?>
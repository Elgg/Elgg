<?php
	/**
	 * Profile admin context links
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

			if (isadminloggedin()){
				if ($_SESSION['id']!=$vars['entity']->guid){
					
					$ts = time();
					$token = generate_action_token($ts);
					
?>
				<a href="<?php echo $vars['url']; ?>pg/settings/user/<?php echo $vars['entity']->username; ?>/"><?php echo elgg_echo('profile:editdetails'); ?></a>
				<?php 
				if (!$vars['entity']->isBanned()) {
					echo elgg_view('output/confirmlink', array('text' => elgg_echo("ban"), 'href' => "{$vars['url']}action/admin/user/ban?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
				} else {
					echo elgg_view('output/confirmlink', array('text' => elgg_echo("unban"), 'href' => "{$vars['url']}action/admin/user/unban?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts")); 
				}
				
				echo elgg_view('output/confirmlink', array('text' => elgg_echo("delete"), 'href' => "{$vars['url']}action/admin/user/delete?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));

				echo elgg_view('output/confirmlink', array('text' => elgg_echo("resetpassword"), 'href' => "{$vars['url']}action/admin/user/resetpassword?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
				
				if (!$vars['entity']->admin) { 
					echo elgg_view('output/confirmlink', array('text' => elgg_echo("makeadmin"), 'href' => "{$vars['url']}action/admin/user/makeadmin?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
				} else {
					echo elgg_view('output/confirmlink', array('text' => elgg_echo("removeadmin"), 'href' => "{$vars['url']}action/admin/user/removeadmin?guid={$vars['entity']->guid}&__elgg_token=$token&__elgg_ts=$ts"));
				}
			}
		}
?>
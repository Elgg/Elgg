<?php
/**
 * Profile admin context links
 * 
 * @package ElggProfile
 * 
 * @uses $vars['entity'] The user entity
 */

if (isadminloggedin()){
	if (get_loggedin_userid()!=$vars['entity']->guid){
?>
		<a href="<?php echo elgg_get_site_url(); ?>pg/settings/user/<?php echo $vars['entity']->username; ?>/"><?php echo elgg_echo('profile:editdetails'); ?></a>
<?php 
			if (!$vars['entity']->isBanned()) {
				echo elgg_view('output/confirmlink', array('text' => elgg_echo("ban"), 'href' => "action/admin/user/ban?guid={$vars['entity']->guid}"));
			} else {
				echo elgg_view('output/confirmlink', array('text' => elgg_echo("unban"), 'href' => "action/admin/user/unban?guid={$vars['entity']->guid}")); 
			}
			echo elgg_view('output/confirmlink', array('text' => elgg_echo("delete"), 'href' => "action/admin/user/delete?guid={$vars['entity']->guid}"));
			echo elgg_view('output/confirmlink', array('text' => elgg_echo("resetpassword"), 'href' => "action/admin/user/resetpassword?guid={$vars['entity']->guid}"));
			if (!$vars['entity']->isAdmin()) { 
				echo elgg_view('output/confirmlink', array('text' => elgg_echo("makeadmin"), 'href' => "action/admin/user/makeadmin?guid={$vars['entity']->guid}"));
			} else {
				echo elgg_view('output/confirmlink', array('text' => elgg_echo("removeadmin"), 'href' => "action/admin/user/removeadmin?guid={$vars['entity']->guid}"));
			}
		}
	}

<?php
/** 
 * This is the admin menu that appears on a user's profile page
 **/
global $CONFIG;

if (isadminloggedin()){
	if (get_loggedin_userid()!=elgg_get_page_owner_guid()){
		$user = get_user(elgg_get_page_owner_guid());
		$url = elgg_get_site_url();
		$ts = time();
		$token = generate_action_token($ts);

?>
<div class="owner_block_links clearfloat">
<ul class="admin_menu">
<li><a href="#" onclick="elgg_slide_toggle(this,'.owner_block_links','.admin_menu_options');">Admin options&hellip;</a>
	
	<ul class="admin_menu_options">
	<li><a href="<?php echo $url; ?>pg/settings/user/<?php echo $user->username; ?>/"><?php echo elgg_echo('profile:editdetails'); ?></a></li>	
	<?php
		if (!$user->isBanned()) {
			echo "<li>".elgg_view('output/confirmlink', array('text' => elgg_echo("ban"), 'href' => "{$url}action/admin/user/ban?guid={$user->guid}&__elgg_token=$token&__elgg_ts=$ts")) . "</li>";
		} else {
			echo "<li>".elgg_view('output/confirmlink', array('text' => elgg_echo("unban"), 'href' => "{$url}action/admin/user/unban?guid={$user->guid}&__elgg_token=$token&__elgg_ts=$ts")) . "</li>"; 
		}
		echo "<li>".elgg_view('output/confirmlink', array('text' => elgg_echo("delete"), 'href' => "{$url}action/admin/user/delete?guid={$user->guid}&__elgg_token=$token&__elgg_ts=$ts")) . "</li>";
		echo "<li>".elgg_view('output/confirmlink', array('text' => elgg_echo("resetpassword"), 'href' => "{$url}action/admin/user/resetpassword?guid={$user->guid}&__elgg_token=$token&__elgg_ts=$ts")) . "</li>";
		if (!$vars['entity']->admin) { 
			echo "<li>".elgg_view('output/confirmlink', array('text' => elgg_echo("makeadmin"), 'href' => "{$url}action/admin/user/makeadmin?guid={$user->guid}&__elgg_token=$token&__elgg_ts=$ts")) . "</li>";
		} else {
			echo "<li>".elgg_view('output/confirmlink', array('text' => elgg_echo("removeadmin"), 'href' => "{$url}action/admin/user/removeadmin?guid={$user->guid}&__elgg_token=$token&__elgg_ts=$ts")) . "</li>";
		}
	?>
	</ul>
</li>	
</ul>	
</div>
<?php
	}
}
?>

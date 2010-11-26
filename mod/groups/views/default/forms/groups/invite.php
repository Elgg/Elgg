<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */

	$group = $vars['entity'];
	$owner = get_entity($vars['entity']->owner_guid);
	$forward_url = $group->getURL();
	
	
?>
<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/groups/invite" method="post">

	<?php
	echo elgg_view('input/securitytoken');

	if ($friends = get_loggedin_user()->getFriends('', 0)) {
		echo elgg_view('friends/picker',array('entities' => $friends, 'internalname' => 'user_guid', 'highlight' => 'all'));	
	}
	
	?>
	<input type="hidden" name="forward_url" value="<?php echo $forward_url; ?>" />
	<input type="hidden" name="group_guid" value="<?php echo $group->guid; ?>" />
	<input type="submit" value="<?php echo elgg_echo('invite'); ?>" />
</form>
</div>

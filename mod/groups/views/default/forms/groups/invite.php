<?php
	/**
	 * Elgg groups plugin
	 *
	 * @package ElggGroups
	 */

	$group = $vars['entity'];
	$owner = get_entity($vars['entity']->owner_guid);
	$forward_url = $group->getURL();
	$friends = elgg_get_logged_in_user_entity()->getFriends('', 0);

if ($friends) {
?>
<form action="<?php echo elgg_get_site_url(); ?>action/groups/invite" method="post" id="invite_to_group" class="margin-top">

<?php
	echo elgg_view('input/securitytoken');

	echo elgg_view('core/friends/picker',array('entities' => $friends, 'name' => 'user_guid', 'highlight' => 'all'));
?>
	<input type="hidden" name="forward_url" value="<?php echo $forward_url; ?>" />
	<input type="hidden" name="group_guid" value="<?php echo $group->guid; ?>" />
	<input type="submit" value="<?php echo elgg_echo('invite'); ?>" />
</form>
<?php
} else {
	echo elgg_echo('groups:nofriendsatall');
}
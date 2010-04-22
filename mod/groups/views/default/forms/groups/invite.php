<?php
	/**
	 * Elgg groups plugin
	 *
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	$group = $vars['entity'];
	$owner = get_entity($vars['entity']->owner_guid);
	$forward_url = $group->getURL();

	$friends = elgg_get_entities_from_relationship($options);
	$options = array(
		'relationship' => 'friend',
		'relationship_guid' => get_loggedin_user()->guid,
		'type' => 'user',
		'limit' => 9999
	);
if ($friends) {
?>
<form action="<?php echo $vars['url']; ?>action/groups/invite" method="post" class="margin_top">

	<?php
	echo elgg_view('input/securitytoken');

	echo elgg_view('friends/picker',array('entities' => $friends, 'internalname' => 'user_guid', 'highlight' => 'all'));

	?>
	<input type="hidden" name="forward_url" value="<?php echo $forward_url; ?>" />
	<input type="hidden" name="group_guid" value="<?php echo $group->guid; ?>" />
	<input type="submit" value="<?php echo elgg_echo('invite'); ?>" />
</form>
<?php
} else {
	echo elgg_echo('groups:nofriendsatall');
}
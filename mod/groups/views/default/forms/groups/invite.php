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
	
	
?>
<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/groups/invite" method="post">

	<?php
	echo elgg_view('input/securitytoken');

	if ($friends = get_entities_from_relationship('friend',$_SESSION['guid'],false,'user','',0,'',9999)) {
		echo elgg_view('friends/picker',array('entities' => $friends, 'internalname' => 'user_guid', 'highlight' => 'all'));	
	}
		// echo elgg_view('sharing/invite',array('shares' => $shares, 'owner' => $owner, 'group' => $group));
	
	?>
	<input type="hidden" name="forward_url" value="<?php echo $forward_url; ?>" />
	<input type="hidden" name="group_guid" value="<?php echo $group->guid; ?>" />
	<input type="submit" value="<?php echo elgg_echo('invite'); ?>" />
</form>
</div>

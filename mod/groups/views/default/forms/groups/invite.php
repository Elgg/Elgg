<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$group = $vars['entity'];
	$owner = get_entity($vars['entity']->owner_guid);
	$forward_url = $group->getURL();
	
	
?>
<h2><?php echo sprintf(elgg_echo('groups:inviteto'), $group->name); ?></h2>
<form action="<?php echo $vars['url']; ?>action/groups/addtogroup" method="post">

	<?php

		echo elgg_view('sharing/invite',array('shares' => $shares, 'owner' => $owner, 'group' => $group));
	
	?>
	<input type="hidden" name="forward_url" value="<?php echo $forward_url; ?>" />
	<input type="hidden" name="group_guid" value="<?php echo $group->guid; ?>" />
	<input type="submit" value="<?php echo elgg_echo('invite'); ?>" />
</form>
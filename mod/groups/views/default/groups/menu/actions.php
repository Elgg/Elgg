<?php
	/**
	 * Elgg group actions
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	if (isloggedin()) {
		
		if ($vars['entity']->isMember($_SESSION['user']))
		{
			?><p><a href="<?php echo $vars['url']; ?>action/groups/leave?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("groups:leave"); ?></a></p><?php 	
		}
		else
		{
			if ($vars['entity']->access_id == 2)
			{
				?><p><a href="<?php echo $vars['url']; ?>action/groups/join?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("groups:join"); ?></a></p><?php		
			}
			else
			{
				?><p><a href="<?php echo $vars['url']; ?>action/groups/joinrequest?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("groups:joinrequest"); ?></a></p><?php		
			}	
		}
		
		if ($vars['entity']->canEdit()) 
		{
			?><p><a href="<?php echo $vars['url']; ?>mod/groups/invite.php?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("groups:invite"); ?></a></p><?php 		
		}
	}
?>
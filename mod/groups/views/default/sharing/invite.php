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

	$owner = $vars['owner'];
	$group = $vars['group'];
	
	if ($friends = get_entities_from_relationship('friend',$owner->getGUID(),false,'user','')) {
		
		foreach($friends as $friend) {
			
			if (!$group->isMember($friend))
			{
				$label = elgg_view("profile/icon",array('entity' => $friend, 'size' => 'tiny'));
				$label .= "{$friend->name}"; 
				$options[$label] = $friend->getGUID();
			}
		}
		
		if ($options)
		{
			echo elgg_view('input/checkboxes',array(
			
				'internalname' => 'user_guid',
				'options' => $options,
			
			));
		}
		else
		{
			echo elgg_echo('groups:nofriends');
		}
	}
?>
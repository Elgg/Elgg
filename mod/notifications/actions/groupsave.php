<?php

	/**
	 * Elgg notifications group save
	 * 
	 * @package ElggNotifications
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Restrict to logged in users
		gatekeeper();
		
	// Load important global vars
		global $SESSION;
		global $NOTIFICATION_HANDLERS;

	// Get group memberships and condense them down to an array of guids
		$groups = array();
		if ($groupmemberships = get_entities_from_relationship('member',$_SESSION['user']->guid,false,'group','',0,'',9999)) {
			foreach($groupmemberships as $groupmembership)
				$groups[] = $groupmembership->guid;
		}		
		
		foreach($NOTIFICATION_HANDLERS as $method => $foo) {
			$subscriptions[$method] = get_input($method.'subscriptions');
			$personal[$method] = get_input($method.'personal');
			$collections[$method] = get_input($method.'collections');
			if (!empty($groups))
				foreach($groups as $group)
					if (in_array($group,$subscriptions[$method])) {
						add_entity_relationship($SESSION['user']->guid,'notify'.$method,$group);
					} else {
						remove_entity_relationship($SESSION['user']->guid,'notify'.$method,$group);
					}
		}
				
		system_message(elgg_echo('notifications:subscriptions:success'));
		
		forward($_SERVER['HTTP_REFERER']);

?>
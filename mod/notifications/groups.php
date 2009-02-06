<?php

	/**
	 * Elgg notifications plugin group index
	 * 
	 * @package ElggNotifications
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load Elgg framework
		require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
		
	// Ensure only logged-in users can see this page
		gatekeeper();
		
	// Set the context to settings
		set_context('settings');
		
	// Get the form
		global $SESSION;
		$people = array();
		if ($groups_ents = get_entities_from_relationship('notify',$SESSION['user']->guid,false,'group','',0,'',99999)) {
			foreach($groups_ents as $ent)
				$groups[] = $ent->guid;
		}
		
		$groupmemberships = get_entities_from_relationship('member',$_SESSION['user']->guid,false,'group','',0,'',9999);
		
		$body = elgg_view('input/form',array(
			'body' => elgg_view('notifications/subscriptions/groupsform',array(
							'groupsubs' => $groups,
							'groups' => $groupmemberships
						)),
			'method' => 'post',
			'action' => ''
		));
		
	// Insert it into the correct canvas layout
		$body = elgg_view_layout('two_column_left_sidebar','',$body);
		
	// Draw the page
		echo page_draw(elgg_echo('notifications:subscriptions:groups'),$body);
		
?>
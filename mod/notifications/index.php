<?php

	/**
	 * Elgg notifications plugin index
	 * 
	 * @package ElggNotifications
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
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
		if ($people_ents = elgg_get_entities_from_relationship(array('relationship' => 'notify', 'relationship_guid' => $SESSION['user']->guid, 'types' => 'user', 'limit' => 99999))) {
			foreach($people_ents as $ent)
				$people[] = $ent->guid;
		}
		$body = elgg_view('notifications/subscriptions/form',array(
							'people' => $people
						));
		
	// Insert it into the correct canvas layout
		$body = elgg_view_layout('two_column_left_sidebar','',$body);
		
	// Draw the page
		page_draw(elgg_echo('notifications:subscriptions:changesettings'),$body);
		
?>
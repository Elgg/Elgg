<?php

	/**
	 * Elgg friends picker callback
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

	// Get callback type (list or picker)
		$type = get_input('type','picker');
		
	// Get list of members if applicable
		$members = get_input('members','');
		if (!empty($members)) {
			$members = explode(',',$members);
		} else {
			$members = array();
		}
		
		$friendspicker = (int) get_input('friendspicker',0);
		
	// Get page owner (bomb out if there isn't one)
		$pageowner = page_owner_entity();
		if (!$pageowner) { forward(); exit; }
		
	// Depending on the view type, launch a different view
		switch($type) {
			
			case 'list':		
								$content = elgg_view('friends/tablelist',array('entities' => $members));
								break;
			default:			$friends = $pageowner->getFriends('',9999);
								$content = elgg_view('friends/picker',array(
												'entities' => $friends,
												'value' => $members,
												'callback' => true,
												'friendspicker' => $friendspicker,
																			));
								break;
			
		}
		
	// Output the content
		echo $content;
		
?>
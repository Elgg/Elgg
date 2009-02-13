<?php

	/**
	 * Elgg river dashboard plugin index page
	 * 
	 * @package ElggRiverDash
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

		gatekeeper();
		
		$subtype = get_input('content','');
		$orient = get_input('display');
		
		if ($subtype == 'all') $subtype = '';
		
		switch($orient) {
			case 'mine':
							$subject_guid = $_SESSION['user']->guid;
							$relationship_type = ''; 
							break;
			case 'friends':	$subject_guid = $_SESSION['user']->guid;
							$relationship_type = 'friend';
							break;
			default:		$subject_guid = 0;
							$relationship_type = '';
							break;
		}

		$river = elgg_view_river_items($subject_guid, 0, $relationship_type, '', $subtype, '');
		$body = elgg_view('riverdashboard/nav',array(
														'subtype' => $subtype,
														'orient' => $orient 
													));
		$body .= $river;
				
		echo page_draw(elgg_echo('dashboard'),elgg_view_layout('two_column_left_sidebar','',$body));

?>
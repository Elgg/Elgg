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
		
		$content = get_input('content','');
		$content = explode(',',$content);
		$type = $content[0];
		$subtype = $content[1];
		$orient = get_input('display');
		
		if ($type == 'all') {
			$type = '';
			$subtype = '';
		}

		//set a view for the wire to extend
		$area1 = elgg_view("activity/thewire");

		//set a view to display newest members
		$area1 .= elgg_view("riverdashboard/newestmembers");

		//set a view to display a welcome message
		$body = elgg_view("riverdashboard/welcome");

		//set a view to display a site wide message
		$body .= elgg_view("riverdashboard/sitemessage");

		
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

		$river = elgg_view_river_items($subject_guid, 0, $relationship_type, $type, $subtype, '') . "</div>";
		$body .= elgg_view('riverdashboard/nav',array(
														'type' => $type,
														'subtype' => $subtype,
														'orient' => $orient 
													));
		$body .= $river;
				
		echo page_draw(elgg_echo('dashboard'),elgg_view_layout('sidebar_boxes',$area1,$body));

?>
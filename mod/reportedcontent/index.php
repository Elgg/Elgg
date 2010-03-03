<?php
	/**
	 * Elgg Reported content
	 * 
	 * @package ElggReportedContent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	admin_gatekeeper();
	set_context('admin');
	// Set admin user for user block
		set_page_owner($_SESSION['guid']);

		
	$title = elgg_view_title(elgg_echo('reportedcontent'));
	
	$reported = elgg_get_entities(array('types' => 'object', 'subtypes' => 'reported_content', 'limit' => 9999));
	
	$area2 = elgg_view("reportedcontent/listing", array('entity' => $reported));
	
	if(!$reported)  
	    $reported = elgg_echo("reportedcontent:none");
		
// Display main admin menu
	page_draw(elgg_echo('reportedcontent'),elgg_view_layout("two_column_left_sidebar", '', $title . $area2));

?>
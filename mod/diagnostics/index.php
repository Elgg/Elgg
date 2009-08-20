<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 * @author Curverider Ltd
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	admin_gatekeeper();
	set_context('admin');
	// Set admin user for user block
		set_page_owner($_SESSION['guid']);

	$title = elgg_view_title(elgg_echo('diagnostics'));
	
	$body = elgg_view('page_elements/contentwrapper', array('body' => 
		elgg_echo('diagnostics:description') . elgg_view('diagnostics/forms/download'))
	);
	
	
	page_draw(elgg_echo('diagnostics'),elgg_view_layout("two_column_left_sidebar", '', $title . $body));
?>
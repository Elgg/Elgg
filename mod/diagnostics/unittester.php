<?php
	/**
	 * Elgg diagnostics - unit tester
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

	$title = elgg_view_title(elgg_echo('diagnostics:unittester'));
	
	$tests = get_available_tests();
	$test_body = "";
	if ($tests)
	{
		foreach ($tests as $func => $desc)
			$test_body .= elgg_view('diagnostics/test', array('function' => $func, 'description' => $desc));
	}
	else
		$test_body = elgg_echo('diagnostics:unittester:notests');
	
	$body = elgg_view('page_elements/contentwrapper', array('body' => 
		elgg_echo('diagnostics:unittester:description') .  
		elgg_view('diagnostics/runalltests')
		) 
	);
	
	$body .= elgg_view('page_elements/contentwrapper', array('body' => 
		$test_body ) 
	);
	
	
	page_draw(elgg_echo('diagnostics:unittester'),elgg_view_layout("two_column_left_sidebar", '', $title . $body));
?>
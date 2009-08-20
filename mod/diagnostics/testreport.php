<?php
	/**
	 * Elgg diagnostics - test report
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
		
	// Which test are we executing?
	$test_func = get_input('test_func');

	$title_txt = sprintf(elgg_echo('diagnostics:unittester:report'), $test_func ? $testfunc : elgg_echo('diagnostics:test:executeall'));
		
	$title = elgg_view_title();
	
	$result = null;
	if ($test_func)
		$result = array(execute_elgg_test($test_func));
	else
		$result = execute_elgg_tests();
		
	if ($result)
	{
		foreach ($result as $r)
			$body .= elgg_view('page_elements/contentwrapper', array('body' =>
				elgg_view('diagnostics/testresult', array('function' => $r['function'], 'result' => $r['result']))
			));
	}
	else
		$body = elgg_view('page_elements/contentwrapper', array('body' => 
			elgg_echo('diagnostics:unittester:testnotfound' ) 
		));
	
	page_draw($title_txt, elgg_view_layout("two_column_left_sidebar", '', $title . $body));
?>
<?php

/**
	 * Elgg invite page
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
	
	gatekeeper();
	
	set_context('friends');
	set_page_owner($_SESSION['guid']);
	
	$body = elgg_view('invitefriends/form');
	$body = elgg_view_layout('two_column_left_sidebar','',$body);

	page_draw(elgg_echo('friends:invite'),$body);
	
?>
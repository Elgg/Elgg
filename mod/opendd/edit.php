<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$form = elgg_view('forms/opendd/subscribe', array('entity' => get_entity(get_input('feed_guid'))));
	
	$body = elgg_view_layout('one_column',$form);
	
	page_draw(elgg_echo("opendd:edit"), $body);
?>
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

	$feeds = opendd_get_feed_urls(page_owner());
	
	
	echo elgg_view('input/checkboxes', array(
		'internalname' => 'params[feeds]',
		'options' => $feeds,
		'value' => $vars['entity']->feeds	
	));
?>
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

	$icon = elgg_view(
			"opendd/icon", array(
									'entity' => $vars['entity'],
									'size' => 'large',
								  )
		);

	$info .= "<p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->feedurl . "</a></b></p>";
	
	// num users, last activity, owner etc
	
	
	echo elgg_view('search/gallery_listing',array('icon' => $icon, 'info' => $info));
?>
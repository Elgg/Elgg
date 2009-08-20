<?php

	/**
	 * Elgg friends of page
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

		if (!$owner = page_owner_entity()) {
			gatekeeper();
			set_page_owner($_SESSION['user']->getGUID());
			$owner = $_SESSION['user'];
		}
		
		$area1 = elgg_view_title(elgg_echo('friends:of'));
		$area2 = list_entities_from_relationship('friend',$owner->getGUID(),true,'user','',0,10,false);
		$body = elgg_view_layout('two_column_left_sidebar', '', $area1 . $area2);
		
		page_draw(sprintf(elgg_echo("friends:of:owned"),$owner->name),$body);

?>
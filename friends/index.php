<?php

	/**
	 * Elgg friends page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

		if (!$owner = page_owner_entity()) {
			gatekeeper();
			set_page_owner($_SESSION['user']->getGUID());
			$owner = $_SESSION['user'];
		}
		
		$area1 = elgg_view_title(elgg_echo('Friends'));
		$area1 .= list_entities_from_relationship('friend',$owner->getGUID(),false,'user','',0,10,false);
		$body = elgg_view_layout('one_column',$area1);
		
		echo page_draw(sprintf(elgg_echo("friends:owned"),$owner->name),$body);

?>
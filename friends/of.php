<?php

	/**
	 * Elgg friends of page
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
			$owner = $_SESSION['user'];
		}
		
		$offset = (int) get_input('offset');
		
		if ($friends = $owner->getFriendsOf("",50,$offset)) {
			
			$body = elgg_view("friends/list",array('friends' => $friends));
			
		} else {
			
		if (page_owner() != $_SESSION['guid']) {
				$body = elgg_echo("friends:of:none");
			} else {
				$body = elgg_echo("friends:of:none:you");
			}
			
		}
		
		echo page_draw(sprintf(elgg_echo("friends:of:owned"),$owner->name),$body);

?>
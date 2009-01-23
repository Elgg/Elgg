<?php
	/**
	 * Elgg user settings system index
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Get the Elgg framework
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
		
		if (!page_owner())
			set_page_owner($_SESSION['guid']);
			
	// Make sure we don't open a security hole ...
		if ((!page_owner_entity()) || (!page_owner_entity()->canEdit())) {
			set_page_owner($_SESSION['guid']);
		}
		
	// Forward to the user settings
		forward('pg/settings/user/' . page_owner_entity()->username . "/");
		
?>
<?php
	/**
	 * Email user validation plugin.
	 * Non-admin or admin created accounts are invalid until their email address is confirmed. 
	 * 
	 * @package ElggUserValidationByEmail
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	function uservalidationbyemail_init()
	{
		// Register actions
		
		// Register hook listening to new users.
	}

	// create - if not admin & if not admin logged in then request email validation
	
	// Initialise
	register_elgg_event_handler('init','system','uservalidationbyemail_init');
?>
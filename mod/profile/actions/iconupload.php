<?php

	/**
	 * Elgg profile plugin upload new user icon action
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// If we were given a correct icon
		if (
				isloggedin()
			) {
				
				$small = get_resized_image_from_uploaded_file('profileicon',50,50);
				$medium = get_resized_image_from_uploaded_file('profileicon',100,100);
				$large = get_resized_image_from_uploaded_file('profileicon',300,300);
				
				if ($small !== false
					&& $medium !== false
					&& $large !== false) {
				
					$filehandler = new ElggFile();
					$filehandler->setFilename($_SESSION['user']->username . "large.jpg");
					$filehandler->open("write");
					$filehandler->write($large);
					$filehandler->close();
					$filehandler->setFilename($_SESSION['user']->username . "medium.jpg");
					$filehandler->open("write");
					$filehandler->write($medium);
					$filehandler->close();
					$filehandler->setFilename($_SESSION['user']->username . "small.jpg");
					$filehandler->open("write");
					$filehandler->write($small);
					$filehandler->close();
					
					system_message(elgg_echo("profile:icon:uploaded"));
				
				} else {
					system_message(elgg_echo("profile:icon:notfound"));					
				}
				
			} else {
				
				system_message(elgg_echo("profile:icon:notfound"));
				
			}
			
		if (isloggedin()) forward($_SESSION['user']->getURL());

?>
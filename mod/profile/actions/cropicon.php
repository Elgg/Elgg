<?php

	/**
	 * Elgg profile plugin upload new user icon action
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	gatekeeper();
	action_gatekeeper();

		$x1 = (int) get_input('x_1',0);
		$y1 = (int) get_input('y_1',0);
		$x2 = (int) get_input('x_2',0);
		$y2 = (int) get_input('y_2',0);
		
		$user = page_owner_entity();
		
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $user->getGUID();
		$filehandler->setFilename("profile/" . $user->username . "master" . ".jpg");
		$filename = $filehandler->getFilenameOnFilestore();
		
		$topbar = get_resized_image_from_existing_file($filename,16,16, true, $x1, $y1, $x2, $y2);
		$tiny = get_resized_image_from_existing_file($filename,25,25, true, $x1, $y1, $x2, $y2);
		$small = get_resized_image_from_existing_file($filename,40,40, true, $x1, $y1, $x2, $y2);
		$medium = get_resized_image_from_existing_file($filename,100,100, true, $x1, $y1, $x2, $y2);
		
		if ($small !== false
					&& $medium !== false
					&& $tiny !== false) {
				
					$filehandler = new ElggFile();
					$filehandler->owner_guid = $user->getGUID();
					$filehandler->setFilename("profile/" .  $user->username . "medium.jpg");
					$filehandler->open("write");
					$filehandler->write($medium);
					$filehandler->close();
					$filehandler->setFilename("profile/" .  $user->username . "small.jpg");
					$filehandler->open("write");
					$filehandler->write($small);
					$filehandler->close();
					$filehandler->setFilename("profile/" .  $user->username . "tiny.jpg");
					$filehandler->open("write");
					$filehandler->write($tiny);
					$filehandler->close();
					$filehandler->setFilename("profile/" .  $user->username . "topbar.jpg");
					$filehandler->open("write");
					$filehandler->write($topbar);
					$filehandler->close();
					
					$user->x1 = $x1;
					$user->x2 = $x2;
					$user->y1 = $y1;
					$user->y2 = $y2;
					
					 $user->icontime = time();
					
					system_message(elgg_echo("profile:icon:uploaded"));
				
				} else {
					system_message(elgg_echo("profile:icon:notfound"));					
				}
			
	    //forward the user back to the upload page to crop
	    
	    $url = $vars['url'] . "pg/profile/{$user->username}/editicon/";
			
		if (isloggedin()) forward($url);

?>
<?php

	/**
	 * Elgg profile plugin upload new user icon action
	 * 
	 * @package ElggProfile
	 */

	gatekeeper();

	$x1 = (int) get_input('x_1',0);
	$y1 = (int) get_input('y_1',0);
	$x2 = (int) get_input('x_2',0);
	$y2 = (int) get_input('y_2',0);
	
	// username is set in form which ensures the page owner is set
	$user = page_owner_entity();
	
	if (!$user || !$user->canEdit()) {
		register_error(elgg_echo("profile:icon:noaccess"));
		forward();
	}
		
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $user->getGUID();
	$filehandler->setFilename("profile/" . $user->guid . "master" . ".jpg");
	$filename = $filehandler->getFilenameOnFilestore();
	
	$topbar = get_resized_image_from_existing_file($filename,16,16, true, $x1, $y1, $x2, $y2, TRUE);
	$tiny = get_resized_image_from_existing_file($filename,25,25, true, $x1, $y1, $x2, $y2, TRUE);
	$small = get_resized_image_from_existing_file($filename,40,40, true, $x1, $y1, $x2, $y2, TRUE);
	$medium = get_resized_image_from_existing_file($filename,100,100, true, $x1, $y1, $x2, $y2, TRUE);
	$large = get_resized_image_from_existing_file($filename,200,200, true, $x1, $y1, $x2, $y2);
	
	if ($topbar !== false &&
		$tiny   !== false &&
		$small  !== false &&
		$medium !== false &&
		$large  !== false ) {
			
				$filehandler = new ElggFile();
				$filehandler->owner_guid = $user->getGUID();
				$filehandler->setFilename("profile/" .  $user->guid . "large.jpg");
				$filehandler->open("write");
				$filehandler->write($large);
				$filehandler->close();
				$filehandler->setFilename("profile/" .  $user->guid . "medium.jpg");
				$filehandler->open("write");
				$filehandler->write($medium);
				$filehandler->close();
				$filehandler->setFilename("profile/" .  $user->guid . "small.jpg");
				$filehandler->open("write");
				$filehandler->write($small);
				$filehandler->close();
				$filehandler->setFilename("profile/" .  $user->guid . "tiny.jpg");
				$filehandler->open("write");
				$filehandler->write($tiny);
				$filehandler->close();
				$filehandler->setFilename("profile/" .  $user->guid . "topbar.jpg");
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
				register_error(elgg_echo("profile:icon:notfound"));
			}
		
    //forward the user back to the upload page to crop
    
    $url = $vars['url'] . "pg/profile/{$user->username}/editicon/";
		
	if (isloggedin()) forward($url);

?>

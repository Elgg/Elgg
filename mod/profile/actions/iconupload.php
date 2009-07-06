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
	
	$user = page_owner_entity();
	if (!$user)
		$user = $_SESSION['user'];
		
	// If we were given a correct icon
		if (
				(isloggedin()) &&
				($user) &&
				($user->canEdit())
			) {
				
				
				$topbar = get_resized_image_from_uploaded_file('profileicon',16,16, true);
				$tiny = get_resized_image_from_uploaded_file('profileicon',25,25, true);
				$small = get_resized_image_from_uploaded_file('profileicon',40,40, true);
				$medium = get_resized_image_from_uploaded_file('profileicon',100,100, true);
				$large = get_resized_image_from_uploaded_file('profileicon',200,200);
				$master = get_resized_image_from_uploaded_file('profileicon',550,550);
				
				if ($small !== false
					&& $medium !== false
					&& $large !== false
					&& $tiny !== false) {
				
					$filehandler = new ElggFile();
					$filehandler->owner_guid = $user->getGUID();
					$filehandler->setFilename("profile/" . $user->username . "large.jpg");
					$filehandler->open("write");
					$filehandler->write($large);
					$filehandler->close();
					$filehandler->setFilename("profile/" . $user->username . "medium.jpg");
					$filehandler->open("write");
					$filehandler->write($medium);
					$filehandler->close();
					$filehandler->setFilename("profile/" . $user->username . "small.jpg");
					$filehandler->open("write");
					$filehandler->write($small);
					$filehandler->close();
					$filehandler->setFilename("profile/" . $user->username . "tiny.jpg");
					$filehandler->open("write");
					$filehandler->write($tiny);
					$filehandler->close();
					$filehandler->setFilename("profile/" . $user->username . "topbar.jpg");
					$filehandler->open("write");
					$filehandler->write($topbar);
					$filehandler->close();
					$filehandler->setFilename("profile/" . $user->username . "master.jpg");
					$filehandler->open("write");
                    $filehandler->write($master);
					$filehandler->close();
					
					$user->icontime = time();
					
					system_message(elgg_echo("profile:icon:uploaded"));
					
					trigger_elgg_event('profileiconupdate',$user->type,$user);
					
					//add to river
					add_to_river('river/user/default/profileiconupdate','update',$user->guid,$user->guid);
				
				} else {
					system_message(elgg_echo("profile:icon:notfound"));					
				}
				
			} else {
				
				system_message(elgg_echo("profile:icon:notfound"));
				
			}
			
	    //forward the user back to the upload page to crop
	    
	    $url = "pg/profile/{$user->username}/editicon/";
			
		if (isloggedin()) forward($url);

?>
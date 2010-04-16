<?php
/**
 * Elgg profile plugin upload new user icon action
 * 
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

gatekeeper();

$profile_username = get_input('username');
$profile_owner = get_user_by_username($profile_username);

if (!$profile_owner || !($profile_owner instanceof ElggUser) || !$profile_owner->canEdit()) {
	register_error(elgg_echo('profile:icon:fail'));
	forward($_SERVER['HTTP_REFERER']);
}

$x1 = (int) get_input('x_1',0);
$y1 = (int) get_input('y_1',0);
$x2 = (int) get_input('x_2',0);
$y2 = (int) get_input('y_2',0);

$filehandler = new ElggFile();
$filehandler->owner_guid = $profile_owner->getGUID();
$filehandler->setFilename("profile/" . $profile_owner->username . "master" . ".jpg");
$filename = $filehandler->getFilenameOnFilestore();
	
$topbar = get_resized_image_from_existing_file($filename, 16, 16, true, $x1, $y1, $x2, $y2, TRUE);
$tiny = get_resized_image_from_existing_file($filename, 25, 25, true, $x1, $y1, $x2, $y2, TRUE);
$small = get_resized_image_from_existing_file($filename, 40, 40, true, $x1, $y1, $x2, $y2, TRUE);
$medium = get_resized_image_from_existing_file($filename, 100, 100, true, $x1, $y1, $x2, $y2, TRUE);
	
if ($small !== FALSE && $medium !== FALSE && $tiny !== FALSE) {
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $profile_owner->getGUID();
	$filehandler->setFilename("profile/" .  $profile_owner->username . "medium.jpg");
	$filehandler->open("write");
	$filehandler->write($medium);
	$filehandler->close();
	$filehandler->setFilename("profile/" .  $profile_owner->username . "small.jpg");
	$filehandler->open("write");
	$filehandler->write($small);
	$filehandler->close();
	$filehandler->setFilename("profile/" .  $profile_owner->username . "tiny.jpg");
	$filehandler->open("write");
	$filehandler->write($tiny);
	$filehandler->close();
	$filehandler->setFilename("profile/" .  $profile_owner->username . "topbar.jpg");
	$filehandler->open("write");
	$filehandler->write($topbar);
	$filehandler->close();

	$profile_owner->x1 = $x1;
	$profile_owner->x2 = $x2;
	$profile_owner->y1 = $y1;
	$profile_owner->y2 = $y2;
	
	$profile_owner->icontime = time();

	system_message(elgg_echo("profile:icon:uploaded"));
} else {
	register_error(elgg_echo("profile:icon:notfound"));
}
		
//forward the user back to the upload page to crop
$url = "{$vars['url']}pg/profile/{$profile_owner->username}/edit/icon";

if (isloggedin()) {
	forward($url);
}

<?php
/**
 * Elgg profile plugin upload new user icon action
 *
 * @package ElggProfile
 */

$profile_username = get_input('username');
$profile_owner = get_user_by_username($profile_username);

if (!$profile_owner || !($profile_owner instanceof ElggUser) || !$profile_owner->canEdit()) {
	register_error(elgg_echo('profile:icon:fail'));
	forward(REFERER);
}

$profile_owner_guid = $profile_owner->getGUID();

//@todo make this configurable?
$icon_sizes = array(
	'topbar' => array('w'=>16, 'h'=>16, 'square'=>TRUE, 'upscale'=>TRUE),
	'tiny' => array('w'=>25, 'h'=>25, 'square'=>TRUE, 'upscale'=>TRUE),
	'small' => array('w'=>40, 'h'=>40, 'square'=>TRUE, 'upscale'=>TRUE),
	'medium' => array('w'=>100, 'h'=>100, 'square'=>TRUE, 'upscale'=>TRUE),
	'large' => array('w'=>200, 'h'=>200, 'square'=>FALSE, 'upscale'=>FALSE),
	'master' => array('w'=>550, 'h'=>550, 'square'=>FALSE, 'upscale'=>FALSE)
);

// get the images and save their file handlers into an array
// so we can do clean up if one fails.
$files = array();
foreach ($icon_sizes as $name => $size_info) {
	$resized = get_resized_image_from_uploaded_file('profileicon', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);

	if ($resized) {
		//@todo Make these actual entities.  See exts #348.
		$file = new ElggFile();
		$file->owner_guid = $profile_owner_guid;
		$file->setFilename("profile/{$profile_owner_guid}{$name}.jpg");
		$file->open('write');
		$file->write($resized);
		$file->close();
		$files[] = $file;
	} else {
		// cleanup on fail
		foreach ($files as $file) {
			$file->delete();
		}

		system_message(elgg_echo('profile:icon:notfound'));
		forward(REFERER);
	}
}

$profile_owner->icontime = time();
if (elgg_trigger_event('profileiconupdate', $profile_owner->type, $profile_owner)) {
	// pull this out into the river plugin.
	//add_to_river('river/user/default/profileiconupdate','update',$user->guid,$user->guid);
	system_message(elgg_echo("profile:icon:uploaded"));
}

//forward the user back to the upload page to crop
forward(REFERER);

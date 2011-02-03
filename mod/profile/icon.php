<?php
/**
* Elgg profile icon
*
* @package ElggProfile
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the owning user
$user = elgg_get_page_owner_entity();

// Get the size
$size = strtolower(get_input('size'));
if (!in_array($size,array('large','medium','small','tiny','master','topbar')))
	$size = "medium";

// If user doesn't exist, return default icon
if (!$user) {
	$path = elgg_view("icon/user/default/$size");
	header("Location: $path");
	exit;
}

// Try and get the icon
$filehandler = new ElggFile();
$filehandler->owner_guid = $user->getGUID();
$filehandler->setFilename("profile/" .  $user->getGUID() . $size . ".jpg");

$success = false;
if ($filehandler->open("read")) {
	if ($contents = $filehandler->read($filehandler->size())) {
		$success = true;
	}
}

if (!$success) {
	$path = elgg_view("icon/user/default/$size");
	header("Location: $path");
	exit;
}

header("Content-type: image/jpeg");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));

$splitString = str_split($contents, 1024);

foreach($splitString as $chunk) {
	echo $chunk;
}
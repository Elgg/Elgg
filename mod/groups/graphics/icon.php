<?php
	/**
	 * Icon display
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

	$group_guid = get_input('group_guid');
	$group = get_entity($group_guid);
	
	$size = strtolower(get_input('size'));
	if (!in_array($size,array('large','medium','small','tiny','master','topbar')))
		$size = "medium";
	
	$success = false;
	
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $group->owner_guid;
	$filehandler->setFilename("groups/" . $group->guid . $size . ".jpg");
	
	$success = false;
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->size())) {
			$success = true;
		} 
	}
	
	if (!$success) {
		$contents = @file_get_contents($CONFIG->pluginspath . "groups/graphics/default{$size}.jpg");
	}
	
	header("Content-type: image/jpeg");
	header('Expires: ' . date('r',time() + 864000));
	header("Pragma: public");
	header("Cache-Control: public");
	header("Content-Length: " . strlen($contents));
	echo $contents;
?>
<?php
	/**
	 * Elgg file browser uploader action
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	// Get variables
	$title = get_input("title");
	$desc = get_input("description");
	$tags = get_input("tags");
	
	// Extract file from, save to default filestore (for now)
	$prefix = "/file/";
	$file = new ElggFile();
	$result = $file->save();
	
	if ($result)
	{	
		$file->setFilename($prefix.$_FILES['upload']['name']);
		$file->setMimeType($_FILES['upload']['type']);
		
		$file->open("write");
		$file->write(get_uploaded_file('upload'));
		$file->close();
		
		$file->title = $title;
		$file->description = $desc;
		
		$result = $file->save();
		
		// Save tags
		$tags = explode(",", $tags);
		$file->tag = $tags;
	}
		
	if ($result)
		system_message(elgg_echo("file:saved"));
	else
		system_message(elgg_echo("file:uploadfailed"));
?>
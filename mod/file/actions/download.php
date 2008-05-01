<?php
	/**
	 * Elgg file browser download action.
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Get the guid
	$file_guid = get_input("file_guid");
	
	// Get the file
	$file = get_entity($file_guid);
	
	if ($file)
	{
		$mime = $file->getMimeType();
		if (!$mime) $mime = "application/octet-stream";
		
		$filename = $file->getFilename();
		
		header("Content-type: $mime");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		
		$file->open("read");
		
		while (!$file->eof())
		{
			echo $file->read(10240, $file->tell());	
		}
		
		$file->close();
	}
	else
		system_message(elgg_echo("file:downloadfailed"));
?>
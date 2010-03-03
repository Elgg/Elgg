<?php
	/**
	 * Elgg file download.
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Get the guid
	$file_guid = get_input("file_guid");
	
	// Get the file
	$file = get_entity($file_guid);
	
	if ($file) {
		$mime = $file->getMimeType();
		if (!$mime) {
			$mime = "application/octet-stream";			
		} 
		
		$filename = $file->originalfilename;
		
		// fix for IE https issue 
		header("Pragma: public");
		
		header("Content-type: $mime");
		if (strpos($mime, "image/")!==false)
			header("Content-Disposition: inline; filename=\"$filename\"");
		else
			header("Content-Disposition: attachment; filename=\"$filename\"");

		$contents = $file->grabFile();
		$splitString = str_split($contents, 8192);
		foreach($splitString as $chunk)
			echo $chunk;
		exit;
	} else {
		register_error(elgg_echo("file:downloadfailed"));
		forward();
	}
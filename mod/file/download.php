<?php
	/**
	 * Elgg file download.
	 * 
	 * @package ElggFile
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

		// allow downloads of large files.
		// see http://trac.elgg.org/ticket/1932
		ob_clean();
		flush();
		readfile($file->getFilenameOnFilestore());
		exit;
	} else {
		register_error(elgg_echo("file:downloadfailed"));
		forward();
	}
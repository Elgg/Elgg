<?php

	//	ELGG search through everything page

	// Run includes
		require("../includes.php");
		
		run("search:init");
		run("search:all:tagtypes");

		$output = run("search:all:display:rss", $_REQUEST['tag']);
		
		if ($output) {
			header("Pragma: public");
			header("Cache-Control: public"); 
			
			// no time data on this RSS, at least not without rewriting some function outputs
			
			$if_none_match = preg_replace('/[^0-9a-f]/', '', $_SERVER['HTTP_IF_NONE_MATCH']);
			
			$etag = md5($output);
			
			if ($if_none_match == $etag) {
				header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
				exit;
			}
			
			header("Content-Length: " . strlen($output));
			header('ETag: "' . $etag . '"');
			
			header("Content-type: text/xml");
			echo $output;
		}
?>
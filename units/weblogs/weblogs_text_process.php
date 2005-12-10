<?php

	// Processes text
	
		if (isset($parameter)) {
			$run_result .= nl2br($parameter);
		}
		
		
		// URLs to links
		
		$run_result = html_activate_urls($run_result);
		
		// Text cutting
		
		global $individual;
		
		if (!isset($individual) || $individual != 1) {
			$run_result = preg_replace("/\{\{cut\}\}(.|\n)*(\{\{uncut\}\})?/","{{more}}",$run_result);
		} else {
			// $run_result = preg_replace("/\{\{cut\}\}/","",$run_result);
			$run_result = str_replace("{{cut}}","",$run_result);
			$run_result = str_replace("{{uncut}}","",$run_result);
		}

?>
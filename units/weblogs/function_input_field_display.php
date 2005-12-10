<?php

	// Displays different HTML depending on input field type

	/*
	
		$parameter(
		
						0 => input name to display (for forms etc)
						1 => data
						2 => type of input field
						3 => reference name (for tag fields and so on)
						4 => ID number (if any)
						5 => Owner (if not specified, current $page_owner is assumed)
		
					)
	
	*/
	
		global $page_owner;
	
		if (isset($parameter) && sizeof($parameter) > 1) {
			
			if (!isset($parameter[4])) {
				$parameter[4] = -1;
			}
			if (!isset($parameter[5])) {
				if (isset($page_owner)) {
					$parameter[5] = $page_owner;
				} else {
					$parameter[5] = -1;
				}
			}
			
			switch($parameter[2]) {
				
				case "weblogtext":
						$run_result .= "<textarea name=\"".$parameter[0]."\" id=\"".$parameter[0]."\" style=\"width: 95%; height: 200px\">".htmlentities(stripslashes($parameter[1]))."</textarea>";
					break;
			}
			
		}
?>
<?php

	// Display unit intialisation routines

	// Variables used in basic templating
		global $screen;
		$screen['mainbody'] = "";
		$screen['headers'] = "";
		$screen['title'] = "";
		$screen['menu'] = "";
		$screen['sidebar'] = "";
		$screen['footer'] = "";
		$screen['messages'] = "";
		
	// Initialise RTF edit
		$data['display:topofpage:headers'][] =  "<script language=\"JavaScript\" type=\"text/javascript\" src=\"/units/display/rtfedit/richtext.js\"></script>";
	
	// Function to sanitise RTF edit text
	function RTESafe($strText) {
		//returns safe code for preloading in the RTE
		$tmpString = trim($strText);
		
		//convert all types of single quotes
		$tmpString = str_replace(chr(145), chr(39), $tmpString);
		$tmpString = str_replace(chr(146), chr(39), $tmpString);
		$tmpString = str_replace("'", "&#39;", $tmpString);
		
		//convert all types of double quotes
		$tmpString = str_replace(chr(147), chr(34), $tmpString);
		$tmpString = str_replace(chr(148), chr(34), $tmpString);
		
		//replace carriage returns & line feeds
		$tmpString = str_replace(chr(10), " ", $tmpString);
		$tmpString = str_replace(chr(13), " ", $tmpString);
		
		return $tmpString;
	}

		
?>
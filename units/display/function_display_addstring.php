<?php

	// Adds a string to a templating element
	
	// $parameter[0] = the context, $parameter[1] = the string to append
	
		if (isset($parameter[0]) && isset($parameter[1])) {
			
			global $screen;
			
			$context = strtolower($parameter[0]);
			
			if (isset($screen[strtolower($parameter[0]]))) {
				$screen[$context] .= $parameter[1];
			} else {
				$screen[$context] = $parameter[1];
			}
			
		}

?>
<?php

	// If isset $parameter (string), returns true if spam
	
		if (isset($parameter)) {
			
			$spam = db_query("select value from datalists where name = 'antispam'");
			if (sizeof($spam) > 0) {
				$spam = stripslashes($spam[0]->value);
			} else {
				$spam = "";
			}
			
			$spam = str_replace("\r","",$spam);
			$spam = explode("\n",$spam);
			
			foreach($spam as $regexp) {
				if (strlen($regexp) > 0) {
					if (substr($regexp,0,1) != "#") {
						if (@preg_match("/" . trim($regexp) . "/i", $parameter)) {
							$run_result = true;
						}
					}
				}
			}
			
		}

?>
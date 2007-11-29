<?php
    
        global $page_owner;
        
        if (is_array($parameter)) {
	        if ($parameter[0] == "comment:delete") {
	            
	            if (isloggedin() && $page_owner == $_SESSION['userid'] ) {
		            // owners of content can delete comments on it
	                $run_result = true;
	            }
	            
	        } elseif ($parameter[0] == "comment:edit") {
		        // need to add this case
	        }
        }    

?>
<?php

// Last modified Ben Werdmuller May 19 2005


global $page_owner;
global $messages;

if ($parameter == "files") {
    if ($page_owner == $_SESSION['userid']) {
        $run_result = true;
    }
}
if (logged_on) {
    // $parameter[0] = context
    // $parameter[1] = file owner
    if ($parameter[0] == "files:edit") {
	if ($parameter[1] == $_SESSION['userid']) {
		$run_result = true;
	} 
    }
}

?>
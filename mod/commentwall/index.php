<?php
	/** Index page */

	// Load the Elgg framework
        require_once("../../includes.php");
        global $CFG, $messages;
    
    /*
     * Variable initialisation
     */
        
	$owner = page_owner();
    if (empty($owner)) {
        $owner = -1;
    }
    
    global $page_owner;
    $page_owner = $owner;
    
    $offset = optional_param('offset', 0);
	$limit = optional_param('limit', 10);
    
    $title = sprintf(__gettext("%s's Comment Wall"), user_info("name", $owner));
    
    $wall = commentwall_getwall($owner, $limit, $offset);
    $html = commentwall_displaywall_html($wall, true, $owner);
    $html.= commentwall_display_footer($owner, $limit, $offset);
    
    templates_page_output($title, $html);
    
?>
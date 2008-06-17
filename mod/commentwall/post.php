<?php

	@require_once("../../includes.php");

	global $messages, $CFG;

	/**
	 * Handle comment post for non axax.
	 */

	$owner = optional_param('owner','');
	$wallowner = optional_param('wallowner','');
	$reply = optional_param('reply','');
	$returnurl = optional_param('returnurl','');
	
	$page = "";

	$owner = page_owner();
    if (empty($owner)) {
        $owner = -1;
    }
    
    global $page_owner;
    $page_owner = $owner;
    
    if ($reply!=-1)
    	$title = __gettext("Post a comment");
    else
    	$title = __gettext("Reply to a comment");

    $html = __gettext("<p>Use this form to post a comment.</p>");
    $html .= commentwall_post_form($wallowner, $reply, false, "", $returnurl);
    
    templates_page_output($title, $html);

?>
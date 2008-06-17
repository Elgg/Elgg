<?php

	@require_once("../../includes.php");

	global $messages, $CFG;

	/**
	 * Handle comment post.
	 */

	$ident = optional_param('ident','');
	$action = optional_param('action','');
	$owner = optional_param('owner','');
	$wallowner = optional_param('wallowner','');
	$comment_owner = $_SESSION['userid'];
	$reply = optional_param('reply','');
	$displaymode = optional_param('displaymode','noxml');
	$text = optional_param('text','');
	$returnurl = urldecode(optional_param('return_url',''));
	
	$page = "";

	/**
	 * Post comments etc.
	 */
	if ($action == "commentwall::post")
	{
			
		// Store the rating
        $success = (empty($text)) ? false : commentwall_addcomment($wallowner, $comment_owner, $text);

		// Message
		if ($success) 
		{
			$messages[] = __gettext("Comment posted.");  
			if ($displaymode=="xml") 
				$messages[] = __gettext(" Click here to see.");
		}
		else
		{
			$messages[] = __gettext("Comment could not be posted.");
		}
		
		// Are we outputing XML or text
		if ($displaymode=="xml")
		{
			$message = implode(" \n", $messages);
			if ($success) 
				$err = "0";
			else
				$err = "1";	
	
			$page = "<ajax>\n<message>$message</message>\n<error>$err</error>\n</ajax>\n";
		}
		else
		{
			header("Location: $returnurl");
			exit;
		}
	}
	else if($action == "commentwall::delete")
	{
		if (commentwall_deletecomment($ident))
		{
			// Success
		}
		else
		{
			// Fail
		}
		
		// Redirect
		header("Location: $returnurl");
		exit;
	}
	
	// Output the page
	if ($displaymode=="xml") {
		header("Content-type: text/xml");
		
		echo $page;
	}
	
	
?>
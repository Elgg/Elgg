<?php
global $messages, $USER, $CFG;

$action = optional_param('action','');
$comment_form_type = optional_param('comment_form_type','');
if ($action) {
	switch ($action) {
		// Create a comment
	    case "comment:add":
		$ok = false;
	        $comment = new StdClass;
	        $comment->object_id = optional_param('object_id',0,PARAM_INT);
	        $comment->object_type = optional_param('object_type','');
	        $comment->body = trim(optional_param('new_comment'));
	        $comment->postedname = trim(optional_param('postedname'));
	        $commentbackup = $comment;
	        if (!empty($comment->object_id) && !empty($comment->body) && !empty($comment->postedname)) {
		        $object_owner = get_owner($comment->object_id,$comment->object_type);
	            $where = run("users:access_level_sql_where",$USER->ident);
	            if ($comment_form_type == 'integrated') {
	            	$redirect_url = get_url($comment->object_id,$comment->object_type);
            	} elseif ($comment_form_type == 'separate') {
	            	$redirect_url = $CFG->wwwroot."mod/generic_comments/comment_page.php?object_id={$comment->object_id}&object_type={$comment->object_type}";
            	}
                if (run("spam:check",$comment->body) != true) {
                    // If we're logged on or comments are public, add one
                    if (isloggedin() || (!$CFG->disable_publiccomments && user_flag_get("publiccomments",$object_owner)) ) {
                        $comment->owner = $USER->ident;
                        $comment->posted = time();
                        $comment = plugin_hook("comment","create",$comment);
                        if (!empty($comment)) {
                            $insert_id = insert_record('comments',$comment);
                            $comment->ident = $insert_id;
                            $comment = plugin_hook("comment","publish",$comment);
                            $messages[] = __gettext("Your comment has been added."); // gettext variable
			    $ok = true;
                            
                            // If we're logged on and not the owner of this post, add post to our watchlist
                            if (isloggedin() && $comment->owner != $object_owner) {
                                delete_records('watchlist','object_id',$comment->object_id,'object_type',$comment->object_type,'owner',$comment->owner);
                                $wl = new StdClass;
                                $wl->owner = $comment->owner;
                                $wl->object_id = $comment->object_id;
                                $wl->object_type = $comment->object_type;
                                insert_record('watchlist',$wl);
                            }
                            
                            // Message comment if applicable
                            if ($comment->owner != $object_owner) {
	                            $object_title = get_title($comment->object_id,$comment->object_type);
                                $message = __gettext(sprintf("You have received a comment from %s on '%s'. It reads as follows:", $comment->postedname, stripslashes($object_title)));
                                $message .= "\n\n" . stripslashes($comment->body) . "\n\n";
                                $message .= __gettext(sprintf("To reply and see other comments, click here: %s", $redirect_url));
                                $message = wordwrap($message);
                                message_user($object_owner,$comment->owner,stripslashes($object_title),$message);
                            }
                        }
                    }
                } else {
                    $messages[] = __gettext("Your comment could not be posted. The system thought it was spam.");
                }
                
		// If river plugin installed then note comment
		if (function_exists('river_save_event'))
		{
			$commenturl = $CFG->wwwroot."mod/generic_comments/comment_page.php?object_id={$comment->object_id}&object_type={$comment->object_type}&comment_sort=ASC";
			$username = "<a href=\"" . river_get_userurl($comment->owner) . "\">" . user_info("username", $comment->owner) . "</a>";
			if (!isset($comment->owner)) 
			{
				$comment->owner = -1;
				$username = __gettext("Anonymous user");
			}

			river_save_event($comment->owner, $comment->object_id, $comment->owner, $comment->object_type, $username . " <a href=\"$commenturl\">" . __gettext("commented on") . "</a> " . river_get_friendly_id($comment->object_type, $comment->object_id));
			
		}

		$xml = optional_param('returnformat','');
		if (!empty($xml))
		{
			// If we are returning xml for the ajax response then output message and die.

			if ($ok!=true) { 
				$ok = "<error>1</error>\n"; 
			} else { 
				$messages[] = __gettext(" Click <a href=\"\">here</a> to refresh");
				$ok = "<error>0</error>\n";
			}
			
			$msg = implode("\n", $messages);
			header("Content-type: text/xml");
			
			echo "<ajax>\n<message>$msg</message>\n$ok</ajax>\n";
			exit;
		}
                else {
			define('redirect_url',$redirect_url);
		}
	        }
	        break;
	        
	        
	    // Delete a comment
	    case "comment:delete":
	        $comment_id = optional_param('comment_delete',0,PARAM_INT);
	        if (logged_on && !empty($comment_id)) {
	            $comment = get_record('comments','ident',$comment_id);
	            $comment = plugin_hook("comment","delete",$comment);
	            if (!empty($comment)) {
	                if ($comment->owner == $USER->ident || run("permissions:check",array("comment:delete",$_SESSION['userid'],$comment->object_id,$comment->object_type))) {
	                    delete_records('comments','ident',$comment_id);
	                    $messages[] = __gettext("Your comment was deleted.");
	                }
	            }
	            if (($comment_form_type == 'integrated') || ($comment_form_type == 'inline')) {
	            	$redirect_url = get_url($comment->object_id,$comment->object_type);
            	} elseif ($comment_form_type == 'separate') {
	            	$redirect_url = $CFG->wwwroot."mod/generic_comments/comment_page.php?object_id={$comment->object_id}&object_type={$comment->object_type}";
            	}
	            define('redirect_url',$redirect_url);
	        }
	        break;
	        
	    // Edit a comment
	    case "comment:edit":
	    	// need to add code for this case
	}
}
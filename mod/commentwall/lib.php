<?php
	/**
	 * @file lib.php Comment wall plugin
	 * This plugin is a replacement for the comment wall widget. 
	 * This replacement widget enables users to reply to another user's comment wall etc.
	 * @see Issue 98 for details
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	
	/**
	 * Comment wall initialisation.
	 */
	function commentwall_init()
	{
		global $CFG, $db,$function, $metatags, $template, $METATABLES;

		// Add meta tags 
		$metatags .= "<script type=\"text/javascript\" src=\"{$CFG->wwwroot}mod/commentwall/commentwall.js\"><!-- commentwall js --></script>";
		
		// Define some templates
        templates_add_context('commentwallobject', 'mod/commentwall/template');
        templates_add_context('commentwallfooter', 'mod/commentwall/footer');
        templates_add_context('css', 'mod/commentwall/css');
		
		// Set up the database
		$tables = $METATABLES;
		if (!in_array($CFG->prefix . "commentwall", $tables))
		{
			if (file_exists($CFG->dirroot . "mod/commentwall/$CFG->dbtype.sql"))
			{
				modify_database($CFG->dirroot . "mod/commentwall/$CFG->dbtype.sql");
                //reload system
                header_redirect($CFG->wwwroot);

			}
			else
			{
				error("Error: Your database ($CFG->dbtype) is not yet fully supported by the Elgg commentwall. See the mod/commentwall directory.");
			}
	
			print_continue($CFG->wwwroot);
			exit;
		}
		
		// Add configuration options
        $function['userdetails:edit:details'][] = $CFG->dirroot . "mod/commentwall/lib/commentwall_settings.php";
	}
	
	/**
	 * Comment wall page setup
	 */
	function commentwall_pagesetup()
	{
		
	}
	
	/** HACK: Output the given code as document.write */
	function commentwall_todocwrite($text)
	{
		$body = "";
		foreach (explode("\n",addslashes($text)) as $line)
			$body .= "document.write(\"" . trim($line) . "\");\n";

		return $body;
	}
	
	/**
	 * Retrieve the wall for a given userid.
	 *
	 * @return mixed Array of comment objects, else returns false.
	 * @param unknown_type $userid The user / wall we are retrieving
	 * @param unknown_type $limit Limit on the search
	 * @param unknown_type $offset Offset
	 */
	function commentwall_getwall($userid, $limit = 3, $offset = 0)
	{
		global $CFG;
		
		$query = "SELECT * from {$CFG->prefix}commentwall where wallowner=$userid order by posted desc limit $offset,$limit";
		
 // echo $query;
		
		return get_records_sql($query);
	}


	/**
	 * Retrieve the wall-to-wall for a given pair of userids.
	 *
	 * @return mixed Array of comment objects, else returns false.
	 * @param unknown_type $userid The user / wall we are retrieving
	 * @param unknown_type $limit Limit on the search
	 * @param unknown_type $offset Offset
	 */
        function commentwall_getwalltowall($userid, $otherid, $limit = 10, $offset = 0)
	{
		global $CFG;
		
		$query = "SELECT * FROM " . $CFG->prefix . "commentwall " . 
                         "WHERE (wallowner=" . $userid . " AND comment_owner=" . $otherid . ") OR ".
		         "(wallowner=" . $otherid . " AND comment_owner=" . $userid . ") ".
		         "ORDER BY posted desc LIMIT " . $offset . "," . $limit;
		
		//echo $query;
		
		return get_records_sql($query);
	}

	
	/**
	 * Add a comment to a wall.
	 * @param unknown_type $wall_id Which wall to post to
	 * @param unknown_type $poster_id 
	 * @param unknown_type $text
	 */
	function commentwall_addcomment($wall_id, $poster_id, $text)
	{
	  global $CFG;
	  
	  $newcomment = new stdClass;
	  $newcomment->wallowner = $wall_id;
	  $newcomment->comment_owner = $poster_id;
	  $newcomment->content = $text;
	  $newcomment->posted = time();
	  
	  if ($newcomment->ident = insert_record("commentwall", $newcomment)) {
            if ($newcomment->comment_owner != $newcomment->wallowner) {
	      
                $message = __gettext(sprintf("You have received a comment from %s on your comment wall:", user_name($newcomment->comment_owner), stripslashes($object_title)));
                $message .= "\n\n" . stripslashes($newcomment->content) . "\n\n";
                $message .= __gettext(sprintf("To reply on %s's comment wall, click here: %s", user_name($newcomment->comment_owner), $CFG->wwwroot . user_info("username", $newcomment->comment_owner) . "/profile/")) . "\n";
                $message .= __gettext(sprintf("To see other comments on your wall, click here: %s", $CFG->wwwroot . user_info("username", $newcomment->wallowner) . "/profile/"));
                $message = wordwrap($message);
                
                message_user($wall_id, $newcomment->comment_owner,  __gettext(sprintf("%s has posted to your comment wall", user_name($newcomment->comment_owner))), $message);
            }
			$newcomment = plugin_hook("commentwall","publish",$newcomment); 
            return $newcomment->ident;
        }
		
        $newcomment->ident = insert_record("commentwall", $newcomment);
		$newcomment = plugin_hook("commentwall","publish",$newcomment); 
        return $newcomment->ident;
	}
	
	/**
	 * Delete the given object id. 
	 *
	 * @param unknown_type $object_id
	 */
	function commentwall_deletecomment($object_id)
	{
		global $CFG;
		
		// Pull object
		$comment = get_record_sql("SELECT * from {$CFG->prefix}commentwall where ident=$object_id");

		// Check ownership (if you are either the wall owner or the comment owner you can delete this)
		if ((commentwall_permissions_check($comment->wallowner)) || (commentwall_permissions_check($comment->comment_owner)))
		{
			if (!delete_records("commentwall", "ident", $comment->ident))
				plugin_hook('commentwall','delete',$comment);
				return false;
				
			return true;
		}
		
		return false;
	}
	
	/**
	 * Reply to a given comment.
	 * @param unknown_type $comment_id
	 * @param unknown_type $wall_id Which wall to post to
	 * @param unknown_type $poster_id 
	 * @param unknown_type $text
	 */
	function commentwall_replyto($comment_id, $wall_id, $poster_id, $text)
	{
		global $CFG;
		
		// Extract the comment we are replying to
		$comment = get_record_sql("SELECT * from {$CFG->prefix}commentwall where ident=$comment_id");
		
		$newcomment = new stdClass;
			$newcomment->wallowner = $wall_id;
			$newcomment->comment_owner = $poster_id;
			$newcomment->content = $text;
			$newcomment->posted = time();
		
		return insert_record("commentwall", $newcomment, true);
		
	}
	
	/**
	 * Display post form.
	 */
	function commentwall_post_form($wall_owner, $replyto = -1, $specialmode = false, $suffix = "", $returnurl = "")
	{
		global $CFG;
		
		if ($returnurl=="")
			$returnurl = urlencode($_SERVER['REQUEST_URI']);
		$buttontxt = __gettext("Post comment");
		
		$frm_elements_common = <<< END
			<input type="hidden" name="action" value="commentwall::post" />
			<input type="hidden" name="owner" value="$wall_owner" />
			<input type="hidden" name="wallowner" value="$wall_owner" />
			<input type="hidden" name="reply" value="$replyto" />
			<input type="hidden" name="comment_owner" value="{$_SESSION['userid']}" />		
			<input type="hidden" name="return_url" value="$returnurl" />
			<textarea name="text"></textarea>
END;

		$html = <<< END
			<div id="commentwall_form_$replyto">
				
					<form id="commentwall_post_frm_$replyto$suffix" action="{$CFG->wwwroot}mod/commentwall/do_action.php" method="POST">
						$frm_elements_common
						<br /><input type="submit" name="$buttontxt" value="$buttontxt" />
					</form>	
			</div>
END;
		
		return $html;
	}
	
	/**
	 * Display a comment.
	 *
	 * @param unknown_type $comment_obj
	 */
	function commentwall_displaycomment($comment_obj)
	{
		global $CFG; 
		
		$html = "";
		
		$owner_username = user_info("name", $comment_obj->wallowner);
		$comment_owner_username = ($comment_obj->comment_owner != 0 ? user_info("name", $comment_obj->comment_owner) : __gettext("Anonymous User"));	
		$icon = ($comment_obj->comment_owner != 0 ? user_info('icon',$comment_obj->comment_owner) : -1);
		$userlogo = user_icon_html($comment_obj->comment_owner,60,true); // $CFG->wwwroot.'_icon/user/'.$icon.'/w/50';
		$userlink = ($comment_obj->comment_owner != 0 ? $CFG->wwwroot . user_info("username", $comment_obj->comment_owner) . "/" : ""); 
			
		$date = date("l jS F Y, g:ia" ,$comment_obj->posted);
		$text = nl2br($comment_obj->content);
		
		$replytowall = __gettext("Post reply");
		$replytootherwall = sprintf(__gettext("%s's wall"), $comment_owner_username);
		$walltowall = __gettext("Wall-to-wall");
		$delete = __gettext("Delete");
		
		$doaction = "{$CFG->wwwroot}mod/commentwall/do_action.php?owner=" . $comment_obj->wallowner. "&return_url=" .urlencode($_SERVER['REQUEST_URI']);

		$replybar = "";
		if (isloggedin())
		{
			//$replybar .= "<a href=\"#commentwall_form_-1\">$replytowall</a>";
		  if (($comment_obj->wallowner != $comment_obj->comment_owner) && ($comment_obj->comment_owner != 0)) {
				$replybar .= "<a href=\"{$CFG->wwwroot}mod/commentwall/index.php?owner={$comment_obj->comment_owner}&wallowner={$comment_obj->comment_owner}&comment_owner={$_SESSION['userid']}&reply={$comment_obj->ident}&return_url=" .urlencode($_SERVER['REQUEST_URI'])."\">$replytootherwall</a> | "; 	
				$replybar .= "<a href=\"{$CFG->wwwroot}mod/commentwall/walltowall.php?owner={$comment_obj->wallowner}&other={$comment_obj->comment_owner}&return_url=" .urlencode($_SERVER['REQUEST_URI'])."\">$walltowall</a> | ";
		  }
		  if ((commentwall_permissions_check($comment_obj->comment_owner))
		      || (commentwall_permissions_check($comment_obj->wallowner)))
		    $replybar.= "<a href=\"$doaction&action=commentwall::delete&ident={$comment_obj->ident}\">$delete</a>";
		}


		$html = templates_draw(
			array(
				'context' => "commentwallobject",
				'userlogo' => $userlogo,
				'userlink' => $userlink,
				'usertxt' => $comment_owner_username,
				'date' => $date,
				'text' => $text,
				'replybar' => $replybar
			)
		);
		
		return $html;
	}
	
	/**
	 * @param $wall List of objects.
	 */
	function commentwall_display_footer($owner, $limit = 3, $offset = 0)
	{
		global $CFG;
		
		$html = "";
		
		$count = get_record_sql("SELECT count(ident) as ident from {$CFG->prefix}commentwall where wallowner=$owner order by posted desc");
		$count = $count->ident;

		$qs = $_SERVER['REDIRECT_URL'];
		if ($qs == "") $qs = $_SERVER['PHP_SELF']; 
		
		// See if we need to display a next button
		$nextbutton = "";
		if ($count - $offset > $limit)
			$nextbutton = "<a href=\"$qs?owner=$owner&offset=" . ($offset+$limit) . "\">" . __gettext("Back") . "</a>";
			
		// See if we need to display a prev button
		$prevbutton = "";
		if (floor($offset / $limit) > 0)
			$prevbutton = "<a href=\"$qs?owner=$owner&offset=" . ($offset-$limit) . "\">" . __gettext("Forward") . "</a>";
		
		return templates_draw(
		array(
		'context' => 'commentwallfooter',
		'nextbutton' => $nextbutton,
		'prevbutton' => $prevbutton
		)
		);
	}
	
	/**
	 * Display a comment wall.
	 *
	 * @param unknown_type $wall
	 */
	function commentwall_displaywall_html($wall,$showalltxt = false, $owner)
	{
        global $CFG;

        $html = "";
		
		// Get access permissions
		$access = user_flag_get("commentwall_access", $owner);
		if (!$access) $access = "LOGGED_IN"; // If no access controls set then assume public
		
		if (
		    ($owner == $_SESSION['userid']) ||                              // Display if the current user owns it
			($access == "PUBLIC") ||                                        // Display if public
			( ($access == "LOGGED_IN") && (isloggedin()) ) ||               // If user needs to be logged in, check it
			( ($access == "FRIENDS_ONLY") && (isfriend($_SESSION['userid'],$owner))) ||                         // If you can 
			( ($access == "PRIVATE") && ($owner == $_SESSION['userid']) )   // If access is private then ensure that $owner is the current logged in user
		)
		{
            // $owner = page_owner();
            $html = "<div id=\"commentwall_title\"><h2>" . sprintf(__gettext("Write on %s's comment wall"), user_info("name", $owner)) . "</h2></div>";

            if (($showalltxt) && ($wall))
            {
                $html .= "<div id=\"commentwall_more\"><a href=\"{$CFG->wwwroot}mod/commentwall/index.php?owner=$owner\">" . __gettext("See all...") . "</a></div>";
            }


            $html .= commentwall_post_form($owner);
    		
    		if (!$wall) {
    			$html .= "<p>" . __gettext("No comments on this wall, why not be the first?") . "</p>";
    			return $html;
    		}
		
	    }
		
        if (is_array($wall)) {
            foreach ($wall as $w)
                $html .= commentwall_displaycomment($w);
        }
		
		return $html;
	}
	
	/**
	 * Render the profile on a profile.
	 *
	 * @param unknown_type $owner
	 */
	function commentwall_displayonprofile($owner, $limit = 3, $offset = 0)
	{
		global $CFG;
		$html = "";	
		
		$wall = commentwall_getwall($owner, $limit, $offset);
		
		$html = "<div id=\"commentwall\">";
		
		$html .= commentwall_displaywall_html($wall, true, $owner);
	 
		$html .= "</div>";
		
		return $html;
	}
	
/**
 * Determines whether or not the current user has permission to do something with the comment.
 *
 * @param $owner The owner of the comment.
 * @return boolean True or false.
 */
function commentwall_permissions_check($owner) 
{
    global $CFG;
    
	if (isloggedin()) {
		if ($owner == $_SESSION['userid'] || user_flag_get('admin',$_SESSION['userid'])) {
			return true;
		}
		if (user_info("user_type",$owner) == "community") {
    		if (record_exists('users','ident',$owner,'owner',$_SESSION['userid'],'user_type','community')) {
                return true;
            } 
            if (count_records_sql('SELECT count(u.ident) FROM '.$CFG->prefix.'friends f
                                             JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                             WHERE u.ident = ? AND f.owner = ? AND u.user_type = ?',
                                  array($owner,$_SESSION['userid'],'community'))) {
                return true;
            }
		}
	}
	
	return false;
}
?>

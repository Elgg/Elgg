<?php

function generic_comments_init() {
	global $CFG, $db,$function, $metatags, $template;

	$metatags .= "<script type=\"text/javascript\" src=\"{$CFG->wwwroot}mod/generic_comments/generic_comments.js\"><!-- generic_comments js --></script>";

	// create the generic_comments and generic watchlist table
	$tables = $db->Metatables();
    if (!in_array($CFG->prefix . "comments",$tables) || !in_array($CFG->prefix . "watchlist",$tables)) {
        if (file_exists($CFG->dirroot . "mod/generic_comments/$CFG->dbtype.sql")) {
            modify_database($CFG->dirroot . "mod/generic_comments/$CFG->dbtype.sql");
        } else {
            error("Error: Your database ($CFG->dbtype) is not yet fully supported by the Elgg generic comments.  See the mod/generic_comments directory.");
        }
        print_continue("index.php");
        exit;
    }
    
    $function['comments:init'][] = $CFG->dirroot . "mod/generic_comments/comments_actions.php";
    $function['permissions:check'][] = $CFG->dirroot . "mod/generic_comments/permissions_check.php";

    // Add annotation support
	display_set_display_annotation_function("file::file", "generic_comments_displayobjectannotations");
	display_set_display_annotation_function("mediastream::media", "generic_comments_displayobjectannotations");

	// Register file river hook (if there)
	if (function_exists('river_save_event'))
	{
		river_register_friendlyname_hook('file::file', 'generic_comments_get_friendly_name');
	}
	$template['embeddedcomments'] = file_get_contents($CFG->dirroot . "mod/generic_comments/comments");
	$template['embeddedcomment'] = file_get_contents($CFG->dirroot . "mod/generic_comments/comment");
	
	$template['css'] .= file_get_contents($CFG->dirroot . "mod/generic_comments/css");

}

function generic_comments_pagesetup() {
// need to set up an admin option to allow comments to be editable

}

function generic_comments_get_friendly_name($object_type, $object_id)
{
	global $CFG;

	if ($object_type == 'file::file')
		return file_get_friendly_name($object_type, $object_id);
		
	if ($object_type == 'mediastream::media')
		return mediastream_get_friendly_name($object_type, $object_id);

	return "";
}

function generic_comments_displayobjectannotations($object, $object_type, $view)
{
	$return = "";

	if (($object_type == "file::file") || ($object_type == "mediastream::media"))
	{
		$txt = __gettext('Click to add or view comments.');
		$int = implode('',action('annotate',$object->ident,$object_type,NULL,
             		array('comment_form_type'=>'inline', 'comment_sort'=>'ASC', 'comment_form_text'=>$txt)));

		$return .= <<< END
			<script type="text/javascript">
			<!--
				$int
			-->
			</script>
			<noscript>
END;
		$return .= implode('',action('annotate',$object->ident,$object_type,NULL,
             		array('comment_form_type'=>'summary', 'comment_sort'=>'ASC', 'comment_form_text'=>$txt)));

		$return .= <<< END
			</noscript>
END;
	}

	return $return;
}


function generic_comments_annotate($object_id,$object_type,$parameters=NULL) {
	global $CFG;
	global $page_owner;
        $owner_username = user_info('username', $page_owner);
// create a form to display comments for this object, and then display the comments for one page
	
	$default_comment_form_text = __gettext("Add a comment"); // gettext variable
	if (!$parameters) {
		$comment_form_type = 'integrated';
		$comment_form_text = $default_comment_form_text;
	} else {
		if (isset($parameters['comment_form_type'])) {
			$comment_form_type = $parameters['comment_form_type'];
		} else {
			$comment_form_type = 'integrated';
		}
		if (isset($parameters['comment_form_text'])) {
			$comment_form_text = $parameters['comment_form_text'];
		} else {
			$comment_form_text = $default_comment_form_text;
		}			
	}

	if (in_array($comment_form_type,array('integrated','separate'))) {

	$item_details = "";
	if (($object_type=="file::file") || (($object_type=="file::folder")))
		$item_details = display_run_displayobject('file', $object_id, $object_type);
	if ($object_type=="mediastream::media")
		$item_details = display_run_displayobject('mediastream', $object_id, $object_type);
	
        $run_result = <<< END
        
    <form action="{$CFG->wwwroot}mod/generic_comments/action_redirection.php" method="post">

        <h2>$comment_form_text</h2>
    
END;

        // $field = display_input_field(array("new_comment","","longtext"));
        $field = <<< END
        
        <textarea name="new_comment" id="new_comment"></textarea>
        
END;
        if (logged_on) {
            $userid = $_SESSION['userid'];
        } else {
            $userid = -1;
        }
        $field .= <<< END
        
        <input type="hidden" name="action" value="comment:add" />
        <input type="hidden" name="object_id" value="{$object_id}" />
        <input type="hidden" name="object_type" value="{$object_type}" />
        <input type="hidden" name="owner" value="{$userid}" />
        <input type="hidden" name="comment_form_type" value="{$comment_form_type}" />
        <input type="hidden" name="comment_sort" value="{$comment_sort}" />      
END;

        $run_result .= templates_draw(array(
        
                                'context' => 'databox1',
                                'name' => __gettext("Your comment text"),
                                'column1' => $field
        
                            )
                            );
                            
        if (logged_on) {
            $comment_name = $_SESSION['name'];
        } else {
            $comment_name = __gettext("Guest");
        }

        $run_result .= templates_draw(array(
        
                                'context' => 'databox1',
                                'name' => __gettext("Your name"),
                                'column1' => "<input type=\"text\" name=\"postedname\" value=\"".htmlspecialchars($comment_name, ENT_COMPAT, 'utf-8')."\" />"
        
                            )
                            );
        
        $run_result .= templates_draw(array(
        
                                'context' => 'databox1',
                                'name' => '&nbsp;',
                                'column1' => "<input type=\"submit\" value=\"".__gettext("Add comment")."\" />"
        
                            )
                            );
                            
        $run_result .= <<< END
    
    </form>
        
END;

		// get the comments
	
	    $commentsbody = "";
	        
	    //which page of comments to display (page numbers are 0-based)
	    $page = optional_param('commentpage', 0, PARAM_INT);
	    $sort_sequence = optional_param('comment_sort','');
	    //$perpage = 20; // set to 0/false to disable paging
	    $perpage = 5; // set to 0/false to disable paging - set to 5 for testing - KJ
	    $offset = $page * $perpage;
	    if ($sort_sequence != 'DESC') {
	    	$sort_sequence = 'ASC';
    	}
	    if ($comment_form_type == 'integrated') {
	    	$thispageurl = generic_comments_add_parameter_to_url(get_url($object_id, $object_type),'comment_sort',$sort_sequence);
    	} elseif ($comment_form_type == 'separate') {
	    	$thispageurl = $CFG->wwwroot."mod/generic_comments/comment_page.php?object_id=$object_id&object_type=$object_type&comment_sort=$sort_sequence";
    	}
	    
	    if ($comments = get_records_sql("SELECT * FROM {$CFG->prefix}comments WHERE object_id = $object_id AND object_type = '$object_type' ORDER BY posted $sort_sequence")) {
	        $numcomments = count($comments);
	        $pagelinks = '';
	        if (!empty($perpage) && $numcomments > $perpage) {
	            $comments = array_slice($comments, $offset, $perpage);
	            $numpages = ceil($numcomments / $perpage);
	            $pagelinks = __gettext("Page: ");
	            for ($i = 1; $i <= $numpages; $i++) {
	                $pagenum = $i - 1;
	                if ($pagenum != $page) {
		                if ($pagenum) {
			                $pageurl = generic_comments_add_parameter_to_url($thispageurl,'commentpage',$pagenum);
		                }
	                    //$pageurl = $thispageurl . (($pagenum) ? '.' . $pagenum : '');
	                    $pagelinks .= ' <a href="' . $pageurl . '">' . $i . '</a>' ;
	                } else {
	                    $pagelinks .= ' ' . $i . ' ';
	                }
	                
	            }
	            //$thispageurl .= '.' . $page;
	            $thispageurl = generic_comments_add_parameter_to_url($thispageurl,'commentpage',$page);
	            
	        }
	        
	        foreach($comments as $comment) {
	            $commentmenu = "";
	            if (isloggedin() && ($comment->owner == $_SESSION['userid'] || run("permissions:check",array("comment:delete",$_SESSION['userid'],$comment->object_id,$comment->object_type)))) {
	                $returnConfirm = __gettext("Are you sure you want to permanently delete this comment?");
	                $Delete = __gettext("Delete");
	                $commentmenu = <<< END
	                <a href="{$CFG->wwwroot}mod/generic_comments/action_redirection.php?action=comment:delete&amp;comment_form_type=$comment_form_type&amp;comment_delete={$comment->ident}" onclick="return confirm('$returnConfirm')">$Delete</a>
END;
	            }
	            $comment->postedname = htmlspecialchars($comment->postedname, ENT_COMPAT, 'utf-8');
	            
	            // turn commentor name into a link if they're a registered user
	            // add rel="nofollow" to comment links if they're not
	            if ($comment->owner > 0) {
	                $commentownerusername = user_info('username', $comment->owner);
	                $comment->postedname = '<a href="' . url . $commentownerusername . '/">' . $comment->postedname . '</a>';
	                $comment->icon = '<a href="' . url . $commentownerusername . '/">' . user_icon_html($comment->owner,50) . "</a>";
	                $comment->body = run("weblogs:text:process", array($comment->body, false));
	            } else {
	                $comment->icon = "<img src=\"" . $CFG->wwwroot . "_icons/data/default.png\" width=\"50\" height=\"50\" align=\"left\" alt=\"\" />";
	                $comment->body = run("weblogs:text:process", array($comment->body, true));
	            }
	            
	            $commentsbody .= templates_draw(array(
	                                                  'context' => 'embeddedcomment',
	                                                  'postedname' => $comment->postedname,
	                                                  'body' => '<a name="cmt' . $comment->ident . '" id="cmt' . $comment->ident . '"></a>' . $comment->body,
	                                                  'posted' => strftime("%A, %d %B %Y, %H:%M %Z",$comment->posted),
	                                                  'usericon' => $comment->icon,
	                                                  'permalink' => $thispageurl . "#cmt" . $comment->ident,
	                                                  'links' =>  $commentmenu
	                                                  )
	                                            );
	            
	        }
	        $commentsbody = templates_draw(array(
	                                             'context' => 'embeddedcomments',
	                                             'paging' => $pagelinks,
	                                             'comments' => $commentsbody
	                                             )
	                                       );
	        
	    }
	    
	    $body = $item_details . $commentsbody . $run_result;
	} elseif ($comment_form_type == 'summary') {
		$count = count_records('comments','object_id',$object_id,'object_type',$object_type);
		if (!isset($count) || $count == 0) {
			$comment_count = "0 ".__gettext("comments").".";
		} elseif ($count == 1) {
			$comment_count = "1 ".__gettext("comment").".";
		} else {
			$comment_count = $count." ".__gettext("comments").".";
		}
		
		if ($parameters && strtoupper($parameters['comment_sort']) == 'DESC') {
			$comment_sort = '&comment_sort=DESC';
		}
		
		$body = "<p>$comment_count <a href=\"{$CFG->wwwroot}mod/generic_comments/comment_page.php?object_id=$object_id&object_type=$object_type$comment_sort\">$comment_form_text</a>";


		
	 } elseif ($comment_form_type == 'inline') {
		// Funky javascript inline editing

		$count = count_records('comments','object_id',$object_id,'object_type',$object_type);
		if (!isset($count) || $count == 0) {
			$comment_count = "0 ".__gettext("comments").".";
		} elseif ($count == 1) {
			$comment_count = "1 ".__gettext("comment").".";
		} else {
			$comment_count = $count." ".__gettext("comments").".";
		}
		
		if ($parameters && strtoupper($parameters['comment_sort']) == 'DESC') {
			$comment_sort = '&comment_sort=DESC';
		}
		
		// $field = display_input_field(array("new_comment","","longtext"));
		$field = <<< END
		<textarea name="new_comment" id="new_comment"></textarea>
END;
		if (logged_on) {
			$userid = $_SESSION['userid'];
		} else {
			$userid = -1;
		}	

		/* There now follows a selection of nasty hacks. Yes yes, I know this is ugly, but it means that it falls back cleanly if no javascript is supported. TODO: There must be a better way, but i'm too tired just now to think of one.*/
		if (logged_on) {
			$comment_name = $_SESSION['name'];
		} else {
			$comment_name = __gettext("Guest");
		}

		

		$thispageurl = generic_comments_add_parameter_to_url(get_url($object_id, $object_type),'comment_sort',$sort_sequence);
        	$comment_name_enc = templates_draw(array(
        
                                'context' => 'databox1',
                                'name' => __gettext("Your name"),
                                'column1' => "<input type=\"text\" name=\"postedname\" value=\"".htmlspecialchars($comment_name, ENT_COMPAT, 'utf-8')."\" />"
        
                            )
                            );
		$postcomment = __gettext("Post comment...");

		if ($comments = get_records_sql("SELECT * FROM {$CFG->prefix}comments WHERE object_id = $object_id AND object_type = '$object_type' ORDER BY posted $sort_sequence")) {
	        $numcomments = count($comments);
	        $pagelinks = '';
	        if (!empty($perpage) && $numcomments > $perpage) {
	            $comments = array_slice($comments, $offset, $perpage);
	            $numpages = ceil($numcomments / $perpage);
	            $pagelinks = __gettext("Page: ");
	            for ($i = 1; $i <= $numpages; $i++) {
	                $pagenum = $i - 1;
	                if ($pagenum != $page) {
		                if ($pagenum) {
			                $pageurl = generic_comments_add_parameter_to_url($thispageurl,'commentpage',$pagenum);
		                }
	                    //$pageurl = $thispageurl . (($pagenum) ? '.' . $pagenum : '');
	                    $pagelinks .= ' <a href="' . $pageurl . '">' . $i . '</a>' ;
	                } else {
	                    $pagelinks .= ' ' . $i . ' ';
	                }
	                
	            }
	            //$thispageurl .= '.' . $page;
	            $thispageurl = generic_comments_add_parameter_to_url($thispageurl,'commentpage',$page);
	            
	        }
	        
	        foreach($comments as $comment) {
	            $commentmenu = "";
	            if (isloggedin() && ($comment->owner == $_SESSION['userid'] || run("permissions:check",array("comment:delete",$_SESSION['userid'],$comment->object_id,$comment->object_type)))) {
	                $returnConfirm = __gettext("Are you sure you want to permanently delete this comment?");
	                $Delete = __gettext("Delete");
	                $commentmenu = <<< END
	                <a href="{$CFG->wwwroot}mod/generic_comments/action_redirection.php?action=comment:delete&amp;comment_form_type=$comment_form_type&amp;comment_delete={$comment->ident}" onclick="return confirm('$returnConfirm')">$Delete</a>
END;
	            }
	            $comment->postedname = htmlspecialchars($comment->postedname, ENT_COMPAT, 'utf-8');
	            
	            // turn commentor name into a link if they're a registered user
	            // add rel="nofollow" to comment links if they're not
	            if ($comment->owner > 0) {
	                $commentownerusername = user_info('username', $comment->owner);
	                $comment->postedname = '<a href="' . url . $commentownerusername . '/">' . $comment->postedname . '</a>';
	                $comment->icon = '<a href="' . url . $commentownerusername . '/">' . user_icon_html($comment->owner,50) . "</a>";
	                $comment->body = run("weblogs:text:process", array($comment->body, false));
	            } else {
	                $comment->icon = "<img src=\"" . $CFG->wwwroot . "_icons/data/default.png\" width=\"50\" height=\"50\" align=\"left\" alt=\"\" />";
	                $comment->body = run("weblogs:text:process", array($comment->body, true));
	            }
	            
	            $commentsbody .= templates_draw(array(
	                                                  'context' => 'embeddedcomment',
	                                                  'postedname' => $comment->postedname,
	                                                  'body' => '<a name="cmt' . $comment->ident . '" id="cmt' . $comment->ident . '"></a>' . $comment->body,
	                                                  'posted' => strftime("%A, %d %B %Y, %H:%M %Z",$comment->posted),
	                                                  'usericon' => $comment->icon,
	                                                  'permalink' => $thispageurl . "#cmt" . $comment->ident,
	                                                  'links' =>  $commentmenu
	                                                  )
	                                            );
	            
	        }
	        $commentsbody = templates_draw(array(
	                                             'context' => 'embeddedcomments',
	                                             'paging' => $pagelinks,
	                                             'comments' => $commentsbody
	                                             )
	                                       );
	}

		$bodyfrm = <<< END
			<span style="cursor:hand; cursor:pointer" onclick="showhide('oid_$object_id')">$comment_count $comment_form_text</span>
			<div id="oid_$object_id" style="display:none">
				$commentsbody
				<form id="comment_$object_id">
					$field 
					<input type="hidden" name="action" value="comment:add" />
					<input type="hidden" name="object_id" value="{$object_id}" />
					<input type="hidden" name="object_type" value="{$object_type}" />
					<input type="hidden" name="owner" value="{$userid}" />
					<input type="hidden" name="comment_form_type" value="integrated" />
					<input type="hidden" name="comment_sort" value="{$comment_sort}" />
					$comment_name_enc
				</form>
				<div id="ajaxmessages_$object_id"></div>
				<div id="ajaxmessages_post_$object_id"><input type="button" style="cursor:hand; cursor:pointer" onclick="sendcomment('{$CFG->wwwroot}mod/generic_comments/action_redirection.php','comment_$object_id', $object_id)" value="$postcomment" /></div>
			</div>
END;
		$body = "";
		foreach (explode("\n",addslashes($bodyfrm)) as $line)
			$body .= "document.write(\"" . trim($line) . "\");";

	 }
    return $body;    
}

function generic_comments_add_parameter_to_url($url,$name,$value) {
	// Pick the correct separator to use
	$separator = "?";
	if (strpos($url,"?")!==false) {
	  $separator = "&";
	}
	 
	// Find the location for the new parameter
	$insertPosition = strlen($url); 
	if (strpos($url,"#")!==false) {
	  $insertPosition = strpos($url,"#");
	}
	 
	// Build the new url
	return substr_replace($url,"$separator$name=$value",$insertPosition,0);
}


function get_owner ($object_id, $object_type) {
	// this is a temporary location - the code should be moved into elgglib
	switch ($object_type) {
		case 'file::file':
			$table = 'files';
			break;
		case 'file::folder':
			$table = 'file_folders';
			break;
		case 'blog::weblog':
			$table = 'weblog_posts';
			break;
		case 'mediastream::media':
			$table = 'mediastream_objects';
			break;
		default:
			// this table does not exist (yet)
			$table = 'metadata';
	}
	
	if ($object_type == 'profile::profile') {
		$owner =  $object_id;
	} elseif ($object_type == 'file::folder' && $object_id < 0) {
		// kludge for root folders
		$owner = -$object_id;
	} else {
		$result = get_record($table,'ident',$object_id);
		$owner = $result->owner;
	}
	return $owner;
}

function get_access ($object_id, $object_type,$access_category='read') {
	// this is a temporary location - the code should be moved into elgglib
	
	// the only valid access category is read right now, but this should change
	
	switch ($object_type) {
		case 'file::file':
			$table = 'files';
			break;
		case 'file::folder':
			$table = 'file_folders';
			break;
		case 'blog::weblog':
			$table = 'weblog_posts';
			break;
		case 'mediastream::media':
			$table = 'mediastream_objects';
			break;
		default:
			// this table does not exist (yet)
			$table = 'metadata';
	}
	
	if ($object_type == 'profile::profile') {
		// no way to block access to profile comments right now
		$access = 'PUBLIC';
	} else {
		$access = get_field($table,'access','ident',$object_id);
	}
	
	return $access;
}

function get_title ($object_id, $object_type) {
	// this is a temporary location - the code should be moved into elgglib
	switch ($object_type) {
		case 'file::file':
			$title = get_field('files','title','ident',$object_id);
			break;
		case 'file::folder':
			if ($object_id < 0) {
				$title = __gettext("Root folder");
			} else {
				$title = get_field('file_folders','name','ident',$object_id);
			}
			break;
		case 'blog::weblog':
			$title = get_field('weblog_posts','title','ident',$object_id);
			break;
		case 'mediastream::media':
			$title = get_field('mediastream_objects','name','ident',$object_id);
			break;
		case 'profile::profile':
			$title = __gettext("Profile for")." ".user_info('name',$object_id);
			break;
		default:
			// this table does not exist (yet)
			$title = get_field('metadata','title','ident',$object_id);
	}
	
	return $title;
}


function get_url($object_id, $object_type) {
	global $CFG, $messages;
	// this is a temporary location - the code should be moved into elgglib
	
	switch ($object_type) {
		case 'file::file':
			$object_record = get_record('files','ident',$object_id);
			$username = user_info('username',$object_record->files_owner);
			if ($object_record->folder == -1) {
				$url = $CFG->wwwroot.$username.'/files';
			} else {
				$url = $CFG->wwwroot.$username.'/files/'.$object_record->folder;
			}				
			break;
		case 'file::folder':
			if ($object_id < 0) {
				$username = user_info('username',-$object_id);
				$url = $CFG->wwwroot.$username.'/files';
			} else {
				$object_record = get_record('file_folders','ident',$object_id);
				if ($object_record) {
					$username = user_info('username',$object_record->files_owner);
					$url = $CFG->wwwroot.$username.'/files/'.$object_id;
				} else {
					$url = '';
				}
			}
			break;
		case 'blog::weblog':
			$object_record = get_record('weblog_posts','ident',$object_id);
			$username = user_info('username',$object_record->weblog);
			$url = $CFG->wwwroot.$username.'/weblog/'.$object_record->ident.'.html';
			break;
		case 'mediastream::media':
			$object_record = get_record('mediastream_objects','ident',$object_id);
			$username = user_info('username',$object_record->owner);
			$url = $CFG->wwwroot."/mod/mediastream/display.php?ident={$object_record->ident}&owner={$object_record->owner}&media={$object_record->mediatype}";
			break;
		case 'profile::profile':
			$username = user_info('username',$object_id);
			$url = $CFG->wwwroot.$username.'/profile';
			break;
		default:
			$url = '';
			// call the module_url function if it exists
			$mod_pos = strpos($object_type,"::");
		    if ($mod_pos) {
		    	$module = substr($object_type,0,$mod_pos);
		    	$module_display_url = $module . '_url';
				if ($module && function_exists($module_display_url)) {
					$url = $module_display_url($object_id,$object_type);
				}
			}		
	}
	return $url; 
}

// this is a temporary location - the code should be moved into elgglib

/**
 * Returns an array of results from each relevant module
 *
 * This function runs the specified hook function for each module
 * that has the hook (possibly restricted to a supplied list of module names). 
 *
 * @param string $hook the name of the module hook we want to invoke
 * @param int  $object_id the object id to apply the hook to (can be empty)
 * @param string  $object_type the type of the object to apply the hook to (can be empty)
 * @param array $modules an array of module names (can be empty)
 * @param array $modules an array of values keyed by keyed by parameter names to pass to the hook function (can be empty)
 * @return array an array of results keyed by module name
 */

function action($hook,$object_id=0, $object_type='', $modules = NULL, $parameters = NULL ) {
    global $CFG;

    $results = array();
    if (!$modules) {
        //if (!$CFG->plugins) {
            $CFG->plugins = get_list_of_plugins('mod');
        //}
        $modules = $CFG->plugins;
    }
    foreach ($modules as $mod) {
        $mod_function = $mod . '_'.$hook;
        if (function_exists($mod_function)) {
           if ($parameters) {
                $results[$mod] = $mod_function($object_id,$object_type,$parameters);
            } else {
                $results[$mod] = $mod_function($object_id,$object_type);
            }
        }
    }
    
    return $results;
}


/**
     * Returns the HTML to display a user's icon, with event hooks allowing for interception.
     * Internally passes around a "user_icon" "display" event, with an object
     * containing the elements 'html', 'icon' (being the icon ID), 'size', 'owner' and 'url'.
     *
     * @uses $CFG
     * @param integer $user_id  The unique ID of the user we want to display the icon for.
     * @param integer $size  The size of the icon we want to display (max: 100).
     * @param boolean $urlonly  If true, returns the URL of the icon rather than the full HTML.
     * @return string Returns the icon HTML, or the default icon if something went wrong (eg the user didn't exist).
     */
/*    function user_icon_html($user_id, $size = 100, $urlonly = false) {
        global $CFG;
        $extra = "";
        $user_icon = new stdClass;
        $user_icon->owner = $user_id;
        $user_icon->size = $size;
        if ($size < 100) {
            $extra = "/h/$size/w/$size";
        }
        if ($user_icon->icon = user_info("icon",$user_id)) {
            $user_icon->url = "{$CFG->wwwroot}_icon/user/{$user_icon->icon}{$extra}";
            $user_icon->html = "<img src=\"{$user_icon->url}\" border=\"0\" alt=\"user icon\" />";
            if ($user_icon = plugin_hook("user_icon","display",$user_icon)) {
                if ($urlonly) {
                    return $user_icon->url;
                } else {
                    return $user_icon->html;
                }
            }
        }
        if ($urlonly) {
            return -1;
        } else {
            return "<img src=\"{$CFG->wwwroot}_icon/user/-1{$extra}\" border=\"0\" alt=\"default user icon\" />";
        }
    }
*/
?>

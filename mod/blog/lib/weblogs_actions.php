<?php
global $USER;
global $CFG;

// Actions to perform
$action = optional_param('action');
$extensionContext = trim(optional_param('extension','weblog'));

switch ($action) {
    // Create a new weblog post
    case "weblogs:post:add":
        $post = new StdClass;
        $post->title = trim(optional_param('new_weblog_title'));
        $post->body = trim(optional_param('new_weblog_post'));
        $post->access = trim(optional_param('new_weblog_access'));
        $post->icon = optional_param('new_weblog_icon',user_info("icon",$_SESSION['userid']),PARAM_INT);
        if (logged_on && !empty($post->body) && !empty($post->access) && run("permissions:check", "weblog")) {
            $post->posted = time();
            $post->owner = $USER->ident;
            $post->weblog = $page_owner;

            $post = plugin_hook("weblog_post","create",$post);

            if (!empty($post)) {
                $insert_id = insert_record('weblog_posts',$post);
                $post->ident = $insert_id;
                $value = trim(optional_param('new_weblog_keywords'));
                insert_tags_from_string ($value, 'weblog', $insert_id, $post->access, $post->owner);

                $extra_value = trim(optional_param('new_weblog_extra'));
                $post->extra_value = $extra_value;

                $post = plugin_hook("weblog_post","publish",$post);
                $rssresult = run("weblogs:rss:publish", array($page_owner, false));
                $rssresult = run("profile:rss:publish", array($page_owner, false));
                if (user_type($page_owner) == "person") {
                    $messages[] = __gettext("Your post has been added to your weblog.");
                }
            }
            // define('redirect_url',url . $_SESSION['username'] . "/weblog/");
            define('redirect_url',url . user_info("username",$page_owner) . "/$extensionContext/");
        } else {
            $messages[] = __gettext("Your post wasn't added to the blog. This was probably because it was empty, or you don't currently have permission to post in this blog.");
        }
        break;

    // Edit a weblog post
    case "weblogs:post:edit":
        $post = new StdClass;
        $post->title = trim(optional_param('edit_weblog_title'));
        $post->body = trim(optional_param('new_weblog_post'));
        $post->access = trim(optional_param('edit_weblog_access'));
        $post->icon = optional_param('edit_weblog_icon',user_info("icon",$_SESSION['userid']),PARAM_INT);
        $post->ident = optional_param('edit_weblog_post_id',0,PARAM_INT);
        if (logged_on && !empty($post->body) && !empty($post->access) && !empty($post->ident)) {
            $exists = false;
            if ($oldpost = get_record('weblog_posts','ident',$post->ident)) {
                if (run("permissions:check", array("weblog:edit", $oldpost->owner))) {
                    $exists = true;
                }
            }

            if (!empty($exists)) {
                $post->posted = $oldpost->posted;
                $post->owner = $oldpost->owner;
                $post->weblog = $oldpost->weblog;
                $post = plugin_hook("weblog_post","update",$post);
                if (!empty($post)) {
                    update_record('weblog_posts',$post);
                    delete_records('tags','tagtype','weblog','ref',$post->ident);
                    $value = trim(optional_param('edit_weblog_keywords'));
                    insert_tags_from_string ($value, 'weblog', $post->ident, $post->access, $oldpost->owner);
                    $post = get_record('weblog_posts','ident',$post->ident);

                    $extra_value = trim(optional_param('new_weblog_extra'));
                    $post->extra_value = $extra_value;

                    $post = plugin_hook("weblog_post","republish",$post);
                    $rssresult = run("weblogs:rss:publish", array($oldpost->weblog, false));
                    $rssresult = run("profile:rss:publish", array($oldpost->weblog, false));
                    $messages[] = __gettext("The weblog post has been modified."); // gettext variable
                }
            }
            define('redirect_url',url . user_info("username",$page_owner) . "/$extensionContext/" . $post->ident . ".html");
        }
        break;

    //Mark a weblog post as interesting
    case "weblog:interesting:on":
        $weblog_post = optional_param('weblog_post',0,PARAM_INT);
        if (logged_on && !empty($weblog_post)) {
            $wl = new StdClass;
            $wl->weblog_post = $weblog_post;
            $wl->owner = $USER->ident;
            if (insert_record('weblog_watchlist',$wl)) {
                $messages[] = __gettext("This weblog post has now been added to your 'interesting' list.");
            }
            define('redirect_url',url . user_info("username",$page_owner) . "/$extensionContext/" . $weblog_post . ".html");
        }
        break;

    //Remove an 'interesting' flag
    case "weblog:interesting:off":
        $weblog_post = optional_param('weblog_post',0,PARAM_INT);
        if (logged_on && !empty($weblog_post)) {
            if (delete_records('weblog_watchlist','weblog_post',$weblog_post,'owner',$USER->ident)) {
                $messages[] = __gettext("You are no longer monitoring this weblog post.");
            }
            define('redirect_url',url . user_info("username",$page_owner) . "/$extensionContext/" . $weblog_post . ".html");
        }
        break;

    // Delete a weblog post
    case "delete_weblog_post":
        $id = optional_param('delete_post_id',0,PARAM_INT);
        if (logged_on && !empty($id)) {
            if ($post_info = get_record('weblog_posts','ident',$id)) {
                if (run("permissions:check", array("weblog:edit", $post_info->owner))) {
                    $post_info = plugin_hook("weblog_post","delete",$post_info);
                    if (!empty($post_info)) {
                        delete_records('weblog_posts','ident',$id);
                        delete_records('weblog_comments','post_id',$id);
                        delete_records('weblog_watchlist','weblog_post',$id);
                        delete_records('tags','tagtype','weblog','ref',$id);
                        $rssresult = run("weblogs:rss:publish", array($post_info->weblog, false));
                        $rssresult = run("profile:rss:publish", array($post_info->weblog, false));
                        $messages[] = __gettext("The selected weblog post was deleted."); // gettext variable - NOT SURE ABOUT THIS POSITION!!!
                    }
                } else {
                    $messages[] = __gettext("You do not appear to have permission to delete this weblog post. It was not deleted."); // gettext variable
                }
            }
            global $redirect_url;
            $redirect_url = url . user_info('username', $post_info->weblog) . "/$extensionContext/";
            define('redirect_url',$redirect_url);
        }
        break;

    // Create a weblog comment
    case "weblogs:comment:add":
        $comment = new StdClass;
        $comment->post_id = optional_param('post_id',0,PARAM_INT);
        $comment->body = trim(optional_param('new_weblog_comment'));
        $comment->postedname = trim(optional_param('postedname'));
        $commentbackup = $comment;
        if (!empty($comment->post_id) && !empty($comment->body) && !empty($comment->postedname)) {
            $where = run("users:access_level_sql_where",$USER->ident);
            if ($post = get_record_select('weblog_posts','('.$where.') AND ident = '.$comment->post_id)) {
                if (run("spam:check",$comment->body) != true) {
                    // If we're logged on or comments are public, add one
                    if (logged_on || (!$CFG->disable_publiccomments && user_flag_get("publiccomments",$post->owner)) ) {
                        $comment->owner = $USER->ident;
                        $comment->posted = time();
                        $comment = plugin_hook("weblog_comment","create",$comment);
                        if (!empty($comment)) {
                            $insert_id = insert_record('weblog_comments',$comment);
                            $comment->ident = $insert_id;
                            $comment = plugin_hook("weblog_comment","publish",$comment);

                            // If we're logged on and not the owner of this post, add post to our watchlist
                            if (logged_on && $comment->owner != $post->owner) {
                                delete_records('weblog_watchlist','weblog_post',$comment->post_id,'owner',$comment->owner);
                                $wl = new StdClass;
                                $wl->owner = $comment->owner;
                                $wl->weblog_post = $comment->post_id;
                                insert_record('weblog_watchlist',$wl);
                            }

                            // Email comment if applicable
                            if ($comment->owner != $post->owner) {
                                $message = sprintf(__gettext("You have received a comment from %s on your blog post '%s'. It reads as follows:"), $comment->postedname, stripslashes($post->title));
                                $message .= "\n\n" . stripslashes($comment->body) . "\n\n";
                                $message .= sprintf(__gettext("To reply and see other comments on this blog post, click here: %s"), $CFG->wwwroot . user_info("username",$post->weblog) . "/weblog/" . $post->ident . ".html");
                                $message = wordwrap($message);
                                message_user($post->owner,$comment->owner,stripslashes($post->title),$message);
                                $messages[] = __gettext("Your comment has been added."); // gettext variable
                            }
                            
	                        // If river plugin installed then note comment
							if (function_exists('river_save_event'))
							{
								$un = user_info("username", $comment->owner);
								
								$commenturl = $CFG->wwwroot."$un/weblog/{$comment->post_id}.html#cmt{$comment->ident}";
								$username = "<a href=\"" . river_get_userurl($comment->owner) . "\">$un</a>";
								if (!isset($comment->owner)) 
								{
									$comment->owner = -1;
									$username = __gettext("Anonymous user");
								}
					
								river_save_event($comment->owner, $comment->ident, $comment->owner, "weblog_post::post", $username . " <a href=\"$commenturl\">" . __gettext("commented on") . "</a> " . river_get_friendly_id("weblog_post::post", $comment->post_id));
								
							}
                        }
                    }
                } else {
                    $messages[] = __gettext("Your comment could not be posted. The system thought it was spam.");
                }
                define('redirect_url',url . user_info("username",$post->owner) . "/$extensionContext/" . $commentbackup->post_id . ".html");
            }
        }
        break;


    // Delete a weblog comment
    case "weblog_comment_delete":
        $comment_id = optional_param('weblog_comment_delete',0,PARAM_INT);
        if (logged_on && !empty($comment_id)) {
            $commentinfo = get_record_sql('SELECT wc.*,wp.owner AS postowner,wp.ident AS postid
                                           FROM '.$CFG->prefix.'weblog_comments wc
                                           LEFT JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = wc.post_id
                                            WHERE wc.ident = ' . $comment_id);
            $commentinfo = plugin_hook("weblog_comment","delete",$commentinfo);
            if (!empty($commentinfo)) {
                if ($commentinfo->owner == $USER->ident || run("permissions:check", "weblog")) {
                    delete_records('weblog_comments','ident',$comment_id);
                    $messages[] = __gettext("Your comment was deleted.");
                }
            }
            $redirect_url = url . user_info('username', $commentinfo->postowner) . "/$extensionContext/" . $commentinfo->postid . ".html";
            define('redirect_url',$redirect_url);
        }
        break;
}

if (defined('redirect_url')) {

    $_SESSION['messages'] = $messages;
    header("Location: " . redirect_url);
    exit;

}

?>

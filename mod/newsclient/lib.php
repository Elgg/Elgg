<?php

function newsclient_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;

    $page_owner = $profile_id;
    $rss_username = user_info('username', $page_owner);

    if (isloggedin()) {
        if (defined("context") && context == "resources" && $page_owner == $_SESSION['userid']) {
            $PAGE->menu[] = array( 'name' => 'feeds',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/feeds/\" class=\"selected\" >" .__gettext("Your Resources").'</a></li>');
        } else {
            $PAGE->menu[] = array( 'name' => 'feeds',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/feeds/\" >" .__gettext("Your Resources").'</a></li>');
        }
    }

    if (defined("context") && context == "resources") {
    
        if ($page_owner != -1) {
            $PAGE->menu_sub[] = array( 'name' => 'newsfeed:subscription',
                                       'html' => a_href( $CFG->wwwroot.$rss_username."/feeds/", 
                                                          __gettext("Feeds")));
            if (permissions_check("profile",$page_owner) && isloggedin()) {
                $PAGE->menu_sub[] = array( 'name' => 'newsfeed:subscription:publish:blog',
                                           'html' => a_href( $CFG->wwwroot."_rss/blog.php?profile_name=" . user_info("username",$page_owner), 
                                                              __gettext("Publish to blog")));
            }
            $PAGE->menu_sub[] = array( 'name' => 'newsclient',
                                       'html' => a_href( $CFG->wwwroot.$rss_username."/feeds/all/", 
                                                          __gettext("View aggregator")));
        }
        $PAGE->menu_sub[] = array( 'name' => 'feed',
                                   'html' => a_href( $CFG->wwwroot."_rss/popular.php",
                                                      __gettext("Popular Feeds")));

        /*
        $PAGE->menu_sub[] = array( 'name' => 'feed',
                                   'html' => a_href( $CFG->wwwroot."help/feeds_help.php", 
                                                      "Page help"));
        */

    }
}

function newsclient_cron() {
    global $CFG;

    // if we've run in the last 5 mins, skip it
    if (!empty($CFG->newsclient_lastcron) && (time() - 300) < $CFG->newsclient_lastcron) {
        return true;
    }
    
    run("weblogs:init");
    run("profile:init");
    run("rss:init");
    
    define('context','resources');
    
    // this doesn't need running every 5 mins
    if (empty($CFG->newsclient_lastcronprune)) {
        set_config('newsclient_lastcronprune',time());
    }
    if ((time() - 86400) >= $CFG->newsclient_lastcronprune) {
        run('rss:prune');
        set_config('newsclient_lastcronprune',time());
    }
    
    run("rss:update:all:cron");

    set_config('newsclient_lastcron',time());
    
    
}

function newsclient_init() {
    global $CFG,$function;
    
	// Magpie unit for Elgg
	// ben@elgg.net Oct 17, 2005

	// Library functions
	require_once ($CFG->dirroot . "mod/newsclient/lib/library.php");

	// Load default template
	$function['init'][] = $CFG->dirroot . "mod/newsclient/lib/default_template.php";

	// Initialise RSS parser
	$function['rss:init'][] = $CFG->dirroot . "mod/newsclient/lib/function_init.php";
	$function['rss:init'][] = $CFG->dirroot . "mod/newsclient/lib/function_actions.php";
	// Get current contents of a feed (raw)
	$function['rss:get'][] = $CFG->dirroot . "mod/newsclient/lib/function_get.php";
	// Display a user's subscriptions
	$function['rss:subscriptions'][] = $CFG->dirroot . "mod/newsclient/lib/function_subscriptions.php";
	// Allow a user to publish feeds to their blog
	$function['rss:subscriptions:publish:blog'][] = $CFG->dirroot . "mod/newsclient/lib/function_subscriptions_publish_to_blog.php";
	// Load variable containing all subscriptions for a user
	$function['rss:subscriptions:get'][] = $CFG->dirroot . "mod/newsclient/lib/function_get_subscriptions.php";
	// Display the most popular subscriptions
	$function['rss:subscriptions:popular'][] = $CFG->dirroot . "mod/newsclient/lib/function_subscriptions_popular.php";
	// Update a feed by ID
	$function['rss:update'][] = $CFG->dirroot . "mod/newsclient/lib/function_update.php";
	// Update all feeds by user
	$function['rss:update:all'][] = $CFG->dirroot . "mod/newsclient/lib/function_update_all.php";
	// Update all feeds in system (for use with cron job)
	$function['rss:update:all:cron'][] = $CFG->dirroot . "mod/newsclient/lib/function_update_all_cron.php";

	// Permissions check
	$function['permissions:check'][] = $CFG->dirroot . "mod/newsclient/lib/permissions_check.php";

	// View a user's posts
	$function['rss:view'][] = $CFG->dirroot . "mod/newsclient/lib/function_view.php";
	$function['rss:view:feed'][] = $CFG->dirroot . "mod/newsclient/lib/function_view_individual.php";
	$function['rss:view:post'][] = $CFG->dirroot . "mod/newsclient/lib/function_view_post.php";

	// Is the current user subscribed to a feed?
	$function['rss:subscribed'][] = $CFG->dirroot . "mod/newsclient/lib/function_is_subscribed.php";

	// Prune feed posts older than a configured age
	$function['rss:prune'][] = $CFG->dirroot . "mod/newsclient/lib/function_prune.php";

    // Delete users
    listen_for_event("user","delete","newsclient_user_delete");
    
    //$CFG->widgets->display['feed'] = "newsclient_widget_display";
    //$CFG->widgets->edit['feed'] = "newsclient_widget_edit";
    $CFG->widgets->list[] = array(
                                        'name' => __gettext("Feed widget"),
                                        'description' => __gettext("Displays the latest entries from an external feed of your choice."),
                                        'type' => "newsclient::feed"
                                );
    
}

function newsclient_widget_display($widget) {
    
    global $CFG;
    $body = "";
    $title = "";
        
    $feed_id = widget_get_data("feed_id",$widget->ident);
    $feed_posts = widget_get_data("feed_posts",$widget->ident);
    if (empty($feed_posts)) {
        $feed_posts = 1;
    }
    
    if (!empty($feed_id)) {
        
        if ($posts = get_records_sql("SELECT fp.*,f.name,f.siteurl,f.tagline FROM ".$CFG->prefix."feed_posts fp
                      JOIN ".$CFG->prefix."feeds f ON f.ident = fp.feed
                      WHERE f.ident = $feed_id ORDER BY fp.added DESC, fp.ident ASC limit $feed_posts")) {
                          
            foreach($posts as $post) {
                $body .= "<h2><a href=\"" .$post->url . "\">". $post->title . "</a></h2>" . $post->body;
            }

            $title = $post->name;

        } else {
            
            $body .= "<p>" . __gettext("This feed is currently empty.") . "</p>";
            
        }
        
      
    } else {
        
        $body .= "<p>" . __gettext("This feed widget is undefined.") . "</p>";
        
    }
    
    return array('title'=>$title,'content'=>$body);
    
}

function newsclient_widget_edit($widget) {
    
    global $CFG, $page_owner;
    
    $feed_id = widget_get_data("feed_id",$widget->ident);
    $feed_posts = widget_get_data("feed_posts",$widget->ident);
    if (empty($feed_posts)) {
        $feed_posts = 1;
    }
    
    $body = "<h2>" . __gettext("Feeds widget") . "</h2>";
    $body .= "<p>" . __gettext("This widget displays the last couple of entries from an external feed you have subscribed to. To begin, select the feed from your subscriptions below:") . "</p>";
                
    $feed_subscriptions = newsclient_get_subscriptions_user($page_owner, true);
    
    if (is_array($feed_subscriptions) && !empty($feed_subscriptions)) {
        
        $body .= "<p><select name=\"widget_data[feed_id]\">\n";
        foreach ($feed_subscriptions as $subscription) {
            if ($subscription->ident == $feed_id) {
                $selected = "selected=\"selected\"";
            } else {
                $selected = "";
            }
            $body .= "<option value=\"" . $subscription->ident . "\" $selected>" . $subscription->name . "</option>\n";
        }
        $body .= "</select></p>\n";
        
        $body .= "<p>" . __gettext("Then enter the number of feed entries you'd like to display:") . "</p>";
        
        $body .= "<p><input type=\"text\" name=\"widget_data[feed_posts]\" value=\"" . $feed_posts . "\" /></p>";
        
    } else {
        
        $body .= "<p>" . sprintf(__gettext("You can't select a feed for this widget because you don't have any feed subscriptions. Click on <a href=\"%s\">Your</a> Resources to subscribe to a feed."),$CFG->wwwroot . $_SESSION['username'] . "/feeds/") . "</p>";
        
    }
    
    return $body;
    
}


// return an array of feed subscriptions for a given userid
// if joined is true, return feed detail etc, otherwise just return feed_subscriptions rows
function newsclient_get_subscriptions_user($userid, $joined = false) {
    
    global $CFG;
    
    $userid = (int) $userid;
    if (empty($joined)) {
        
        $feed_subscriptions = get_records('feed_subscriptions', 'user_id', $userid);
        
    } else {
        
        $feed_subscriptions = get_records_sql('SELECT fs.ident AS subid, fs.autopost, fs.autopost_tag, f.* FROM '.$CFG->prefix.'feed_subscriptions fs
            JOIN '.$CFG->prefix.'feeds f ON f.ident = fs.feed_id
            WHERE fs.user_id = ? ORDER BY f.name ASC',array($userid));
        
    }
    
    return $feed_subscriptions;
}

function newsclient_user_delete($object_type, $event, $object) {
    global $CFG, $data;
    if (!empty($object->ident) && $object_type == "user" && $event == "delete") {
        delete_records('feed_subscriptions','user_id',$object->ident);
    }
    return $object;
}


?>

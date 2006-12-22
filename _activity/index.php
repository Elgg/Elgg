<?php
global $CFG;
//    ELGG recent activity page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

// Initialise functions for user details, icon management and profile management
run("profile:init");

// Whose friends are we looking at?
global $page_owner;

// Weblog context
define("context", "weblog");

// You must be logged on to view this!
protect(1);

templates_page_setup();

cleanup_messages(time() - (86400 * 30));

$title = run("profile:display:name") . " :: " . __gettext("Recent activity");

// If we haven't specified a start time, start time = 1 day ago
$starttime = optional_param('starttime',time()-86400,PARAM_INT);

$body = "<p>" . __gettext("Currently viewing recent activity since ") . gmstrftime("%B %d, %Y", $starttime) . ".</p>";

$body .= "<p>" . __gettext("You may view recent activity during the following time-frames:") . "</p>";

$body .= "<ul><li><a href=\"index.php?starttime=" . (time() - 86400) . "\">" . __gettext("The last 24 hours") . "</a></li>";
$body .= "<li><a href=\"index.php?starttime=" . (time() - (86400 * 2)) . "\">" . __gettext("The last 48 hours") . "</a></li>";
$body .= "<li><a href=\"index.php?starttime=" . (time() - (86400 * 7)) . "\">" . __gettext("The last week") . "</a></li>";
$body .= "<li><a href=\"index.php?starttime=" . (time() - (86400 * 30)) . "\">" . __gettext("The last month") . "</a></li></ul>";

$body .= "<h2>" . __gettext("Your recent messages") . "</h2>";

$user_messages = get_messages($page_owner,null,(time() - $starttime));

if (is_array($user_messages) && !empty($user_messages)) {
    foreach($user_messages as $user_message) {
        $body .= "<div class=\"user_message\">" . display_message($user_message) . "</div>";
        
    }
}

$body .= "<h2>" . __gettext("Activity on weblog posts you have marked as interesting") . "</h2>";

if ($activities = get_records_sql('SELECT wc.*, u.username, u.name as weblogname, wp.weblog, wp.ident AS weblogpost, wp.title AS weblogtitle, wp.weblog AS weblog 
                                    FROM '.$CFG->prefix.'weblog_comments wc 
                                    LEFT JOIN '.$CFG->prefix.'weblog_watchlist wl ON wl.weblog_post = wc.post_id 
                                    LEFT JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = wc.post_id 
                                    LEFT JOIN '.$CFG->prefix.'users u ON u.ident = wp.weblog 
                                    WHERE wc.posted > ? AND wl.owner = ?
                                    ORDER BY wc.posted DESC',array($starttime, $page_owner))) {
    foreach($activities as $activity) {
        $commentbody = stripslashes($activity->body);
        $commentbody .= "<br /><br /><a href=\"" . url . $activity->username . "/weblog/" . $activity->weblogpost . ".html\">" . __gettext("Read more") . "</a>";
        $activity->postedname = stripslashes($activity->postedname);
        $activity->weblogname = stripslashes($activity->weblogname);
        if ($activity->weblog == $USER->ident) {
            $activity->weblogname = __gettext("your blog");
        }
        if ($activity->owner == $USER->ident) {
            $commentposter = sprintf(__gettext("<b>You</b> commented on weblog post '%s' in %s:"), stripslashes($activity->weblogtitle), $activity->weblogname);
        } else {
            $commentposter = sprintf(__gettext("<b>%s</b> commented on weblog post '%s' in %s:"), $activity->postedname, stripslashes($activity->weblogtitle), $activity->weblogname);
        }
        $body .= templates_draw(array(
                                        'context' => 'databox1',
                                        'name' => $commentposter,
                                        'column1' => $commentbody
                                      )
                                );
    }
} else {
    $body .= "<p>" . __gettext("No activity during this time period.") . "</p>";
}

$body = templates_draw(array(
                             'context' => 'contentholder',
                             'title' => $title,
                             'body' => $body
                             )
                       );

echo templates_page_draw( array(
                                  $title, $body
                                  )
         );

?>
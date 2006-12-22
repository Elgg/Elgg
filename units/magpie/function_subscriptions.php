<?php
global $USER,$CFG,$page_owner;
$body = '';

if (logged_on) {
    
    $displayuser = (int) $parameter;
    
    //global $rss_subscriptions;
    //run("rss:subscriptions:get");
    
    run("rss:update:all",$displayuser);
    if ($USER->ident == $displayuser) {
        $body .= "<p>". __gettext("Feeds are information streams from other sites. You will often see a link to an 'RSS' feed while browsing; enter the link address into the 'add feed' box at the bottom of the page to read that information from within your learning landscape.") . "</p>";
    }
    if ($feed_subscriptions = get_records_sql('SELECT fs.ident AS subid, fs.autopost, fs.autopost_tag, f.* FROM '.$CFG->prefix.'feed_subscriptions fs
                                              JOIN '.$CFG->prefix.'feeds f ON f.ident = fs.feed_id
                                              WHERE fs.user_id = ? ORDER BY f.name ASC',array($displayuser))) {
        if (run("permissions:check", "profile")) {
            $body .= "<form action=\"\" method=\"post\" >";
        }
        
        $body .= templates_draw(array(
                                      'context' => 'adminTable',
                                      'name' => "<b>" . __gettext("Last updated") . "</b>",
                                      'column1' => "<b>" . __gettext("Resource name") . "</b>",
                                      'column2' => "&nbsp;"
                                      )
                                );
                                
        foreach($feed_subscriptions as $feed) {
            $name = "<a href=\"".$feed->siteurl."\">" . stripslashes($feed->name) . "</a>";
            $column2 = "<a href=\"".url."_rss/individual.php?feed=".$feed->ident."\">". __gettext("View content") . "</a>";
            // this allows unsubscribing the *displayed* user from the feed (if you're admin), as opposed
            // to the popular feeds page, which allows you to unsubscribe yourself from a feed.
            // I think.
            // maybe.
            // Love, Sven
            if (run("permissions:check", "profile")) {
                $column2 .= " | <a href=\"".url."_rss/subscriptions.php?action=unsubscribe&amp;feed=".$feed->ident
                    ."\" onclick=\"return confirm('".__gettext("Are you sure you want to unsubscribe from this feed?")."')\">" . __gettext("Unsubscribe") . "</a>";
            }
            
            $body .= templates_draw(array(
                                          'context' => 'adminTable',
                                          'name' => strftime("%B %d %Y, %H:%M",$feed->last_updated),
                                          'column1' => $name,
                                          'column2' => $column2
                                          )
                                    );
            
        }
        
        if (run("permissions:check", "profile")) {
            
            $body .= templates_draw( array(
                                                'context' => 'adminTable',
                                                'name' => "<input type=\"hidden\" name=\"action\" value=\"rss:subscriptions:update\" />",
                                                'column1' => "<input type=\"submit\" value=\"" . __gettext("Update") . "\" />",
                                                'column2' => ""
                                            )
                                            );
            
            $body .= "</form>";
        }
        
    } else {
        if ($_SESSION['userid'] == $displayuser) {
            $body .= "<p>" . __gettext("You are not subscribed to any feeds.") . "</p>";
        } else {
            $body .= "<p>" . __gettext("No feeds were found.") . "</p>";
        }
    }
        
    if (run("permissions:check", "profile")) {
        $body .= "<p>". __gettext("To subscribe to a new feed, enter its address below:") . "</p>";
        $body .= "<form action=\"\" method=\"post\">";
        $body .= templates_draw(array(
                                      'context' => 'adminTable',
                                      'name' => "&nbsp;",
                                      'column1' => "<input type=\"text\" name=\"url\" value=\"http://\" style=\"width: 100%\" />",
                                      'column2' => "<input type=\"submit\" value=\"".__gettext("Subscribe") . "\" />"
                                      )
                                );
        $body .= "<input type=\"hidden\" name=\"action\" value=\"subscribe-new\" /></form>";
    }
    $run_result .= $body;
    
}

?>
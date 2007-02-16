<?php

global $USER,$CFG,$page_owner;
$body = '';

if (logged_on) {
    
    if ($USER->ident == $page_owner) {
        $body .= "<p>". __gettext("Click a box below to automatically import content from a feed into your blog. You can also add default keywords for content from that feed. (You should only do this if you have the legal right to use this resource - in other words, you must have permission from the content owner, as the feed's content will appear in your blog as if you had written it.)") . "</p>";
    }
    if ($feed_subscriptions = newsclient_get_subscriptions_user($page_owner, true)) {
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
            if (run("permissions:check", "profile")) {
                $name = "<input type=\"checkbox\" name=\"feedautopost[]\" value=\"" . $feed->subid . "\" ";
                $name .= "onclick=\"if (this.checked) return confirm('" . __gettext("Are you sure you want to import this content into your personal blog?\\nYou should make sure you own it or have permission from the copyright holder.") . "')\" ";
                if ($feed->autopost == "yes") {
                    $name .= " checked=\"checked\"";
                }
                $name .= " />";
            }
            $name .= "
                <a href=\"".$feed->siteurl."\">" . stripslashes($feed->name) . "</a>
                <a href=\"".$feed->url."\"><img src=\"{$CFG->wwwroot}mod/template/icons/rss.png\" border=\"0\" alt=\"rss\" /></a>
            ";
            if (run("permissions:check", "profile")) {
                $name .= "<br />";
                $name .= __gettext("Keywords: ") . "<input type=\"text\" name=\"keywords[" . $feed->subid . "]\" value=\"" . htmlspecialchars($feed->autopost_tag) . "\" />";
            }
            $column2 = "<a href=\"".url."_rss/individual.php?feed=".$feed->ident."\">". __gettext("View content") . "</a>";
            
            $body .= templates_draw(array(
                                          'context' => 'adminTable',
                                          'name' => strftime("%B %d %Y, %H:%M",$feed->last_updated),
                                          'column1' => $name,
                                          'column2' => $column2
                                          )
                                    );
        }
        
        if (run("permissions:check", "profile")) {
            
            $body .= templates_draw(array(
                                          'context' => 'adminTable',
                                          'name' => "<input type=\"hidden\" name=\"action\" value=\"rss:subscriptions:update\" />",
                                          'column1' => "<input type=\"submit\" value=\"" . __gettext("Update") . "\" />",
                                          'column2' => ""
                                          )
                                    );
            
            $body .= "</form>";
        }
        
    } else {
        if ($_SESSION['userid'] == $page_owner) {
            $body .= "<p>" . __gettext("You are not subscribed to any feeds.") . "</p>";
        } else {
            $body .= "<p>" . __gettext("No feeds were found.") . "</p>";
        }
    }
    
    $run_result .= $body;
    
}

?>
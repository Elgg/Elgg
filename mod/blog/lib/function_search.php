<?php
global $CFG, $USER, $db, $PAGE;
global $search_exclusions;

$weblogPosted = __gettext("Weblog posts by"); // gettext variable
$inCategory = __gettext("in category"); // gettext variable
$rssForBlog = __gettext("RSS feed for weblog posts by"); // gettext variable
$otherUsers = __gettext("Other users with weblog posts in category"); // gettext variable
$otherUsers2 = __gettext("Users with weblog posts in category"); // gettext variable

$handle = 1;

if (!empty($PAGE->returned_items) && $PAGE->returned_items == "accounts") {
    $handle = 0;
}

if ((isset($parameter) && $parameter[0] == "weblog" || $parameter[0] == "weblogall") && $handle) {

    if ($parameter[0] == "weblog") {
        $search_exclusions[] = "weblogall";
        $owner = optional_param('owner',0,PARAM_INT);
        $searchline = "tagtype = 'weblog' AND owner = $owner AND tag = " . $db->qstr($parameter[1]) . "";
        $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") AND " . $searchline;
        $searchline = str_replace("owner","t.owner",$searchline);
        if ($refs = get_records_select('tags t',$searchline)) {
            $searchline = "";
            foreach($refs as $ref) {
                if ($searchline != "") {
                    $searchline .= ",";
                }
                $searchline .= $ref->ref;
            }
            $searchline = " wp.ident in (" . $searchline . ")";
            if (!empty($PAGE->search_type_unformatted)) {
                $searchline .= " AND p.user_type = " . $PAGE->search_type;
            }
            if (!$posts = get_records_sql('SELECT wp.ident,u.name,u.username,u.ident as userid, wp.title, wp.ident, wp.weblog, wp.owner, wp.posted
                                     FROM '.$CFG->prefix.'weblog_posts wp JOIN '.$CFG->prefix.'users u ON u.ident = wp.owner
                                     JOIN '.$CFG->prefix.'users p on p.ident = wp.weblog
                                     WHERE ('.$searchline.') ORDER BY posted DESC')) {
                $posts = array(); // avoid warnings
            }
             // TODO I don't like this, but I can't understand why it's there, so I'm leaving it.
            $name = '';
            $username = '';
            if (count($posts) >= 1) {
                $keys = array_keys($posts);
                $p = $posts[$keys[0]];
                if (!empty($p)) {
                    // $name = stripslashes($p->name);
                    $name = run("profile:display:name",$p->userid);
                    $username = $p->username;
                }
            }
            $run_result .= "<h2>$weblogPosted " . $name . " $inCategory '".$parameter[1]."'</h2>\n<ul>";
            foreach($posts as $post) {
                $run_result .= "<li>";
                $weblogusername = user_info('username', $post->weblog);
                $run_result .= "<a href=\"".url . $weblogusername . "/weblog/" . $post->ident . ".html\">" . gmstrftime("%B %d, %Y",$post->posted) . " - " . stripslashes($post->title) . "</a>\n";
                if ($post->owner != $post->weblog) {
                    $run_result .= " @ " . "<a href=\"" . url . $weblogusername . "/weblog/\">" . $weblogusername . "</a>\n";
                }
                $run_result .= "</li>";
            }
            $run_result .= "</ul>";
            $run_result .= "<p><small>[ <a href=\"".url.$username . "/weblog/rss/" . $parameter[1] . "\">$rssForBlog " . $name . " $inCategory '".$parameter[1]."'</a> ]</small></p>\n";
        }
    } else {
        $icon = "default.png";
    }
    $searchline = "tagtype = 'weblog' and tag = " . $db->qstr($parameter[1]) . "";
    $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") AND " . $searchline;
    $searchline = str_replace("owner","t.owner",$searchline);
    if (!empty($PAGE->search_type_unformatted)) {
            $searchline .= " AND u.user_type = " . $PAGE->search_type;
        }
    $sql = "SELECT DISTINCT u.* FROM ".$CFG->prefix.'tags t JOIN '.$CFG->prefix.'users u ON u.ident = t.owner WHERE ('.$searchline.')';
    if ($parameter[0] == "weblog") {
        $sql .= " and u.ident != " . $owner;
    }
    if ($users = get_records_sql($sql)) {
        if ($parameter[0] == "weblog") {
            $run_result .= "<h2>$otherUsers '".$parameter[1]."'</h2>\n";
        } else {
            $run_result .= "<h2>$otherUsers2 '".$parameter[1]."'</h2>\n";
        }
        $body = "<table><tr>";
        $i = 1;
        $w = 100;
        if (sizeof($users) > 4) {
            $w = 50;
        }
        foreach($users as $key => $info) {
            $friends_userid = $info->ident;
            $friends_name = user_name($info->ident);
            $info->icon = run("icons:get",$info->ident);
            $friends_menu = run("users:infobox:menu",array($info->ident));
            $link_keyword = urlencode($parameter[1]);
            $friends_icon = user_icon_html($info->ident,$w);
            $body .= <<< END
        <td align="center">
            <p>
            <a href="{$CFG->wwwroot}search/index.php?weblog={$link_keyword}&amp;owner={$friends_userid}">
            {$friends_icon}</a><br />
            <span class="userdetails">
                {$friends_name}
                {$friends_menu}
            </span>
            </p>
        </td>
END;
                if ($i % 5 == 0) {
                    $body .= "\n</tr><tr>\n";
                }
                $i++;
        }
        $body .= "</tr></table>";
        $run_result .= $body;
    }

}

?>
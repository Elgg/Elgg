<?php
global $CFG;
global $page_owner;
global $profile_id;

if ($page_owner != -1 && (user_type($page_owner) == "person" || user_type($page_owner) == "external")) {
    $friends = array();
    if ($result = get_records_sql('SELECT DISTINCT u.ident,1 FROM '.$CFG->prefix.'friends f
                                   JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                   WHERE f.owner = ? AND u.user_type = ? order by u.last_action desc LIMIT 8',array($page_owner,'person'))) {
        foreach($result as $row) {
            $friends[] = (int) $row->ident;
        }
    }
        $run_result .= "<li id=\"sidebar_friends\">";
    if ($page_owner != $_SESSION['userid']) {
        $run_result .= run("users:infobox",
                           array(
                                 __gettext("Friends"),
                                 $friends,
                                 "<a href=\"".url."mod/friend/?owner=$profile_id\">[" . __gettext("View all Friends") . "]</a>"
                                 )
                           );

    } else {
        $run_result .= run("users:infobox",
                           array(
                                 __gettext("Your Friends"),
                                 $friends,
                                 "<a href=\"".url.$_SESSION['username']."/friends/\">[" . __gettext("View all Friends") . "]</a>"
                                 )
                           );
    }
        $run_result .= "</li>";

}

?>
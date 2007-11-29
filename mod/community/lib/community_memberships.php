<?php
global $CFG;
global $page_owner;
 
if ($page_owner != -1) {
    if (user_type($page_owner) == "person" || user_type($page_owner) == "external") {
        if ($result = get_records_sql('SELECT DISTINCT u.ident,u.username,u.name FROM '.$CFG->prefix.'friends f
                                       JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                       WHERE f.owner = ? AND u.user_type = ? AND u.owner != ?',
                                      array($page_owner,'community',$page_owner))) {
            $body = "<ul>";
            foreach($result as $row) {
                $row->name = run("profile:display:name",$row->ident);
                $body .= "<li><a href=\"" . url . $row->username . "/\">" . $row->name . "</a></li>";
            }
            $body .= "</ul>";
            $run_result .= "<li id=\"community_membership\">";
            $run_result .= templates_draw(array(
                                                'context' => 'sidebarholder',
                                                'title' => __gettext("Community memberships"),
                                                'body' => $body
                                                )
                                          );
            $run_result .= "</li>";
        } else {
            $run_result .= "";
        }
    } else if (user_type($page_owner) == "community") {
        $friends = array();
        if ($result = get_records_sql('SELECT DISTINCT u.ident,1 FROM '.$CFG->prefix.'friends f
                                JOIN '.$CFG->prefix.'users u ON u.ident = f.owner
                                WHERE f.friend = ? LIMIT 8',array($page_owner))) {
            foreach($result as $row) {
                $friends[] = (int)$row->ident;
            }
        }
        //$CFG->wwwroot.$info->username."/community/members
        $run_result .= "<li id=\"community_membership\">";
        $run_result .= run("users:infobox",
                           array(
                                 __gettext("Members"),
                                 $friends,
                                 "<a href=\"".$CFG->wwwroot.user_info('username',$page_owner)."/community/members\">" . __gettext("Members") . "</a>"
                                 )
                           );
        $run_result .= "</li>";
    }
}


?>
<?php
global $CFG;
global $page_owner;

if ($page_owner != -1) {
    if (user_type($page_owner) == "person" || user_type($page_owner) == "external") {
        if ($result = run('community:membership:data',array($page_owner))) {
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
        if ($result = run('community:members:data',array($page_owner,8))) {
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
                                 "<a href=\"".$CFG->wwwroot.user_info('username',$page_owner)."/community/members\">[" . __gettext("View all members") . "]</a>"
                                 )
                           );
        $run_result .= "</li>";
    }
}


?>
<?php

// Given a user ID as a parameter, will display a list of communities

global $CFG;

if (isset($parameter[0])) {

    $user_id = (int) $parameter[0];
    $body = null;

    $result = get_records_select('users',"owner = ? AND user_type = ?",array($user_id,'community'));

    $i = 1;
    if (!empty($result)) {
        foreach($result as $key => $info) {
            $friends_name = user_name($info->ident);
            $info->icon = run("icons:get",$info->ident);
            $friends_menu = run("community:infobox:menu",array($info));
            $friends_icon = user_icon_html($info->ident,COMMUNITY_ICON_SIZE);
            $link = $CFG->wwwroot.$info->username."/";

            $body .= templates_draw(array(
                                        'context' => 'community_member',
                                        'name' => $friends_name,
                                        'icon' => $friends_icon,
                                        'link' => $link,
                                        'functions' => $friends_menu
                                      )
                        );
            if ($i % COMMUNITY_MEMBERS_PER_ROW == 0) {
                $body .= "</tr><tr>";
            }
            $i++;
        }
    } else {
        if ($user_id == $_SESSION['userid']) {
            $body .= "<td><p>". __gettext("You don't own any communities. Why not create one?") ."</p></td>";
        } else {
            $body .= "<td><p>". __gettext("This user is not currently moderating any communities.") ."</p></td>";
        }
    }


    $run_result = templates_draw(array(
                        'context' => 'community_members',
                        'members' => $body
                        )
                  );

}
?>
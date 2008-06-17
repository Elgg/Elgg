<?php
global $CFG;
// Given a user ID as a parameter, will display a list of friends

$body = '';
if (isset($parameter[0])) {

    $user_id = (int) $parameter[0];
    $friends = null;

    $result = get_records_sql('SELECT u.ident, u.username FROM '.$CFG->prefix.'friends f
                               JOIN '.$CFG->prefix.'users u ON u.ident = f.owner
                               WHERE friend = ? AND u.user_type = ? order by u.last_action desc',array($user_id,'person'));

    if (is_array($result)) {
        $numfriends = count($result);
        if ($numfriends > 1000) {
            $result = array_slice($result, 0, 1000);
            $body .= '<p>' . sprintf(__gettext("Displaying 1000 most recently active people, of %d found."), $numfriends) . '</p>';
        }
    }

    $i = 1;
    if (!empty($result)) {
        foreach($result as $key => $info) {
            $link = $CFG->wwwroot.$info->username."/";
            $friends_name = run("profile:display:name", $info->ident);
            $info->icon = run("icons:get",$info->ident);
            $friends_menu = run("users:infobox:menu",array($info->ident,"friendsof"));
            $friends_menu = run("users:infobox:delete",array($info->ident,"friendsof"));
            $friends_icon = user_icon_html($info->ident,FRIENDS_ICON_SIZE);
            $friends .= templates_draw(array(
                                        'context' => 'friends_friend',
                                        'name' => $friends_name,
                                        'icon' => $friends_icon,
                                        'link' => $link,
                                        'friend_menu' => $friends_menu
                                      )
                        );

            if ($i % FRIENDS_PER_ROW == 0) {
                $friends .= "</tr><tr>";
            }
            $i++;
        }
    } else {
        if ($user_id == $_SESSION['userid']) {
            $friends .=  "<td><p>" . __gettext("Nobody's listed you as a friend! Maybe you need to start chatting to some other users?") . "</p></td>";
        } else {
            $friends .= "<td><p>" . __gettext("This user isn't currently listed as anyone's friend. Maybe you could be the first?") . "</p></td>";
        }
    }
}

    $run_result = templates_draw(array(
                        'context' => 'friends_friends',
                        'friends' => $friends
                        )
                  );

?>
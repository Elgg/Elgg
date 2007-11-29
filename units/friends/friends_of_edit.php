<?php
global $CFG;
// Given a user ID as a parameter, will display a list of friends

$body = '';
if (isset($parameter[0])) {
    
    $user_id = (int) $parameter[0];
    
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
    
    $body .= <<< END

    <div class="networktable">
    <table>
        <tr>

END;
    $i = 1;
    if (!empty($result)) {
        $w = 100;
        if ($numfriends > 4) {
            $w = 50;
        }
        foreach($result as $key => $info) {
            $friends_name = run("profile:display:name", $info->ident);
            $info->icon = run("icons:get",$info->ident);
            $friends_menu = run("users:infobox:menu",array($info->ident));
            $friends_icon = user_icon_html($info->ident,$w);
            $body .= <<< END
        <td>
            <p>
            <a href="{$CFG->wwwroot}{$info->username}/">
            {$friends_icon}</a><br />
            <span class="userdetails">
                <a href="{$CFG->wwwroot}{$info->username}/">{$friends_name}</a>
                {$friends_menu}
            </span>
            </p>
        </td>
END;
            if ($i % 5 == 0) {
                $body .= "</tr><tr>";
            }
            $i++;
        }
    } else {
        if ($user_id == $_SESSION['userid']) {
            $body .=  "<td><p>" . __gettext("Nobody's listed you as a friend! Maybe you need to start chatting to some other users?") . "</p></td>";
        } else {
            $body .= "<td><p>" . __gettext("This user isn't currently listed as anyone's friend. Maybe you could be the first?") . "</p></td>";
        }
    }
    $body .= <<< END
    </tr>
    </table>
    </div>
END;
}

$run_result .= $body;

?>
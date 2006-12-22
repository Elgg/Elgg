<?php
global $CFG;
// Given a user ID as a parameter, will display a list of friends

if (isset($parameter[0])) {
    
    $user_id = (int) $parameter[0];
    
    $result = get_records_sql('SELECT u.* FROM '.$CFG->prefix.'friends f
                               JOIN '.$CFG->prefix.'users u ON u.ident = f.owner
                               WHERE friend = ? AND u.user_type = ? order by u.last_action desc',array($user_id,'person'));
    $body = <<< END

    <div class="networktable">
    <table>
        <tr>

END;
    $i = 1;
    if (!empty($result)) {
        foreach($result as $key => $info) {
            $w = 100;
            if (sizeof($result) > 4) {
                $w = 50;
            }
            // $friends_name = htmlspecialchars(stripslashes($info->name), ENT_COMPAT, 'utf-8');
            $friends_name = run("profile:display:name", $info->ident);
            $info->icon = run("icons:get",$info->ident);
            $friends_menu = run("users:infobox:menu",array($info->ident));
            $body .= <<< END
        <td>
            <p>
            <a href="{$CFG->wwwroot}{$info->username}/">
            <img src="{$CFG->wwwroot}_icon/user/{$info->icon}/w/{$w}" alt="{$friends_name}" border="0" /></a><br />
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
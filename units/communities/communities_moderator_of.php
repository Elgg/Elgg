<?php

// Given a user ID as a parameter, will display a list of communities

global $CFG;

if (isset($parameter[0])) {
    
    $user_id = (int) $parameter[0];
    
    $result = get_records_select('users',"owner = ? AND user_type = ?",array($user_id,'community'));
    
    $body = <<< END
    <div class="networktable">
    <table>
        <tr>
END;
    $i = 1;
    if (!empty($result)) {
        foreach($result as $key => $info) {
            $w = 100;
            //if (count($result) > 4) {
            //    $w = 100;
            //}
            // $friends_name = htmlspecialchars(stripslashes($info->name), ENT_COMPAT, 'utf-8');
            $friends_name = run("profile:display:name", $info->ident);
            $info->icon = run("icons:get",$info->ident);
            // $friends_menu = run("users:infobox:menu",array($info->ident));
            $body .= <<< END
        <td>
            <p>
            <a href="{$CFG->wwwroot}{$info->username}/">
            <img src="{$CFG->wwwroot}_icon/user/{$info->icon}/w/{$w}" alt="{$friends_name}" border="0" /></a><br />
            <span class="userdetails">
                <a href="{$CFG->wwwroot}{$info->username}/">{$friends_name}</a>
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
            $body .= "<td><p>". __gettext("You don't own any communities. Why not create one?") ."</p></td>";
        } else {
            $body .= "<td><p>". __gettext("This user is not currently moderating any communities.") ."</p></td>";
        }
    }
    $body .= <<< END
    </tr>
    </table>
    </div>
END;

    
    $run_result = $body;
   
}

?>
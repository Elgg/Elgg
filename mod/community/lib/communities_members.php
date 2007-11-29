<?php
global $CFG;
// Given a user ID as a parameter, will display a list of communities


if (isset($parameter[0])) {

    $community_id = (int) $parameter[0];
    $community_name = user_info('username',$community_id);
    $community_owner = user_info('owner',$community_id);

    if(COMMUNITY_ALLOW_COMMUNITY_TYPE_MEMBERS){
      $result = get_records_sql('SELECT u.*, f.ident AS friendident FROM '.$CFG->prefix.'friends f
                               JOIN '.$CFG->prefix.'users u ON u.ident = f.owner
                               WHERE f.friend = ?',array($community_id));
      
    }
    else{
      $result = get_records_sql('SELECT u.*, f.ident AS friendident FROM '.$CFG->prefix.'friends f
                               JOIN '.$CFG->prefix.'users u ON u.ident = f.owner
                               WHERE f.friend = ? AND u.user_type = ?',array($community_id,'person'));
    }

    $i = 1;
    if (!empty($result)) {
        foreach($result as $key => $info) {
            $link = $CFG->wwwroot.$info->username."/";
            $friends_name = user_name($info->ident);
            $info->icon = run("icons:get",$info->ident);
            $friends_icon = user_icon_html($info->ident,COMMUNITY_ICON_SIZE);
            // $friends_menu = run("users:infobox:menu",array($info->ident));
            $functions = array();
            if($community_owner != $info->ident && $community_owner == $_SESSION['userid']){
                $msg= "onclick=\"return confirm('". __gettext("Are you sure you want to separate this user from the community?") ."')\"";
                $functions[] = "<a href=\"".$CFG->wwwroot.$community_name."/community/separate/".$info->ident."\" $msg>".__gettext("Separate")."</a>";
            }
            else if($community_owner == $info->ident){
              $functions[] = "<b>(".__gettext("Owner").")</b>";
            }

            $functions = implode("\n",array_map(create_function('$entry',"return \"<li>\$entry</li>\";"),$functions));
            $members .= templates_draw(array(
                                        'context' => 'community_member',
                                        'name' => $friends_name,
                                        'icon' => $friends_icon,
                                        'link' => $link,
                                        'functions' => $functions
                                      )
                        );
            if ($i % COMMUNITY_MEMBERS_PER_ROW == 0) {
                $members .= "</tr><tr>";
            }
            $i++;
        }
    } else {
        $members .= "<td><p>". __gettext("This community doesn't currently have any members.") . "</p></td>";
    }

    $run_result = templates_draw(array(
                        'context' => 'community_members',
                        'members' => $members
                        )
                  );

}

?>
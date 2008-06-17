<?php

// List of users in the system
    
if (logged_on && user_flag_get("admin", $_SESSION['userid'])) {
    
    // We're only displaying 50 users a page, so if this variable exists it will serve as the offset
    $offset = optional_param('offset',0,PARAM_INT);
    
    $run_result .= "<p>" . __gettext("The following is a list of all the users in the system, 50 users at a time. You can click each one to edit their user details as if you were logged in as them, as well as set user flags (including 'ban user' and 'set user as administrator').") . "</p>";
    $run_result .= "<p>" . __gettext("If you know the username of the user you would like to edit, you can also enter it below.") . "</p>";

    $current_type = optional_param('user_type', 'person');
    $user_types = get_user_types();
    if (!empty($user_types)) {
        $run_result .= '<p>' . __gettext('Filter user type');
        foreach ($user_types as $user_type) {
            $run_result .= ' | ';
            if ($user_type == $current_type) {
                $run_result .= $user_type;
            } else {
                $run_result .= '<a href="'.get_url_query(1,'admin::users','user_type='.$user_type) . '">' . $user_type . '</a>';
            }
        }
    }
    
    $run_result .= "<form action=\"". url . "_userdetails/\" method=\"get\">";
    $run_result .= templates_draw(array(
                                        'context' => 'adminTable',
                                        'name' => "<h4>" . __gettext("Enter username") ."</h4>",
                                        'column1' => "<input type=\"text\" name=\"profile_name\" value=\"\" /><input type=\"hidden\" name=\"context\" value=\"admin\" />",
                                        'column2' => "<input type=\"submit\" value=\"".__gettext("Edit user") . "\" />"
                                        )
                                  );
    $run_result .= "</form>";
    
    $maxusers = count_records('users','user_type',$current_type);
    
    if ($users = get_records('users','user_type',$current_type,'name ASC','*',$offset,50)) {
        if ($maxusers > ($offset + 50)) {
            $next = "<a href=\"" . get_url_query(null, 'admin::users', "user_type=$current_type&offset=" . ($offset + 50)) . "\">" . __gettext("Next") . "</a>";
        } else {
            $next = "";
        }
        $prevoffset = $offset - 50;
        if ($prevoffset < 0) {
            $prevoffset = 0;
        }
        if ($prevoffset != $offset) {
            $prev = "<a href=\"" . get_url(null, 'admin::users', "user_type=$current_type&offset=" . ($prevoffset)) . "\">" . __gettext("Previous") . "</a>";
        } else {
            $prev = "";
        }
        
        $nav = templates_draw(array(
                                            'context' => 'adminTable',
                                            'name' => __gettext('Total users') . ":&nbsp;" . $maxusers,
                                            'column1' => $prev . "&nbsp;" . $next,
                                            'column2' => "&nbsp;"
                                            )
                                      );

        $run_result .= $nav;
        
        $run_result .= templates_draw(array(
                                            'context' => 'adminTable',
                                            'name' => "<h3>" . __gettext("Full name") . "</h3>",
                                            'column1' => "<h3>" . __gettext("Username") . "</h3>",
                                            'column2' => "<h3>" . __gettext("Extra info") . "</h3>"
                                            )
                                      );

        foreach($users as $user) {
            $run_result .= run("admin:users:panel",$user);
        }

        $run_result .= $nav;

    }
}

?>
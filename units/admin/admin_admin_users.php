<?php

// List of users in the system
    
global $CFG;

if (logged_on && user_flag_get("admin", $_SESSION['userid'])) {
    
    // We're only displaying 50 users a page, so if this variable exists it will serve as the offset
    $offset = optional_param('offset',0,PARAM_INT);
    
    $run_result .= "<p>" . __gettext("The following is a list of all the admin users in the system. You can click each one to edit their user details as if you were logged in as them, as well as set user flags (including 'ban user' and 'set user as administrator').") . "</p>";
    
    // if ($users = get_records('users','user_type','person','username ASC','*',$offset,50)) {
    if ($users = get_records_sql("select u.* from {$CFG->prefix}user_flags uf join {$CFG->prefix}users u on u.ident = uf.user_id where uf.flag = 'admin' and uf.value = '1'")) {
        $run_result .= templates_draw(array(
                                            'context' => 'adminTable',
                                            'name' => "<h3>" . __gettext("Username") . "</h3>",
                                            'column1' => "<h3>" . __gettext("Full name") . "</h3>",
                                            'column2' => "<h3>" . __gettext("Email address") . "</h3>"
                                            )
                                      );
        foreach($users as $user) {
            $run_result .= run("admin:users:panel",$user);
        }
        $prev = '';
        $next = '';
        $run_result .= templates_draw(array(
                                            'context' => 'adminTable',
                                            'name' => "&nbsp;",
                                            'column1' => $prev . "&nbsp;" . $next,
                                            'column2' => "&nbsp;"
                                            )
                                      );
        
    }
}

?>
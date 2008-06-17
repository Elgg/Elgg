<?php
global $CFG;
// Generate a new password

$sitename = sitename;

$code = trim(optional_param('passwordcode'));
if (!empty($code)) {
    if ($details = get_record_sql('SELECT pr.ident AS passcodeid,u.* FROM '.$CFG->prefix.'password_requests pr
                                   JOIN '.$CFG->prefix."users u ON u.ident = pr.owner
                                   WHERE pr.code = ? AND u.user_type = ?",array($code,'person'))) {
        
        $passwordDesc = sprintf(__gettext("A new password has been emailed to you at %s. You should be able to use it immediately; your old one has been deactivated."),$details->email);
        $run_result .= <<< END
    
    <p>
        $passwordDesc
    </p>
END;
        
        $validcharset = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz234567898765432";
        $newpassword = "";
        for ($i = 0; $i < 8; $i++) {
            $newpassword .= $validcharset[mt_rand(0, (strlen($validcharset) - 1))]; 
        }
        $newpassword = strtolower($newpassword);
        
        $sitename = sitename;
        
        email_to_user($details, null, sprintf(__gettext("Your %s password"), $sitename), sprintf(__gettext("Your %s password has been reset.\n\nFor your records, your new password is:\n\n\tPassword: %s\n\nPlease consider changing your password as soon as you have logged in for security reasons.\n\nWe hope you continue to enjoy using the system.\n\nRegards,\n\nThe %s Team"),$sitename, $newpassword, $sitename));
        $newpassword = md5($newpassword);
        set_field('users','password',$newpassword,'ident',$details->ident);
        delete_records('password_requests','owner',$details->ident);
        
    } else {
        
        $passwordDesc2 = __gettext("Your password request code appears to be invalid. Try generating a new one?");
        $run_result .= <<< END
    
    <p>
        $passwordDesc2
    </p>
    
END;
        
    }
    
} else {
    $passwordDesc3 = __gettext("Your password request code appears to be invalid. Try generating a new one?");
    $run_result .= <<< END
    
    <p>
        $passwordDesc3
    </p>
    
END;

}

?>
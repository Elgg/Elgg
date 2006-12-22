<?php

global $CFG;
// Join
        
$sitename = sitename;
$textlib = textlib_get_instance();

$code = optional_param('invitecode');
if (!empty($code)) {
    if ($details = get_record('invitations','code',$code)) {
        $name = optional_param('join_name',$details->name);

        $username = optional_param('join_username');
        if (empty($username)) {
            $username = "";
            $namebits = explode(" ", $name);
            foreach($namebits as $key => $bit) {
                if ($key == 0) {
                    $username .= $textlib->strtolower($bit);
                } else {
                    $username .= $textlib->strtolower($textlib->substr($bit,0,1));
                }
            }
            $username = preg_replace("/[^A-Za-z]/","",$username);
        }
        
        $invite_id = (int) $details->ident;
        $thankYou = sprintf(__gettext("Thank you for registering for an account with %s! Registration is completely free, but before you confirm your details, please take a moment to read the following documents:"), $sitename);
        $terms = __gettext("terms and conditions"); // gettext variable
        $privacy = __gettext("Privacy policy"); // gettext variable
        $age = __gettext("Submitting the form below indicates acceptance of these terms. Please note that currently you must be at least 13 years of age to join the site."); // gettext variable
        $run_result .= <<< END
            
    <p>
        $thankYou
    </p>
    <ul>
        <li><a href="{$CFG->wwwroot}content/terms.php" target="_blank">$sitename $terms</a></li>
        <li><a href="{$CFG->wwwroot}content/privacy.php" target="_blank">$privacy</a></li>
    </ul>
    <p>
        $age
    </p>

    <form action="" method="post">
                
END;
                
        $run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Your name"),
                                            'contents' => display_input_field(array("join_name",$name,"text"))
                                            )
                                      );
        $run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Your username - (Must be letters only)"),
                                            'contents' => display_input_field(array("join_username",$username,"text"))
                                            )
                                      );
        $run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Enter a password"),
                                            'contents' => display_input_field(array("join_password1","","password"))
                                            )
                                      );
        $run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Your password again for verification purposes"),
                                            'contents' => display_input_field(array("join_password2","","password"))
                                            )
                                      );
        $correctAge = __gettext("I am at least thirteen years of age."); // gettext variable
        $buttonValue = __gettext("Join"); // gettext variable
        $run_result .= <<< END
            <p align="center">
                <label for="over13checkbox"><input type="checkbox" id="over13checkbox" name="over13" value="yes" /> <strong>$correctAge</strong></label>
            </p>
            <p align="center">
                <input type="hidden" name="action" value="invite_join" />
                <input type="submit" value="$buttonValue" />
            </p>
        </form>
                
END;

    } else {
        
        $invalid = sprintf(__gettext("Your invitation code appears to be invalid. Codes only last for seven days; it's possible that yours is older. If you still want to join %s, it may be worth getting in touch with the person who invited you."),$sitename);
        $run_result .= <<< END
            
    <p>
        $invalid
    </p>
                
END;
                
    }
    
} else {
    $invite = sprintf(__gettext("For the moment, joining %s requires a specially tailored invite code. If you know someone who's a member, it may be worth asking them for one."),$sitename);
    $run_result .= <<< END
                
    <p>
        $invite
    </p>
                
END;
            
}

?>
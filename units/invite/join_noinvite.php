<?php

    global $CFG;
    // Join
        
        if ($CFG->publicreg == true) {
            
            $sitename = sitename;
            $partOne = sprintf(__gettext("Thank you for registering for an account with %s! Registration is completely free, but before you fill in your details, please take a moment to read the following documents:"),$sitename); // gettext variable
            $terms = __gettext("terms and conditions"); // gettext variable
            $privacy = __gettext("Privacy policy"); // gettext variable
            $partFour = __gettext("When you fill in the details below, we will send an \"invitation code\" to your email address in order to validate it. You must then click on this within seven days to create your account."); // gettext variable
                            
                $run_result .= <<< END
                
    <p>
        $partOne
    </p>
    <ul>
        <li><a href="{$CFG->wwwroot}content/terms.php" target="_blank">$sitename $terms</a></li>
        <li><a href="{$CFG->wwwroot}content/privacy.php" target="_blank">$privacy</a></li>
    </ul>
    <p>
        $partFour
    </p>
    <form action="" method="post">
                
END;
                
                $run_result .= templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("Your name"),
                                                'contents' => display_input_field(array("invite_name","","text"))
                    )
                    );
                $run_result .= templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("Your email address"),
                                                'contents' => display_input_field(array("invite_email","","text"))
                    )
                    );
            $buttonValue = __gettext("Register");
            $run_result .= <<< END
            <p align="center">
                <input type="hidden" name="action" value="invite_invite" />
                <input type="submit" value=$buttonValue />
            </p>
        </form>
                
END;
        } else {
            $nope = __gettext("Self-registration is currently disabled."); // gettext variable
            $run_result .= <<< END
    <p>
        $nope
    </p>
END;
        }

?>
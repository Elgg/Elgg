<?php

global $CFG;

if (!empty($CFG->disable_passwordchanging)) {

    $nope = __gettext('The site administrator has disabled password changing.');
    $run_result .= '<p>' . $nope . '</p>';

} else {

    // Join
    $sitename = sitename;
    $desc = sprintf(__gettext("To generate a new password at %s!, enter your username or email below. We will send the address of a unique verification page to you via email click on the link in the body of the message and a new password will be sent to you."), $sitename); // gettext variable
    $thismethod = __gettext("This method reduces the chance of a mistakenly reset password.");

    $run_result .= <<< END

    <p>
        $desc
    </p>
    <p>
        $thismethod
    </p>
    <form action="" method="post">

END;

    $run_result .= templates_draw(array(
                                    'context' => 'databoxvertical',
                                    'name' => __gettext("Your username"),
                                    'contents' => display_input_field(array("password_request_name","","text"))
        )
        );
    $request = __gettext("Request new password"); // gettext variable
    $run_result .= <<< END
            <p align="center">
                <input type="hidden" name="action" value="invite_password_request" />
                <input type="submit" value=$request />
            </p>
        </form>

END;

}

?>
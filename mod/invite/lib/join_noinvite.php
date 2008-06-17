<?php

global $CFG,$function,$messages;
// Join

if ($CFG->publicreg == true) {

    if(array_key_exists("invite:register:welcome",$function)){
      $run_result.=run("invite:register:welcome");
    }
    else{
      $run_result.=run("invite:register:default:welcome");
    }
$run_result .= <<< END

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
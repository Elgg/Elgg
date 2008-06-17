<?php

global $CFG, $metatags;
// Join

$metatags .= <<< END
\n<script type="text/javascript">
         function keypressed() {
             var username = document.getElementById("id_join_username").value;
             document.getElementById("username_ok").src = '{$CFG->wwwroot}mod/invite/lib/check_username.php?username=' + username;
         }

         function setup() {
            document.getElementById("id_join_username").onkeyup = keypressed;
         }

         window.onload = setup; 
</script>
END;

$sitename = sitename;
$textlib = textlib_get_instance();

$code = optional_param('invitecode');
$registrationpage = "<a href=\"".$CFG->wwwroot."register\">".__gettext("register page")."</a>";
if (!empty($code)) {
    if ($details = get_record('invitations','code',$code)) {
        $name = optional_param('join_name',$details->name);
        $username = optional_param('join_username');
        if (empty($username) && INVITE_USERNAME_SUGGEST) {
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

    if(array_key_exists("invite:join:welcome",$function)){
      $run_result.=run("invite:join:welcome");
    }
    else{
      $run_result.=run("invite:join:default:welcome");
    }

$run_result .= <<< END
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
                                            'contents' => "\n<b>$CFG->wwwroot</b>" . "<input type=\"text\" name=\"join_username\" value=\"".htmlspecialchars(stripslashes($username), ENT_COMPAT, 'utf-8')."\" style=\"width: 60%\" id=\"id_join_username\" /><img src=\"{$CFG->wwwroot}mod/invite/lib/check_username.php?username=$username\" id=\"username_ok\" />",
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

        if(array_key_exists("invite:join:footer",$function)){
          $run_result.=run("invite:join:footer");
        }
        else{
          $run_result.=run("invite:join:default:footer");
        }

  $buttonValue = __gettext("Join"); // gettext variable

  $run_result .= <<< END
      <p align="center">
          <input type="hidden" name="action" value="invite_join" />
          <input type="submit" value="$buttonValue" />
      </p>
  </form>
END;

    } else {

        $invalid = __gettext("Your invitation code appears to be invalid. Codes only last for seven days; it's possible that yours is older.");

        if ($CFG->publicreg) {
            $invalid .= sprintf(__gettext("If you still want to join %s, go to the %s."),$sitename,$registrationpage);
        } else {
            $invalid .= sprintf(__gettext("If you still want to join %s, it may be worth getting in touch with the person who invited you."),$sitename);
        }


        $run_result .= <<< END

    <p>
        $invalid
    </p>

END;

    }

} else {
    if ($CFG->publicreg) {
        $invite = sprintf(__gettext("To join %s, go to the %s."),$sitename,$registrationpage);
    } else {
        $invite = sprintf(__gettext("For the moment, joining %s requires a specially tailored invite code. If you know someone who's a member, it may be worth asking them for one."),$sitename);
    }
    $run_result .= <<< END

    <p>
        $invite
    </p>

END;

}

?>
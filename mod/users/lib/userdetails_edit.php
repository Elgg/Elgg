<?php

global $page_owner, $CFG;

if (user_type($page_owner) == 'person' && run("permissions:check",array("userdetails:change", $page_owner))) {
    
    $info = get_record('users','ident',$page_owner);
    $name = htmlspecialchars(stripslashes(user_name($info->ident)), ENT_COMPAT, 'utf-8');
    $email = htmlspecialchars(stripslashes($info->email), ENT_COMPAT, 'utf-8');
    
    $changeName = __gettext("Change your full name:"); // gettext variable
    $displayed = __gettext("This name will be displayed throughout the system."); // gettext variable
    $body = <<< END

<form action="index.php" method="post">

    <h2>
        $changeName
    </h2>
    <p>
        $displayed
    </p>

END;

    $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Your full name "),
            'column1' => "<input type=\"text\" name=\"name\" value=\"$name\" />"
        )
        );
    
    $emailAddress = __gettext("Your email address:"); // gettext variable
    $emailRules = __gettext("This will not be displayed to other users; you can choose to make an email address available via the profile screen."); // gettext variable
    $body .= <<< END
    
    <h2>
        $emailAddress
    </h2>
    <p>
        $emailRules
    </p>
    
END;

    $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Your email address "),
            'column1' => "<input type=\"text\" name=\"email\" value=\"$email\" />"
        )
        );

    $friendAddress = __gettext("Friendship moderation:"); // gettext variable
    $friendRules = __gettext("This allows you to choose who can list you as a friend."); // gettext variable
    $body .= <<< END
        
        <h2>
            $friendAddress
        </h2>
        <p>
            $friendRules
        </p>
        
END;

    $friendlevel = "<select name=\"moderation\">";
    $friendlevel .= "<option value=\"no\" ";
    if ($info->moderation == "no") {
        $friendlevel .= "selected=\"selected\"";
    }
    $friendlevel .= ">" . __gettext("No moderation: anyone can list you as a friend. (Recommended)") . "</option>";
    $friendlevel .= "<option value=\"yes\" ";
    if ($info->moderation == "yes") {
        $friendlevel .= "selected=\"selected\"";
    }
    $friendlevel .= ">" . __gettext("Moderation: friendships must be approved by you.") . "</option>";
    $friendlevel .= "<option value=\"priv\" ";
    if ($info->moderation == "priv") {
        $friendlevel .= "selected=\"selected\"";
    }
    $friendlevel .= ">" . __gettext("Private: nobody can list you as a friend.") . "</option>";
    $friendlevel .= "</select>";
    
    $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Friendship moderation"),
            'column1' => $friendlevel
            )
            );
    
    if (!$CFG->disable_publiccomments) {
        $emailReplies = __gettext("Make comments public");
        $emailRules = __gettext("Set this to 'yes' if you would like anyone to be able to comment on your resources (by default only logged-in users can). Note that this may make you vulnerable to spam.");
        
        $body .= <<< END
        
        <h2>$emailReplies</h2>
        <p>
            $emailRules
        </p>
        
END;

        $publiccomments = user_flag_get("publiccomments",$page_owner);
        if ($publiccomments) {
            $body .= templates_draw(array(
                'context' => 'databox',
                'name' => __gettext("Public comments: "),
                'column1' => "<label><input type=\"radio\" name=\"publiccomments\" value=\"yes\" checked=\"checked\" /> " . __gettext("Yes") . "</label> <label><input type=\"radio\" name=\"publiccomments\" value=\"no\" /> " . __gettext("No") . "</label>"
            )
            );
        } else {
            $body .= templates_draw(array(
                'context' => 'databox',
                'name' => __gettext("Public comments: "),
                'column1' => "<label><input type=\"radio\" name=\"publiccomments\" value=\"yes\" /> " . __gettext("Yes") . "</label> <label><input type=\"radio\" name=\"publiccomments\" value=\"no\" checked=\"checked\" /> " . __gettext("No") . "</label>"
            )
            );
        }
    }
    
    $emailReplies = __gettext("Receive email notifications");
    $emailRules = __gettext("Set this to 'yes' if you would like to receive email copies of any messages you receive. This includes blog comments, notifications when people add you as a friend and more. You can always view these online as part of your recent activity page.");
    
    $body .= <<< END
        
        <h2>$emailReplies</h2>
        <p>
            $emailRules
        </p>
        
END;
    
    $emailreplies = user_flag_get("emailnotifications",$page_owner);
    if ($emailreplies) {
        $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Receive notifications: "),
            'column1' => "<label><input type=\"radio\" name=\"receivenotifications\" value=\"yes\" checked=\"checked\" /> " . __gettext("Yes") . "</label> <label><input type=\"radio\" name=\"receivenotifications\" value=\"no\" /> " . __gettext("No") . "</label>"
        )
        );
    } else {
        $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Receive notifications: "),
            'column1' => "<label><input type=\"radio\" name=\"receivenotifications\" value=\"yes\" /> " . __gettext("Yes") . "</label> <label><input type=\"radio\" name=\"receivenotifications\" value=\"no\" checked=\"checked\" /> " . __gettext("No") . "</label>"
        )
        );
    }
    
    if (empty($CFG->disable_passwordchanging)) {
        $password = __gettext("Change your password:"); // gettext variable
        $passwordRules = __gettext("Leave this blank if you're happy to leave your password as it is."); // gettext variable
        $body .= <<< END
    
    <h2>
        $password
    </h2>
    <p>
        $passwordRules
    </p>
    
END;
    
        $body .= templates_draw(array(
                'context' => 'databox',
                'name' => __gettext("Your password: "),
                'column1' => "<input type=\"password\" name=\"password1\" value=\"\" />"
            )
            );
            
        $body .= templates_draw(array(
                'context' => 'databox',
                'name' => __gettext("Again for verification purposes: "),
                'column1' => "<input type=\"password\" name=\"password2\" value=\"\" />"
            )
            );
    }
    
    // Allow plug-ins to add stuff ...
    $body .= run("userdetails:edit:details");

    $id = $page_owner;

    $save = __gettext("Save");
    
    $body .= <<< END
    
    <p align="center">
        <input type="hidden" name="action" value="userdetails:update" />
        <input type="hidden" name="id" value="$page_owner" />
        <input type="hidden" name="profile_id" value="$page_owner" />
END;
    if (context == "admin") {
        $body .= '<input type="hidden" name="context" value="admin" />';
    }
    $body .= <<< END
        <input type="submit" value="$save" />
    </p>
    
</form>

END;
    if (context == "admin") {
        
        $blurb = __gettext("Deleting this account is permanent and absolutely cannot be undone. Only click this button if you're really sure!");
        $deleteaccount = __gettext("Delete account");
        $body .= templates_draw(array(
            'context' => 'databox',
            'name' => $blurb,
            'column1' => "<a href=\"index.php?action=user:delete&profile_id=$page_owner\">{$deleteaccount}</a>",
            ));

    }

    $run_result .= $body;
}

?>
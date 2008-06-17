<?php

    global $page_owner;
    
    if (user_type($page_owner) == 'community' && run("permissions:check", "userdetails:change")) {
        
        $info = get_record('users','ident',$page_owner);
        $name = htmlspecialchars(stripslashes(user_name($info->ident)), ENT_COMPAT, 'utf-8');
        $email = htmlspecialchars(stripslashes($info->email), ENT_COMPAT, 'utf-8');
    
    $header = __gettext("Change your community name"); // gettext variable
    $desc = __gettext("This name will be displayed throughout the system."); // gettext variable
    $body = <<< END
<form action="" method="post">

    <h3>
        $header
    </h3>
    <p>
        $desc
    </p>

END;

    $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Community name"),
            'column1' => "<input type=\"text\" name=\"name\" value=\"$name\" />"
        )
        );
        
    $friendAddress = __gettext("Membership restriction:"); // gettext variable
        $friendRules = __gettext("This allows you to choose who can join this community."); // gettext variable
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
        $friendlevel .= ">" . __gettext("No moderation: anyone can join this community.") . "</option>";
        $friendlevel .= "<option value=\"yes\" ";
        if ($info->moderation == "yes") {
            $friendlevel .= "selected=\"selected\"";
        }
        $friendlevel .= ">" . __gettext("Moderation: memberships must be approved by you.") . "</option>";
        $friendlevel .= "<option value=\"priv\" ";
        if ($info->moderation == "priv") {
            $friendlevel .= "selected=\"selected\"";
        }
        $friendlevel .= ">" . __gettext("Private: nobody can join this community.") . "</option>";
        $friendlevel .= "</select>";
    
        $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Membership restriction"),
            'column1' => $friendlevel
            )
            );
            
        $body .= "<h2>" . __gettext("Community ownership") . "</h2>\n";
        $body .= "<p>" . __gettext("Type the username of the user who you want to own this community below. You cannot leave this blank, or specify a community or a user that doesn't exist.") . "</p>";
        
        if ($info->owner < 1) {
            $owner_username = "";
        } else {
            if (!($owner_username = user_info("username",$info->owner))) {
                $owner_username = "";
            }
        }
        
        $body .= templates_draw(array(
            'context' => 'databox',
            'name' => __gettext("Community owner"),
            'column1' => "<input type=\"text\" name=\"community_owner\" value=\"" . htmlspecialchars($owner_username) . "\" />"
            )
            );
        
    // Allow plug-ins to add stuff ...
        $body .= run("userdetails:edit:details");
        
        $save = __gettext("Save"); // gettext variable
        $body .= <<< END
        
    <p align="center">
        <input type="hidden" name="action" value="userdetails:update" />
        <input type="hidden" name="id" value="$page_owner" />
        <input type="hidden" name="profile_id" value="$page_owner" />
        <input type="submit" value="$save" />
    </p>
    
</form>

END;

    if (context == "admin") {
        
        $blurb = __gettext("Deleting this account is permanent and absolutely cannot be undone. Only click this button if you're really sure!");
        $deleteaccount = __gettext("Delete community account");
        $body .= templates_draw(array(
            'context' => 'databox',
            'name' => $blurb,
            'column1' => "<a href=\"index.php?action=user:delete&profile_id=$page_owner\">{$deleteaccount}</a>",
            ));

    }



    $run_result .= $body;
    }

?>
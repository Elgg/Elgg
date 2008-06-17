<?php
    // Community invite users page

    // Run includes
    require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
    run("userdetails:init");
    run("profile:init");
    run("friends:init");
    run("communities:init");

    $context = (defined('COMMUNITY_CONTEXT'))?COMMUNITY_CONTEXT:"network";

    define("context", $context);
    templates_page_setup();

    // Whose friends are we looking at?
    global $page_owner,$profile_id;
    
    // You must be logged on to view this!
    //    protect(1);

    $title = run("profile:display:name") . " :: " . __gettext("Invite people");
    
    $body  = '<h3>'.__gettext("Invite people").'</h3>';
	$body .= '<p>'.__gettext("Invite people to this community.").'</p>';
    $body .= '<form action="" method="post">';
    
    $submit_search_label = __gettext("Search");

    $name_or_username = __gettext("Name or username:");
    $invite_search = <<< END
	<p>
        {$name_or_username} <input type="text" name="invite_name"/><br/>
		<input type="submit" name="submit" value="{$submit_search_label}"/>
	</p>
END;

    $invite_name    = optional_param('invite_name');
    $profile_name   = optional_param('profile_name');
    $invite_ident   = optional_param('invite_ident');
    
    if (empty($invite_name)) {
        $body .= $invite_search;        
    } else {
        $search_sql = "SELECT ident, name, username FROM {$CFG->prefix}users WHERE username LIKE '%{$invite_name}%' OR name LIKE '%{$invite_name}%'";
        $users = get_records_sql($search_sql);

        if (empty($users)) {
            global $messages;
            
            $messages[] = __gettext('No results found matching your search criteria.');
            $body .= $invite_search;
        } else {
            // Reuse adminTable layout
            $body .= templates_draw(array(
                                        'context' => 'adminTable',
                                        'name' => "<h3>" . __gettext("Invite") . "</h3>",
                                        'column1' => "<h3>" . __gettext("Name") . "</h3>",
                                        'column2' => "<h3>" . __gettext("Full name") . "</h3>"
                                        )
                                  );


            foreach($users as $user) {
$block = <<< END
<div>
    <table width="100%">
	<tr>
		<td width="25%" valign="top"><input type="checkbox" name="invite_ident" value="$user->ident"/></td>
		<td width="45%" valign="top">$user->username</td>
		<td width="30%" valign="top">$user->name</td>
	</tr>
    </table>
</div>
END;

                $body .= $block;

            }

            $body .= '<input type="submit" name="Invite" value="'.__gettext("Invite").'"/>';
        }
    }

    if (!empty($invite_ident)) {
        global $messages;
        // We have an ident, process
	    if ($result = get_record('friends','owner',$invite_ident,'friend',$profile_id)) {
	        $messages[] = __gettext("The user already is a member of this community.");
	    } else if ($result = get_record('friends_requests', 'owner', $profile_id, 'friend', $invite_ident)){
	        $messages[] = __gettext("The user already has been invited.");
	    } else {
	        $x = new stdClass();
	        $x->owner  = $profile_id;
	        $x->friend = $invite_ident;

	        // Add a callback
	        $x = plugin_hook('community:invite', 'publish', $x);
	        
	        if (empty($x)) {
	            trigger_error("The community:invite callback didn't receive a return value.", E_USER_ERROR);
	        }
	        
	        insert_record('friends_requests', $x);
	        
	        $community = get_record('users', 'ident', $profile_id );

            $message_body = sprintf(__gettext("You have been invited to join %s!\n\nTo visit this community's profile, click on the following link:\n\n\t%s\n\nTo view all your community invitation requests and approve or deny this user, click here:\n\n\t%s\n\nRegards,\n\nThe %s team."),$community->name, $CFG->wwwroot . $community->username . "/", $CFG->wwwroot . user_info("username",$invite_ident) . "/communities/invitations",$CFG->sitename);
            $title = sprintf(__gettext("New %s community invitation"), $CFG->sitename);
            notify_user($x->friend,$title,$message_body);
	        
	        $messages[] = __gettext("The user has been invited.");
	    }
    }
    
    $body .= '</form>';
    
    echo templates_page_draw( array(
            $title, templates_draw(array(
                'context' => 'contentholder',
                'title' => $title,
                'body' => $body
            )
            )
        )
        );
?>
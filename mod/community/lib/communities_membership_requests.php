<?php
global $CFG;

$body = '';
    // Lists membership requests for a community

    if (logged_on) {

        global $page_owner;
        $owner_name = user_info('username',$page_owner);
        if (user_type($page_owner) == "community" && run("permissions:check",array("userdetails:change", $page_owner))) {

            if ($pending_requests = get_records_sql('SELECT fr.ident AS request_id, u.*  FROM '.$CFG->prefix.'friends_requests fr
                                                     LEFT JOIN '.$CFG->prefix.'users u ON u.ident = fr.owner
                                                     WHERE fr.friend = ? ORDER BY u.name ASC',array($page_owner))) {
                $body .= "<p>" . __gettext("The following users would like to join this community. They need your approval to do this (to change this setting, visit the 'community settings' page).") . "</p>";

                foreach($pending_requests as $pending_user) {

                    $where = run("users:access_level_sql_where",$_SESSION['userid']);
                    if ($description = get_record_select('profile_data',"($where) AND name = 'minibio' and owner = " . $pending_user->ident)) {
                        $description = "<p>" . stripslashes($description->value) . "</p>";
                    } else {
                        $description = "<p>&nbsp;</p>";
                    }

                    $request_id = $pending_user->request_id;

                    $pending_user->name = run("profile:display:name",$pending_user->ident);

                    $col1 = "<p><b>" . $pending_user->name . "</b></p>" . $description;
                    $col1 .= "<p>";
                    $col1 .= '<a href="' . url . $pending_user->username . '/">' . __gettext("Profile") . "</a> | ";
                    $col1 .= '<a href="' . url . $pending_user->username . '/weblog/">' . __gettext("Blog") . "</a></p>";
                    $col2 = '<p><a href="' .url.$owner_name. "/community/requests/aprove/$request_id\">Approve</a> | <a href=\"" .url.$owner_name. "/community/requests/decline/$request_id\">Decline</a></p>";
                    $ident = $pending_user->ident;

                    $body .= templates_draw(array(
                                                    'context' => 'adminTable',
                                                    'name' => user_icon_html($pending_user->ident),
                                                    'column1' => $col1,
                                                    'column2' => $col2
                                                 )
                                                 );

                }

            } else {
                $body .= "<p>" . __gettext("You have no pending membership requests.") . "</p>";
            }

            $run_result = $body;
        }

    }

?>
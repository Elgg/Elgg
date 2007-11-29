<?php

    $comm_name = '';
    if (isset($_SESSION['comm_name'])) {
        $comm_name = $_SESSION['comm_name'];
    }
    $comm_username = '';
    if (isset($_SESSION['comm_username'])) {
        $comm_username = $_SESSION['comm_username'];
    }

    global $page_owner, $CFG, $USER;

    if (logged_on && $page_owner == $_SESSION['userid'] &&
        ($CFG->community_create_flag == "" || user_flag_get($CFG->community_create_flag, $USER->ident))) {

    $title = __gettext("Create a new community"); // gettext variable
    $communityName = __gettext("Community name:"); // gettext variable
    $communityUsername = __gettext("Username for community (forms part of the community website address):"); // gettext variable
    $buttonValue = __gettext("Create"); // gettext variable

    $fields = templates_draw(array('context' => 'databox1',
                                   'name' => $communityName,
                                   'column1' => "<input type=\"text\" name=\"comm_name\" value=\"$comm_name\" size=\"50\"/>"
                                   )
                            );
    $fields.= templates_draw(array('context' => 'databox1',
                                   'name' => $communityUsername,
                                   'column1' => "<input type=\"text\" name=\"comm_username\" value=\"$comm_username\" maxlength=\"12\"/>"
                                   )
                            );
    $run_result .= templates_draw(array('context'=>"community_create",
                                        'title'=> $title,
                                        'form_fields'=> $fields,
                                        'button' => $buttonValue
                                        )
                                );
    }

?>
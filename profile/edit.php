<?php

//    ELGG profile edit page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");
require_once($CFG->dirroot . "profile/profile.class.php");

// define what profile to show
$profile_name = optional_param('profile_name', '', PARAM_ALPHANUM);
if (!empty($profile_name)) {
    $profile_id = user_info_username('ident', $profile_name);
}
if (empty($profile_id)) {
    // fetch from GET/POST param
    $profile_id = optional_param('profile_id', -1, PARAM_INT);

    // if it wasn't in GET/POST but we have a valid session, use it
    if ($profile_id === -1 && isset($_SESSION['userid'])) {
        $profile_id = $_SESSION['userid'];
    }

    $profile_name = user_info('username', $profile_id);
}

// init library
$profile = new ElggProfile($profile_id);  

define("context", "profile");
        
protect(1);

global $page_owner;
        
$title = run("profile:display:name", $page_owner) . " :: ". __gettext("Edit profile") ."";
templates_page_setup();


if ($profile_new = data_submitted()) {
    $body = profile_update($profile_new);
} else {
    $body = $profile->display_form();
}   
$body = templates_draw(array( 'context' => 'contentholder',
                              'title' => $title,
                              'body' => $body   ));

print templates_page_draw(array($title, $body));



function profile_update($profile_new) {

    global $CFG;
    global $data;
    global $messages;
    global $page_owner;
    global $profile_name;
        
    $profiledetails = optional_param('profiledetails',array());
    if (count($profiledetails) > 0) {
        delete_records('profile_data','owner',$page_owner);
        foreach($profiledetails as $field => $value) {
            $field = trim($field);
            $value = trim($value);

            if ($value != "") {
                //TODO get rid of variable duplication here. (Penny)
                $access = $_POST['profileaccess'][$field];

                $pd = new StdClass;
                $pd->name   = $field;
                $pd->value  = $value;
                $pd->access = $access;
                $pd->owner  = $page_owner;

                $insert_id  = insert_record('profile_data',$pd);
            }


            foreach($data['profile:details'] as $datatype) {
                if ($datatype[1] == $field && $datatype[2] == "keywords") {
                    delete_records('tags', 'tagtype', $field, 'owner', $page_owner);
                    $value = insert_tags_from_string ($value, $field, $insert_id, $access, $page_owner);
                }
            }
        }
        $messages[] = __gettext("Profile updated.");
    }

    // Changes saved successfully, update RSS feeds
    $rssresult = run("weblogs:rss:publish", array(1, false)); 
    $rssresult = run("profile:rss:publish", array(1, false));

    // redirect("{$CFG->wwwroot}{$profile_name}", get_string("changessaved"));
    redirect("{$CFG->wwwroot}{$profile_name}/profile/", "");
}

?>
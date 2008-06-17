<?php

//    ELGG profile edit page

global $profile_name, $profile_id, $messages;

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
    $profile_id = page_owner();

    // if it wasn't in GET/POST but we have a valid session, use it
    if ($profile_id === -1 && isset($_SESSION['userid'])) {
        $profile_id = $_SESSION['userid'];
    }
}
$profile_name = user_info('username', $profile_id);

// init library
$profile = new ElggProfile($profile_id);

define("context", "profile");

protect(1);

global $page_owner, $metatags, $CFG, $data;

if (isset($_SESSION['profile:preload'])) {
    $data['profile:preload'] = $_SESSION['profile:preload'];
    unset($_SESSION['profile:preload']);
}
if (isset($_SESSION['profile:preload:access'])) {
    $data['profile:preload:access'] = $_SESSION['profile:preload:access'];
    unset($_SESSION['profile:preload:access']);
}

$title = user_name($page_owner) . " :: ". __gettext("Edit profile") ."";
templates_page_setup();

$metatags .= "<script type=\"text/javascript\" src=\"" . $CFG->wwwroot . "mod/profile/tabber/tabber.js\"></script>";
$metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/profile/tabber/example.css\" type=\"text/css\" media=\"screen\" />";

if ($profile_new = data_submitted()) {
    $body = profile_update($profile_new);
} else {
    $body = $profile->display_form();
}   

templates_page_output($title, $body);

function profile_update($profile_new) {

    global $CFG;
    global $data;
    global $messages;
    global $page_owner;
    global $profile_name;
        
    $profiledetails = optional_param('profiledetails',array());
    if (count($profiledetails) > 0) {
        // delete_records('profile_data','owner',$page_owner);
        
        $insertvalues = array();
        $requiredmissing = array();
        
        foreach($profiledetails as $field => $value) {
            $field = trim($field);
            $value = trim($value);
          
            if (!empty($value)) {
                //TODO get rid of variable duplication here. (Penny)
                if (!empty($data['profile:details'][$field]->invisible)) {
                    $access = 'user' . $page_owner;
                } else {
                    $access = $_POST['profileaccess'][$field];
                }

                $pd = new StdClass;
                $pd->name   = $field;
                $pd->value  = $value;
                $pd->access = $access;
                $pd->owner  = $page_owner;

                // $insert_id  = insert_record('profile_data',$pd);
                $insertvalues[] = $pd;
                
            } else {
                foreach($data['profile:details'] as $datatype) {
                    if (is_array($datatype)) {
                        $fname  = !empty($datatype[1]) ? $datatype[1] : '';
                        $flabel = !empty($field[0]) ? $field[0] : '';
                        $frequired = false;
                        $fcat = __gettext("Main");
                    // Otherwise map things the new way!
                    } else {
                        $fname = $datatype->internal_name;
                        $flabel = $datatype->name;
                        $frequired = $datatype->required;
                        if (empty($datatype->category)) {
                            $fcat = __gettext("Main");
                        } else {
                            $fcat = $datatype->category;
                        }
                    }
                    if ($fname == $field) {
                        if ($frequired == true) {
                            $requiredmissing[] = sprintf(__gettext("%s (in category %s)"),$flabel,$fcat);
                        } else {
                            delete_records('profile_data','owner',$page_owner,'name',$fname);
                        }
                    }
                }
            }
        }
        if (sizeof($requiredmissing) == 0) {
            
            $updatedok = true;
            
            foreach($insertvalues as $insertvalue) {
                delete_records('profile_data','owner',$page_owner,'name',$insertvalue->name);
                $insertvalue = plugin_hook("profile_data","create",$insertvalue);
                if (!empty($insertvalue)) {
                    $insert_id  = insert_record('profile_data',$insertvalue);
                    $insertvalue->ident = $insert_id;
                    plugin_hook("profile_data","publish",$insertvalue);
                    foreach($data['profile:details'] as $datatype) {
                        if (is_array($datatype)) {
                            $fname = !empty($datatype[1]) ? $datatype[1] : '';
                            $ftype = !empty($datatype[2]) ? $datatype[2] : '';
                        // Otherwise map things the new way!
                        } else {
                            $fname = $datatype->internal_name;
                            $ftype = $datatype->field_type;
                        }
                        if ($fname == $insertvalue->name && $ftype == "keywords") {
                            delete_records('tags', 'tagtype', $insertvalue->name, 'owner', $page_owner);
                            $value = insert_tags_from_string ($insertvalue->value, $insertvalue->name, $insert_id, $insertvalue->access, $page_owner);
                        }
                        if (isset($CFG->display_field_module[$ftype])) {
                            $callback = $CFG->display_field_module[$ftype] . "_validate_input_field";
                            $updatedok = $callback($insertvalue);
                        }
                    }
                }
            }
            $messages[] = __gettext("Profile updated.");
        } else {
            
            $savedata = array();
            
            foreach($insertvalues as $insertvalue) {
                $savedata['profile:preload'][$insertvalue->name] = $insertvalue->value;
                $savedata['profile:preload:access'][$insertvalue->name] = $insertvalue->access;
            }
            foreach($requiredmissing as $key=> $missinglabel) {
                $message = "";
                if ($key > 0) {
                    $message .= ", ";
                }
                $message .= $missinglabel;
            }
            
            $messages[] = sprintf(__gettext("You need to fill in the following required fields: %s"),$message);
            
            $updatedok = false;
            $_SESSION['profile:preload'] = $savedata['profile:preload'];
            $_SESSION['profile:preload:access'] = $savedata['profile:preload:access'];
        }
    }

    // Changes saved successfully, update RSS feeds
    $rssresult = run("weblogs:rss:publish", array(1, false)); 
    $rssresult = run("profile:rss:publish", array(1, false));

    $_SESSION['messages'] = $messages;
    
    // redirect("{$CFG->wwwroot}{$profile_name}", get_string("changessaved"));
    if ($updatedok) {
        redirect("{$CFG->wwwroot}{$profile_name}/profile/", "");
    } else {
        redirect("{$CFG->wwwroot}profile/edit.php?profile_id=".$page_owner, "");
    }
}

?>
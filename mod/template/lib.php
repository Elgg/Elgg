<?php

function template_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;

    $page_owner = $profile_id;

    if (defined("context") && context == "account" && !$CFG->disable_templatechanging  && user_info("user_type",$_SESSION['userid']) != "external") {
        if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
            $PAGE->menu_sub[] = array( 'name' => 'template:change',
                                       'html' => a_href( "{$CFG->wwwroot}_templates/",
                                                          __gettext("Change theme")));  
        }
    }
    
    $CFG->templates->variables_substitute['templatesroot'][] = "templates_root";
}

function template_init() {
    // Delete users
    listen_for_event("user","delete","template_user_delete");
}

function templates_root($vars) {
    global $CFG;
    return $CFG->templatesroot;
}

function template_user_delete($object_type, $event, $object) {
    global $CFG, $data;
    if (!empty($object->ident) && $object_type == "user" && $event == "delete") {
        if ($templates = get_records_sql("select * from {$CFG->prefix}templates where owner = {$object->ident}")) {
            foreach($templates as $template) {
                delete_records('template_elements','template_id',$template->ident);
            }
        }
        delete_records('templates','owner',$object->ident);
    }
    return $object;
}

?>

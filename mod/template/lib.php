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
                                       'html' => a_href( "{$CFG->wwwroot}mod/template/",
                                                          __gettext("Change theme")));  
        }
    }
    
    $CFG->templates->variables_substitute['templatesroot'][] = "templates_root";
}

function template_init() {
	global $CFG,$function;

    // Load default values
    $function['init'][] = dirname(__FILE__) . "/lib/default_template.php";
        
    // Actions
    $function['templates:init'][] = dirname(__FILE__) . "/lib/template_actions.php";

    // Draw template (returns HTML as opposed to echoing it straight to the screen)
    $function['templates:draw'][] = dirname(__FILE__) . "/lib/template_draw.php";
        
    // Function to substitute variables within a template, used in templates:draw
    $function['templates:variables:substitute'][] = dirname(__FILE__) . "/lib/variables_substitute.php";

    // Function to draw the page, once supplied with a main body and title
    $function['templates:draw:page'][] = dirname(__FILE__) . "/lib/page_draw.php";
        
    // Function to display a list of templates
    $function['templates:view'][] = dirname(__FILE__) . "/lib/templates_view.php";
    $function['templates:preview'][] = dirname(__FILE__) . "/lib/templates_preview.php";
                
    // Function to display input fields for template editing
    $function['templates:edit'][] = dirname(__FILE__) . "/lib/templates_edit.php";
	        
    // Function to allow the user to create a new template
    $function['templates:add'][] = dirname(__FILE__) . "/lib/templates_add.php";
	
    // Adds default template
    listen_for_event("user","create","template_user_create");
    // Delete users
    listen_for_event("user","delete","template_user_delete");
}

function templates_root($vars) {
    global $CFG;
    return $CFG->templatesroot;
}

function template_user_create($object_type, $event, $object) {
    global $CFG;
    // add current default template
    $object->template_name = $CFG->default_template;
    return $object;
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

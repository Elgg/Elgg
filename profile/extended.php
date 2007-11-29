<?php

//    ELGG profile view page

// Run includes
require_once(dirname(dirname(__FILE__)) . '/includes.php');
require_once($CFG->dirroot . 'profile/profile.class.php');

// define what profile to show
$profile_name = optional_param('profile_name', '', PARAM_ALPHANUM);
if (!empty($profile_name)) {
    $profile_id = user_info_username('ident', $profile_name);
}
if (empty($profile_id)) {
    $profile_id = optional_param('profile_id', -1, PARAM_INT);
}
// and the page_owner naturally
$page_owner = $profile_id;

define("context", "profile");
templates_page_setup();

// init library
$profile = new ElggProfile($profile_id); 
        
$title = user_name($profile_id); //$profile->display_name();
$body  = $profile->view();

$body  = templates_draw( array(
                               'context' => 'contentholder',
                               'title' => $title,
                               'body' => $body
                               ));

echo templates_page_draw(array($title, $body));

?>
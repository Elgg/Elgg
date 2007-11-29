<?php
global $USER;
// ELGG weblog system initialisation

// ID of profile to view / edit

global $profile_id;

$weblog_name = optional_param('weblog_name');
if (!empty($weblog_name)) {
    $profile_id = (int) user_info_username('ident', $weblog_name);
} else {
    if (isloggedin()) {
        $profile_id = optional_param('profile_id',optional_param('profileid',$USER->ident,PARAM_INT),PARAM_INT);
    } else {
        $profile_id = optional_param('profile_id',optional_param('profileid',-1,PARAM_INT),PARAM_INT);
    }
}

global $page_owner;

$page_owner = $profile_id;

global $page_userid;

$page_userid = user_info('username', $profile_id);

// Add RSS to metatags, only if we are on a user page

if (!empty($page_userid)) {
    global $metatags;
    $metatags .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".url."$page_userid/weblog/rss\" />\n";
}

// Maximun items per page
define('POSTS_PER_PAGE',10);
?>

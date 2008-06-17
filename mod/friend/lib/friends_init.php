<?php

global $owner;
global $page_owner;
global $profile_id;

$friends_name = optional_param('friends_name');
if (!empty($friends_name)) {
    $owner = user_info_username('ident', $friends_name);
} else {
    $owner = optional_param('owner',$page_owner,PARAM_INT);
}
if (empty($owner)) {
    $owner = -1;
}

/*if (logged_on) {
    $owner = (int) $_SESSION['userid'];
}*/

$page_owner = $owner;
$profile_id = $owner;

global $page_userid;

$page_userid = user_info('username', $page_owner);

global $metatags;

if ($owner != -1) {
    $metatags .= "<link rel=\"meta\" type=\"application/rdf+xml\" title=\"FOAF\" href=\"".url."$page_userid/foaf\" />";
}
        
?>
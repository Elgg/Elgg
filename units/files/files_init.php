<?php

global $owner;

$files_name = optional_param('files_name');
if (!empty($files_name)) {
    $owner = user_info_username('ident', $files_name);
} else {
    $owner = optional_param('owner',optional_param('userid',0,PARAM_INT),PARAM_INT);
}

global $owner_username;        
$owner_username = user_info('username', $owner);

global $page_owner;

$fowner = optional_param('files_owner',$owner);
if (!empty($fowner)) {
    $page_owner = $fowner;
}

global $profile_id;
$profile_id = $owner;

global $folder;

$folder = optional_param('folder',0,PARAM_INT);
$count = count_records('file_folders','ident',$folder,'files_owner',$owner);

if (empty($count) || empty($folder)) {
    $folder = -1;
}

?>
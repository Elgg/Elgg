<?php
global $USER;

if (substr_count($parameter, "group") > 0 && logged_on) {
    $groupnum = (int) substr($parameter, 5, 15);
    $run_result = record_exists('group_membership','user_id',$USER->ident,'group_id',$groupnum);
    if (empty($run_result)) {
        $run_result = record_exists('groups','ident',$groupnum,'owner',$USER->ident);
    }
}

?>
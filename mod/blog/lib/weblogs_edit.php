<?php

// Edit weblog posts

// If a post ID has been specified, edit a specific post - otherwise create a new one
$id = optional_param('weblog_post_id',0,PARAM_INT);
$action = optional_param('action');
if (!empty($id) && $action == 'edit') {

    $run_result .= run("weblogs:posts:edit",$id);

} else {

    $run_result .= run("weblogs:posts:add");

}

// echo run("users:access_level_sql_where");
        
?>
<?php

    /*
     *   XML-RPC init stuff
     */

    // The class libraries

    include_once path . "units/rpc/lib/class_elggobject.php";
    include_once path . "units/rpc/lib/class_user.php";
    include_once path . "units/rpc/lib/class_weblog.php";
    include_once path . "units/rpc/lib/class_comment.php";
    include_once path . "units/rpc/lib/class_post.php";
    include_once path . "units/rpc/lib/class_tag.php";
    include_once path . "units/rpc/lib/class_folder.php";
    include_once path . "units/rpc/lib/class_file.php";


    // Autodiscovery editlink
    // Add to profile and weblog section

    global $metatags;

    $add_meta = false;

    if (isset($_GET['weblog_name'])) {
        $user_id = run('users:name_to_id', $_GET['weblog_name']);
        $add_meta = true;
    } else if (isset($_GET['profile_name'])) {
        $user_id = run('users:name_to_id', $_GET['profile_name']);
        $add_meta = true;
    }

    if ($add_meta) {
        $metatags .= "\n<link rel=\"EditURI\" type=\"application/rsd+xml\" title=\"RSD\" href=\"" . url . "_rpc/rsd.php?user_id=".$user_id."\" />\n";
    }
?>

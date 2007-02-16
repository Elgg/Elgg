<?php

    /*
     *   XML-RPC plug-in
     */

    // Functions to perform upon init;
        $function['init'][] = $CFG->dirroot . "units/rpc/rpc_init.php";

    // Function to log on
        $function['rpc:auth'][] = $CFG->dirroot . "units/rpc/lib/function_authenticate.php";

    // XML-RPC server
        $function['rpc:xmlrpc:server'][] = $CFG->dirroot . "units/rpc/xmlrpc/xmlrpc_server.php";

    // Users class
        $function['users:instance'][] = $CFG->dirroot . "units/rpc/lib/function_load_user.php";

    // Weblog class
        $function['weblogs:instance'][] = $CFG->dirroot . "units/rpc/lib/function_load_weblog.php";

    // Post class
        $function['posts:instance'][] = $CFG->dirroot . "units/rpc/lib/function_load_post.php";

    // Tag class
        $function['tags:instance'][] = $CFG->dirroot . "units/rpc/lib/function_load_tag.php";

    // Comment class
        $function['comments:instance'][] = $CFG->dirroot . "units/rpc/lib/function_load_comment.php";

    // Folder class
        $function['folders:instance'][] = $CFG->dirroot . "units/rpc/lib/function_load_folder.php";

    // File class
        $function['files:instance'][] = $CFG->dirroot . "units/rpc/lib/function_load_file.php";

    // Tag URI handler
        $function['tags:uri:object'][] = $CFG->dirroot . "units/rpc/lib/function_taguri_object.php";

?>

<?php

    /*
     *   XML-RPC plug-in
     */

    // Functions to perform upon init;
        $function['init'][] = path . "units/rpc/rpc_init.php";

    // Function to log on
        $function['rpc:auth'][] = path . "units/rpc/lib/function_authenticate.php";

    // XML-RPC server
        $function['rpc:xmlrpc:server'][] = path . "units/rpc/xmlrpc/xmlrpc_server.php";

    // Users class
        $function['users:instance'][] = path . "units/rpc/lib/function_load_user.php";

    // Weblog class
        $function['weblogs:instance'][] = path . "units/rpc/lib/function_load_weblog.php";

    // Post class
        $function['posts:instance'][] = path . "units/rpc/lib/function_load_post.php";

    // Tag class
        $function['tags:instance'][] = path . "units/rpc/lib/function_load_tag.php";

    // Comment class
        $function['comments:instance'][] = path . "units/rpc/lib/function_load_comment.php";

    // Folder class
        $function['folders:instance'][] = path . "units/rpc/lib/function_load_folder.php";

    // File class
        $function['files:instance'][] = path . "units/rpc/lib/function_load_file.php";

?>

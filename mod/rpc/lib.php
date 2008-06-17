<?php

    /*
     *   XML-RPC plug-in
     */

    function rpc_pagesetup() {

        global $CFG, $metatags, $function;

        $add_meta = false;

        if (isset($_GET['weblog_name'])) {
            $user_id = user_info_username('ident', $_GET['weblog_name']);
            $add_meta = true;
        } else if (isset($_GET['profile_name'])) {
            $user_id = user_info_username('ident', $_GET['profile_name']);
            $add_meta = true;
        }

        if ($add_meta) {
            $metatags .= "\n<link rel=\"EditURI\" type=\"application/rsd+xml\" title=\"RSD\" href=\"" . url . "mod/rpc/rsd.php?user_id=".$user_id."\" />\n";
        }
    }
    
    function rpc_init() {
        global $RPC, $CFG, $function;
    
        // A basic handler registry, for other plugins to register their xml-rpc calls

        include ($CFG->dirroot . "mod/rpc/lib/class_rpc_config.php");
        
        $RPC = new RpcConfig();

        // Function to log on
        $function['rpc:auth'][] = $CFG->dirroot . "mod/rpc/lib/function_authenticate.php";

        // XML-RPC server
        $function['rpc:xmlrpc:server'][] = $CFG->dirroot . "mod/rpc/xmlrpc/xmlrpc_server.php";

        // Users class
        $function['users:instance'][] = $CFG->dirroot . "mod/rpc/lib/function_load_user.php";

        // Weblog class
        $function['weblogs:instance'][] = $CFG->dirroot . "mod/rpc/lib/function_load_weblog.php";

        // Post class
        $function['posts:instance'][] = $CFG->dirroot . "mod/rpc/lib/function_load_post.php";

        // Tag class
        $function['tags:instance'][] = $CFG->dirroot . "mod/rpc/lib/function_load_tag.php";

        // Comment class
        $function['comments:instance'][] = $CFG->dirroot . "mod/rpc/lib/function_load_comment.php";

        // Folder class
        $function['folders:instance'][] = $CFG->dirroot . "mod/rpc/lib/function_load_folder.php";

        // File class
        $function['files:instance'][] = $CFG->dirroot . "mod/rpc/lib/function_load_file.php";

        // Tag URI handler
        $function['tags:uri:object'][] = $CFG->dirroot . "mod/rpc/lib/function_taguri_object.php";
    }

?>

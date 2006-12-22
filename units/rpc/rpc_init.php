<?php

    /*
     *   XML-RPC init stuff
     */

    // The class libraries

    include_once $CFG->dirroot . "units/rpc/lib/class_elggobject.php";
    include_once $CFG->dirroot . "units/rpc/lib/class_user.php";
    include_once $CFG->dirroot . "units/rpc/lib/class_weblog.php";
    include_once $CFG->dirroot . "units/rpc/lib/class_comment.php";
    include_once $CFG->dirroot . "units/rpc/lib/class_post.php";
    include_once $CFG->dirroot . "units/rpc/lib/class_tag.php";
    include_once $CFG->dirroot . "units/rpc/lib/class_folder.php";
    include_once $CFG->dirroot . "units/rpc/lib/class_file.php";


    // Autodiscovery editlink
    // Add to profile and weblog section

    global $metatags;

    $add_meta = false;

    if (isset($_GET['weblog_name'])) {
        $user_id = user_info_username('ident', $_GET['weblog_name']);
        $add_meta = true;
    } else if (isset($_GET['profile_name'])) {
        $user_id = user_info_username('ident', $_GET['profile_name']);
        $add_meta = true;
    }

    if ($add_meta) {
        $metatags .= "\n<link rel=\"EditURI\" type=\"application/rsd+xml\" title=\"RSD\" href=\"" . url . "_rpc/rsd.php?user_id=".$user_id."\" />\n";
    }

    // A basic handler registry, for other plugins to register their xml-rpc calls

    global $RPC;

    include ($CFG->dirroot . "units/rpc/lib/class_rpc_config.php");
    $RPC = new RpcConfig();

    // Blogger API
    include $CFG->dirroot . "units/rpc/xmlrpc/handlers_blogger_xmlrpc.php";
    // MoveableType API
    include $CFG->dirroot . "units/rpc/xmlrpc/handlers_mt_xmlrpc.php";
    // LiveJournal API
    include $CFG->dirroot . "units/rpc/xmlrpc/handlers_livejournal_xmlrpc.php";
    // Misc Elgg functions
    include $CFG->dirroot . "units/rpc/xmlrpc/handlers_elgg_user.php";
?>

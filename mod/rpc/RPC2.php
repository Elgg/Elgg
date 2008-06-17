<?php

    /*
    *   XML-RPC entry point
    */

    // TODO include a check for a valid request
 
    // This can be run from walled gardens
    define("context","external");
        
    // Run site includes
    require_once "../../includes.php";
    
    global $CFG;
    
    // rpc includes
    include_once $CFG->dirroot . "mod/rpc/lib/class_elggobject.php";
    include_once $CFG->dirroot . "mod/rpc/lib/class_user.php";
    include_once $CFG->dirroot . "mod/rpc/lib/class_weblog.php";
    include_once $CFG->dirroot . "mod/rpc/lib/class_comment.php";
    include_once $CFG->dirroot . "mod/rpc/lib/class_post.php";
    include_once $CFG->dirroot . "mod/rpc/lib/class_tag.php";
    include_once $CFG->dirroot . "mod/rpc/lib/class_folder.php";
    include_once $CFG->dirroot . "mod/rpc/lib/class_file.php";

    // Blogger API
    include $CFG->dirroot . "mod/rpc/xmlrpc/handlers_blogger_xmlrpc.php";
    // MoveableType API
    include $CFG->dirroot . "mod/rpc/xmlrpc/handlers_mt_xmlrpc.php";
    // LiveJournal API
    include $CFG->dirroot . "mod/rpc/xmlrpc/handlers_livejournal_xmlrpc.php";
    // Misc Elgg functions
    include $CFG->dirroot . "mod/rpc/xmlrpc/handlers_elgg_user.php";

    // Fire up the XML-RPC server
    run('rpc:xmlrpc:server');

?>

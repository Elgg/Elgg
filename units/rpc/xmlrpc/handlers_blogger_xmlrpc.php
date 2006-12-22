<?php

    // Prepare a list of handlers to be loaded into the XML-RPC server
    
    $handlers = array('blogger.newPost'           => 'generic_newPost',
                      'blogger.editPost'          => 'generic_editPost',
                      'blogger.deletePost'        => 'blogger_deletePost',
                      'blogger.getRecentPosts'    => 'generic_getRecentPosts',
                      'blogger.getUsersBlogs'     => 'blogger_getUsersBlogs',
                      'blogger.getUserInfo'       => 'blogger_getUserInfo',
                      'metaWeblog.newPost'        => 'generic_newPost',
                      'metaWeblog.getRecentPosts' => 'generic_getRecentPosts',
                      'metaWeblog.editPost'       => 'generic_editPost',
                      'metaWeblog.getPost'        => 'metaweblog_getPost',
                      'metaWeblog.newMediaObject' => 'metaweblog_newMediaObject');

    // Add the handlers to the global handlers array
    $RPC->addMapping($handlers);
    $RPC->addLibrary(dirname(__FILE__)."/library_blogger_xmlrpc.php");

?>

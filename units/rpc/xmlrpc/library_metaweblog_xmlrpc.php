<?php

    /* MetaWeblog API implementation
     *
     * Full metaWeblog implementation
     */

    // Prepare a list of handlers to be loaded into the XML-RPC server

    $handlers_metaweblog = array('metaWeblog.newPost'        => array('function' => 'metaweblog_newPost'),
                                 'metaWeblog.editPost'       => array('function' => 'metaweblog_editPost'),
                                 'metaWeblog.getPost'        => array('function' => 'metaweblog_getPost'),
                                 'metaWeblog.getRecentPosts' => array('function' => 'metaweblog_getRecentPosts'),
                                 'metaWeblog.newMediaObject' => array('function' => 'metaweblog_newMediaObject'));

?>

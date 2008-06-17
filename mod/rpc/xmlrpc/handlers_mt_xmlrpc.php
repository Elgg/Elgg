<?php

    // Prepare a list of handlers to be loaded into the XML-RPC server

    $handlers = array('mt.getRecentPostTitles'  => 'mt_getRecenPostTitles',
                      'mt.getCategoryList'      => 'mt_getCategoryList',
                      'mt.getPostCategories'    => 'mt_getPostCategories',
                      'mt.setPostCategories'    => 'mt_setPostCategories',
                      'mt.supportedMethods'     => 'mt_supportedMethods',
                      'mt.supportedTextFilters' => 'mt_supportedTextFilters',
                      'mt.publishPost'          => 'mt_publishPost');

    // Add the handlers to the global handlers array

    $RPC->addMapping($handlers);
    $RPC->addLibrary(dirname(__FILE__)."/library_mt_xmlrpc.php");

?>

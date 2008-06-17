<?php

    //    ELGG generate FOAF file

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("friends:init");

    // Whose friends are we looking at?
        global $page_owner;

        header("Content-Type: text/xml; charset=utf-8");
        header('Content-Disposition: attachment; filename=foaf.rdf');
        echo run("foaf:generate", $page_owner);

?>
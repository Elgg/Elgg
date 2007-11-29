<?php

    //    ELGG manage files page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("files:init");
        
        define("context", "files");
        templates_page_setup();

    // Whose files are we looking at?

        global $CFG, $page_owner, $owner, $folder;
        $title = run("profile:display:name") . " :: ". __gettext("Files") ."";

        $folder_object = get_record('file_folders','files_owner',$owner,'ident',$folder);

        $body = run("content:files:view");
                
        if (!is_object($folder_object) || $folder_object->handler == "elgg"
            || !isset($folder_object->handler)
            || !isset($CFG->folders->handler[$folder_object->handler]->function_name)
            || !is_callable($CFG->folders->handler[$folder_object->handler]->function_name)) {
            $body .= run("files:view",$folder_object);
        } else {
            $body .= $CFG->folders->handler[$folder_object->handler]->function_name($folder_object);
        }
        
        echo templates_page_draw( array(
                    $title,
                    templates_draw(array(
                            'context' => 'contentholder',
                            'title' => $title,
                            'body' => $body
                        )
                        )
                )
                );
                
?>
<?php

    // Template preview
    
        $header = __gettext("Folder name"); // gettext variable
        $subHeader = __gettext("Subfolders"); // gettext variable
        $body = <<< END
        
        <h2>$header</h2>
        <h3>$subHeader</h3>
        
END;
        $body .= templates_draw(array(
                                    'context' => 'folder',
                                    'username' => __gettext("test"),
                                    'url' => "",
                                    'ident' => 0,
                                    'name' => __gettext("Subfolder"),
                                    'icon' => url. "mod/file/folder.png",
                                    'menu' => "[<a href=\"#\">" . __gettext("Delete") . "</a>]"
                                )
                                );

        $body .= templates_draw(array(
                                    'context' => 'file',
                                    'username' => __gettext("test"),
                                    'title' => __gettext("A sample file"),
                                    'ident' => 0,
                                    'folder' => 0,
                                    'description' => __gettext("This is a file"),
                                    'originalname' => __gettext("filename"),
                                    'url' => "#",
                                    'icon' => url . "mod/file/file.png",
                                    'menu' => "[<a href=\"#\">" . __gettext("Edit") . "</a>] [<a href=\"#\">" . __gettext("Delete") . "</a>]"
                                )
                                );
                                
        $run_result .= templates_draw(array(
                                                    'context' => 'contentholder',
                                                    'title' => __gettext("Files and folders"),
                                                    'body' => $body,
                                                    'submenu' => ''
                                                    )
                                                    );
                                
?>
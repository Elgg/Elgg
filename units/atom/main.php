<?php
    // Atom plugin

    // Autodiscovery
    if (isset($_GET['weblog_name']))
    {
        $user = $_GET['weblog_name'];
        $metatags .= "\n<link rel=\"alternate\" 
                              type=\"application/atom+xml\" 
                              title=\"Atom feed\" 
                              href=\"".url."$user/atom/blog.atom\" />\n";

    }

?>

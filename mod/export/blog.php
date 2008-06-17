<?php

    /**
     * Elgg blog export
     * @author Ben Werdmuller <ben@curverider.co.uk>
     */

        require_once("../../includes.php");

        if (isloggedin()) {
        
        	header('Content-Disposition: attachment');
	        header("Content-type: text/xml");
	        echo export_blog_as_rss($_SESSION['userid']);
        
        }
        
?>
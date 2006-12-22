<?php
/*
NZVLE NOTES (Penny)
-------------------
this file seems to be deprecated. I can't find where it's accessed from anywhere
but I have updated it to use datalib functions anyway. Tested ok. 
*/

    //    ELGG weblog RSS 0.91 page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("profile:init");
        run("friends:init");
        run("weblogs:init");
        
        global $profile_id;
        global $individual;
        global $page_owner;
        
        $individual = 1;
        
        $output = "";
        $trackmaxtime = 0;
       
        if (isset($page_owner)) {
            
            $output .= <<< END
<?xml version="1.0"?><!DOCTYPE rss SYSTEM "http://my.netscape.com/publish/formats/rss-0.91.dtd">

<rss version="0.91">
END;
            if ($info = get_record('users','ident',$page_owner)) {
                $name = htmlspecialchars(stripslashes($info->name), ENT_COMPAT, 'utf-8');
                $username = htmlspecialchars($info->username, ENT_COMPAT, 'utf-8');
                $sitename = sitename;
                $mainurl = htmlspecialchars(url . $username . "/weblog/", ENT_COMPAT, 'utf-8');
                $output .= <<< END
    <channel>
        <title>$name : Weblog</title>
        <description>The weblog for $name, hosted on $sitename.</description>
        <language>en-gb</language>
        <link>$mainurl</link>
END;
                if ($entries = get_records_select('weblog_posts',"weblog = ? AND access = ? ",array($page_owner,'PUBLIC'),'posted DESC','*',0,10)) {
                    foreach($entries as $entry) {
                        $title = htmlspecialchars(stripslashes($entry->title), ENT_COMPAT, 'utf-8');
                        $trackmaxtime = max($trackmaxtime, $entry->posted);
                        $link = url . $username . "/weblog/" . $entry->ident . ".html";
                        $body = (run("weblogs:text:process",stripslashes($entry->body)));
                        $output .= <<< END
        <item>
            <title>$title</title>
            <link>$link</link>
            <description>$body</description>
        </item>
END;
                    }
                }
                $output .= <<< END
    </channel>
</rss>
END;
            }
            
            if ($output) {
                header("Pragma: public");
                header("Cache-Control: public"); 
                header('Expires: ' . gmdate("D, d M Y H:i:s", (time()+3600)) . " GMT");
                
                $if_modified_since = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) ? preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']) : false;
                $if_none_match = (isset($_SERVER['HTTP_IF_NONE_MATCH'])) ? preg_replace('/[^0-9a-f]/', '', $_SERVER['HTTP_IF_NONE_MATCH']) : false;
                
                if (!$trackmaxtime) {
                    $trackmaxtime = time();
                }
                
                $lm = gmdate("D, d M Y H:i:s", $trackmaxtime) . " GMT";
                $etag = md5($output);
                
                if ($if_modified_since && $if_modified_since == $lm) {
                    header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
                    exit;
                }
                if ($if_none_match && $if_none_match == $etag) {
                    header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
                    exit;
                }
                
                // Send last-modified header to enable if-modified-since requests
                if ($tstamp < time()) {
                    header("Last-Modified: " . $lm);
                }
                
                header("Content-Length: " . strlen($output));
                header('ETag: "' . $etag . '"');
                
                header("Content-type: text/xml; charset=utf-8");
                echo $output;
            }
            
            
        }
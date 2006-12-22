<?php
global $CFG;
global $db;
global $search_exclusions;

if (isset($parameter) && $parameter[0] == "weblog") {
    
    $sitename = sitename;
    
    $owner = optional_param('owner');
    $searchline = "tagtype = 'weblog' and tag = " . $db->qstr($parameter[1]) . "";
    $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
    $searchline = str_replace("access", "wp.access", $searchline);
    $searchline = str_replace("owner", "wp.weblog", $searchline);
    if ($refs = get_records_sql('SELECT DISTINCT wp.owner FROM '.$CFG->prefix.'tags t
                                 LEFT JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = t.ref 
                                 LEFT JOIN '.$CFG->prefix.'users u ON u.ident = t.owner
                                 WHERE ('.$searchline.') ORDER BY wp.posted DESC LIMIT 500')) {
        foreach($refs as $post) {
            $page_owner = $post->owner;
            
            $run_result .= <<< END
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
END;
            if ($info = get_record('users','ident',$page_owner)) {
                $name = htmlspecialchars(stripslashes($info->name), ENT_COMPAT, 'utf-8');
                $username = htmlspecialchars($info->username, ENT_COMPAT, 'utf-8');
                $mainurl = htmlspecialchars(url . $username . "/weblog/", ENT_COMPAT, 'utf-8');
                $run_result .= <<< END
    <channel xml:base='$mainurl'>
        <title>$name : Weblog</title>
        <description>The weblog for $name, hosted on $sitename.</description>
        <language>en-gb</language>
        <link>$mainurl</link>
END;
                $tag = trim(optional_param('tag'));
                if (empty($tag)) {
                    $entries = get_records_select('weblog_posts',"weblog = ? AND access = ?",array($page_owner,'PUBLIC'),'posted DESC',0,500);
                } else {
                    $entries = get_records_sql('SELECT wp.* FROM '.$CFG->prefix.'tags t 
                                               LEFT JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = t.ref
                                               WHERE wp.weblog = ? AND wp.access = ? AND t.tag = ? AND t.tagtype = ?
                                               ORDER BY wp.posted DESC LIMIT 10',
                                               array($page_owner,'PUBLIC',$tag,'weblog'));
                }
                if (!empty($entries)) {
                    foreach($entries as $entry) {
                        $title = htmlspecialchars(stripslashes($entry->title), ENT_COMPAT, 'utf-8');
                        $link = url . $username . "/weblog/" . $entry->ident . ".html";
                        $body = htmlspecialchars(run("weblogs:text:process",stripslashes($entry->body)), ENT_COMPAT, 'utf-8');
                        $pubdate = gmdate("D, d M Y H:i:s T", $entry->posted);
                        $keywordtags = "";
                        if ($keywords = get_records_select('tags',"tagtype = ? AND ref = ?",array('weblog',$entry->ident))) {
                            foreach($keywords as $keyword) {
                                $keywordtags .= "\n        <dc:subject><![CDATA[".htmlspecialchars($keyword->tag, ENT_COMPAT, 'utf-8') . "]]></dc:subject>";
                            }
                        }
                        $run_result .= <<< END
        <item>
            <title><![CDATA[$title]]></title>
            <link>$link</link>
            <pubDate>$pubdate</pubDate>$keywordtags
            <description><![CDATA[$body]]></description>
        </item>
END;
                    }
                }
                $run_result .= <<< END
    </channel>
</rss>
END;
            }
            
        }
    }
}

?>
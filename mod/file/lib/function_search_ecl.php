<?php
global $CFG;
global $search_exclusions;

if (isset($parameter) && $parameter[0] == "file") {
    
    $search_exclusions[] = "folder";
    $search_exclusions[] = "file";
    
    $sitename = sitename;
    
    $parameter[1] = trim($parameter[1]);
    
    if ($file_refs = get_records_sql('SELECT DISTINCT t.owner,1 FROM '.$CFG->prefix.'tags t 
                                     LEFT JOIN '.$CFG->prefix."files f ON f.ident = t.refs
                                     WHERE (t.tagtype = ? OR t.tagtype = ?)
                                     AND t.tag = ? AND t.access = ?
                                     ORDER BY f.time_uploaded DESC LIMIT 50",
                                     array('file','folder',$parameter[1],'PUBLIC'))) {
        foreach($file_refs as $post) {
            $page_owner = $post->owner;
                
            $run_result .= <<< END
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
END;
            if ($info = get_record('users','ident',$page_owner)) {
                $name = htmlspecialchars(stripslashes(user_name($info->ident)), ENT_COMPAT, 'utf-8');
                $username = htmlspecialchars($info->username, ENT_COMPAT, 'utf-8');
                $mainurl = url . $username . "/files/";
                $run_result .= <<< END
    <channel xml:base='$mainurl'>
        <title>$name : Files</title>
        <description>Files for $name, hosted on $sitename.</description>
        <language>en-gb</language>
        <link>$mainurl</link>
END;
                $tag = trim(optional_param('tag'));
                if (empty($tag)) {
                    $files = get_records_select('files',"files_owner = $page_owner AND access = 'PUBLIC' ORDER BY time_uploaded DESC LIMIT 10");
                } else {
                    $files = get_records_sql('SELECT f.* from '.$CFG->prefix.'tags t
                                              LEFT JOIN '.$CFG->prefix.'files f ON f.ident = t.ref
                                              WHERE t.owner = ? AND f.access = ?
                                              AND t.tagtype = ? AND t.tag = ?
                                              ORDER BY f.time_uploaded DESC LIMIT 10',
                                             array($page_owner,'PUBLIC','file',$tag));
                }
                if (!empty($files)) {
                    foreach($files as $file) {
                        $title = htmlspecialchars(stripslashes($file->title), ENT_COMPAT, 'utf-8');
                        $link = url . $username . "/files/" . $file->folder . "/" . $file->ident . "/" . htmlspecialchars(urlencode(stripslashes($file->originalname)), ENT_COMPAT, 'utf-8');
                        $description = htmlspecialchars(stripslashes($file->description), ENT_COMPAT, 'utf-8');
                        $pubdate = gmdate("D, d M Y H:i:s T", $file->time_uploaded);
                        $length = (int) $file->size;
                        require_once($CFG->dirroot.'lib/filelib.php');
                        $mimetype = mimeinfo('type',$file->location);
                        $keywordtags = "";
                        if ($keywords = get_records_select('tags',"tagtype = ? AND ref = ?",array('file',$file->ident))) {
                            foreach($keywords as $keyword) {
                                $keywordtags .= "\n\t\t<dc:subject>".htmlspecialchars(stripslashes($keyword->tag), ENT_COMPAT, 'utf-8') . "</dc:subject>";
                            }
                        }
                        $run_result .= <<< END
                            
        <item>
            <title>$title</title>
            <link>$link</link>
            <enclosure url="$link" length="$length" type="$mimetype" />
            <pubDate>$pubdate</pubDate>$keywordtags
            <description>$description</description>
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
<?php
global $CFG;
/*
 *    Function to get RSS item blocks for a filestore
 *    
 *    $parameter[0] is the numeric id of the user to retrieve
 *    $parameter[1] is the number of entries to retrieve
 *    $parameter[2] is a tag to search for, if any
 *
 */

    $run_result = "";

    global $page_owner;
    
    if (isset($parameter) && is_array($parameter)) {
        
        $userid = (int) $parameter[0];
        if ($userid > 0) {
            $username = user_info('username', $userid);
        }
        if ($username) {
            
            $numrows = (int) $parameter[1];
            if (!$numrows) {
                $numrows = 10;
            }
            
            $tag = trim($parameter[2]);
            if (empty($tag)) {
                $files = get_records_sql('SELECT * FROM '.$CFG->prefix.'files where files_owner = ? and access = ? order by time_uploaded desc limit ?',array($parameter[0],'PUBLIC',$numrows));
            } else {
                $files = get_records_sql('SELECT f.* FROM '.$CFG->prefix.'tags t
                                          JOIN '.$CFG->prefix.'files f ON f.ident = t.ref
                                          WHERE f.files_owner = ? AND f.access = ?
                                          AND t.tagtype = ? AND t.tag = ?
                                          ORDER BY f.time_uploaded DESC LIMIT 10',array($parameter[0],'PUBLIC','file',$tag)); 
            }
            
            if (is_array($files) && sizeof($files) > 0) {
                require_once($CFG->dirroot . 'lib/filelib.php');
                foreach($files as $file) {
                    $title = stripslashes($file->title);
                    $link = url . $username . "/files/" . $file->folder . "/" . $file->ident . "/" . (urlencode(stripslashes($file->originalname)));
                    $description = stripslashes($file->description);
                    $pubdate = gmdate("D, d M Y H:i:s T", $file->time_uploaded);
                    // $trackmaxtime = max($trackmaxtime, $file->time_uploaded);
                    $length = (int) $file->size;
                    $mimetype = mimeinfo('type',$file->location);
                    $keywordtags = "";
                    if ($keywords = get_records_select('tags',"tagtype = ? AND ref = ?",array('file',$file->ident))) {
                        foreach($keywords as $keyword) {
                            $keywordtags .= "\n\t\t<dc:subject><![CDATA[". (stripslashes($keyword->tag)) . "]]></dc:subject>";
                        }
                    }
                    $run_result .= <<< END

        <item>
            <title><![CDATA[$title]]></title>
            <link>$link</link>
            <enclosure url="$link" length="$length" type="$mimetype" />
            <pubDate>$pubdate</pubDate>$keywordtags
            <description><![CDATA[$description]]></description>
        </item>
END;
                }
            } else {
                //$run_result .= "no items";
            }
            
        } // if ($username)
        
    }

?>
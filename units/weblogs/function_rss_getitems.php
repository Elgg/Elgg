<?php
global $CFG;
/*
 *    Function to get RSS item blocks for a weblog
 *    
 *    $parameter[0] is the numeric id of the user to retrieve
 *    $parameter[1] is the number of entries to retrieve
 *    $parameter[2] is a tag to search for, if any
 *
 */

    $run_result = "";

    if (isset($parameter) && is_array($parameter)) {
        
        $userid = (int) $parameter[0];
        //if ($userid > 0) {
        //    $username = user_info('username', $userid);
        //}
        // if ($username) {
            
            $numrows = (int) $parameter[1];
            if (!$numrows) {
                $numrows = 10;
            }
            
            $tag = trim($parameter[2]);
            if (isset($parameter[3]) && $parameter[3] == "not") {
                $entries = get_records_sql('SELECT wp.*, u.username FROM '.$CFG->prefix.'weblog_posts wp JOIN '.$CFG->prefix.'users u ON u.ident = wp.weblog
                                            WHERE wp.weblog = ? AND wp.access = ?  
                                            AND (SELECT count(ident) FROM '.$CFG->prefix.'tags t WHERE t.tagtype = ? AND t.tag = ? AND t.ref = wp.ident) = ?
                                            ORDER BY wp.posted DESC LIMIT '.$numrows,array($userid,'PUBLIC','weblog',$tag,0));
            } else if (isset($parameter[3]) && $parameter[3] == "all") {
                $entries = get_records_sql('SELECT wp.*, u.username FROM '.$CFG->prefix.'weblog_posts wp JOIN '.$CFG->prefix.'users u ON u.ident = wp.weblog WHERE wp.access = ?  
                                            AND (SELECT count(ident) FROM '.$CFG->prefix.'tags t WHERE t.tagtype = ? AND t.tag = ? AND t.ref = wp.ident) = ?
                                            ORDER BY wp.posted DESC LIMIT '.$numrows,array('PUBLIC','weblog',$tag,0));
            } else if ($tag) {
                $entries = get_records_sql('SELECT wp.*, u.username FROM '.$CFG->prefix.'tags t JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = t.ref
                                            JOIN '.$CFG->prefix.'users u on u.ident = wp.weblog
                                            WHERE wp.weblog = ? AND wp.access = ? AND t.tag = ? AND t.tagtype = ?
                                            ORDER BY wp.posted DESC limit '.$numrows,array($userid,'PUBLIC',$tag,'weblog'));
            } else {
                // $entries = get_records_select('weblog_posts','weblog = ? AND access = ?',array($userid,'PUBLIC'),'posted DESC','*',0,$numrows);
                $entries = get_records_sql('SELECT wp.*, u.username FROM '.$CFG->prefix.'weblog_posts wp JOIN '.$CFG->prefix.'users u ON u.ident = wp.weblog
                                            WHERE wp.weblog = ? AND wp.access = ?
                                            ORDER BY wp.posted DESC LIMIT '.$numrows, array($userid, 'PUBLIC'));
                
            }

            if (is_array($entries) && sizeof($entries) > 0) {
                foreach($entries as $entry) {
                    $title = (stripslashes($entry->title));
                    $link = url . $entry->username . "/weblog/" . $entry->ident . ".html";
                    $body = (run("weblogs:text:process",stripslashes($entry->body)));
                    $pubdate = gmdate("D, d M Y H:i:s T", $entry->posted);
                    $keywordtags = "";
                    if ($keywords = get_records_select('tags','tagtype = ? AND ref = ?',array('weblog',$entry->ident))) {
                        foreach($keywords as $keyword) {
                            $keywordtags .= "\n\t\t<dc:subject><![CDATA[" . (stripslashes($keyword->tag)) . "]]></dc:subject>";
                        }
                    }
                    $run_result .= <<< END
        
        <item>
            <title><![CDATA[$title]]></title>
            <link>$link</link>
            <guid isPermaLink="true">$link</guid>
            <pubDate>$pubdate</pubDate>$keywordtags
            <description><![CDATA[$body]]></description>
        </item>
        
END;
                }
            } else {
                //$run_result .= "no items";
            }
            
        // } // if ($username)
        
    }

?>
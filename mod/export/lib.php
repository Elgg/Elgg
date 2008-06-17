<?php

    /**
     * Weblog export function
     * @author Ben Werdmuller <ben@curverider.co.uk>
     */

        function export_pagesetup() {
            global $page_owner, $PAGE, $CFG;
            if (defined('context') && context == 'weblog') {
                if ($page_owner == $_SESSION['userid']) {
                	$PAGE->menu_sub[]= array (
                        'name' => 'blog:export:html',
                        'html' => "<a href=\"{$CFG->wwwroot}mod/export/blogashtml.php/export.html\">". __gettext("Download blog as HTML") ."</a>"
                    );
                    $PAGE->menu_sub[]= array (
                        'name' => 'blog:export:rss',
                        'html' => "<a href=\"{$CFG->wwwroot}mod/export/blog.php/export.rss\">". __gettext("Download blog as RSS") ."</a>"
                    );
                }
            }
        }
        
        function export_init() {
        }
        
        /**
         * Exports a weblog as RSS
         *
         * @param int $blog_id The ID of the blog to export
         * @return string The RSS feed
         */
        function export_blog_as_rss($blog_id = -1) {
        	
        	global $CFG;
        	
        	if ($blog_id < 0) {
        		$blog_id = $_SESSION['id'];
        	}
        	$blog_id = (int) $blog_id;
        	
        	$name = user_info("name", $blog_id);
        	$username = user_info("username", $blog_id);

        	$rssweblog = __gettext("Weblog items");
        	$rssdescription = sprintf(__gettext("The weblog for %s, hosted on %s."),$name,$CFG->sitename);

            $output .= <<< END
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
    <channel xml:base='{$CFG->wwwroot}'>
        <title><![CDATA[$name : $rssweblog]]></title>
        <description><![CDATA[$rssdescription]]></description>
        <link>{$CFG->wwwroot}{$username}/weblog/</link>
END;

                $where = run("users:access_level_sql_where",$_SESSION['userid']);
                if ($posts = get_records_select('weblog_posts','('.$where.') AND weblog = '.$blog_id,null,'posted DESC','*')) {
                    foreach($posts as $entry) {
	                    $title = (stripslashes($entry->title));
	                    $link = url . $username . "/weblog/" . $entry->ident . ".html";
	                    $body = (run("weblogs:text:process",stripslashes($entry->body)));
	                    $pubdate = gmdate("D, d M Y H:i:s T", $entry->posted);
	                    $keywordtags = "";
	                    if ($keywords = get_records_select('tags','tagtype = ? AND ref = ?',array('weblog',$entry->ident))) {
	                        foreach($keywords as $keyword) {
	                            $keywordtags .= "\n\t\t<dc:subject><![CDATA[" . (stripslashes($keyword->tag)) . "]]></dc:subject>";
	                        }
	                    }
	                    $output .= <<< END
        
        <item>
            <title><![CDATA[$title]]></title>
            <link>$link</link>
            <guid isPermaLink="true">$link</guid>
            <pubDate>$pubdate</pubDate>$keywordtags
            <description><![CDATA[$body]]></description>
        </item>
        
END;
                    }
                }

                //$output .= run("weblogs:rss:getitems", array($blog_id, 10000,null,"complete"));

                $output .= <<< END

    </channel>
</rss>
END;
        	return $output;
        }
        
        /**
         * Exports a blog as HTML
         *
         * @param int $blog_id The blog to export
         * @return string The HTML file
         */
        function export_blog_as_html($blog_id = -1) {
            
            global $CFG;
            
            if ($blog_id < 0) {
                $blog_id = $_SESSION['id'];
            }
            $blog_id = (int) $blog_id;
            
            $name = user_info("name", $blog_id);
            $username = user_info("username", $blog_id);

            $rssweblog = __gettext("Weblog items");
            $rssdescription = sprintf(__gettext("The weblog for %s, hosted on %s."),$name,$CFG->sitename);

            $output .= <<< END
            <html>
                <head>
                    <title>{$name}: {$rssweblog}</title>
                </head>
                <body>
                    <h1>{$name}: {$rssweblog}</h1>
                    <p><i>{$rssdescription}</i></p>
                    <p>
                        <a href="{$CFG->wwwroot}{$username}/weblog/">{$CFG->wwwroot}{$username}/weblog/</a>
                    </p>
END;

                $where = run("users:access_level_sql_where",$_SESSION['userid']);
                if ($posts = get_records_select('weblog_posts','('.$where.') AND weblog = '.$blog_id,null,'posted DESC','*')) {
                    foreach($posts as $entry) {
                        $title = (stripslashes($entry->title));
                        $link = url . $username . "/weblog/" . $entry->ident . ".html";
                        $body = (run("weblogs:text:process",stripslashes($entry->body)));
                        $pubdate = gmdate("D, d M Y H:i:s T", $entry->posted);
                        $keywordtags = "";
                        if ($keywords = get_records_select('tags','tagtype = ? AND ref = ?',array('weblog',$entry->ident))) {
                            foreach($keywords as $keyword) {
                            	if (!empty($keywordtags))
                                    $keywordtags .= ", ";
                                $keywordtags .= stripslashes($keyword->tag);
                            }
                        }
                        if (!empty($keywordtags)) {
                        	$keywordtags = "<p>Keywords: {$keywordtags}</p>";
                        }
                        $output .= <<< END
                    <div class="weblog-post">
	                    <h2>{$title}</h2>
	                    <p>{$pubdate}</p>
	                    <p><i><a href="{$link}">{$link}</a></i></p>
	                    {$body}
	                    {$keywordtags}
	                    <p>&nbsp;</p>
                    </div>
END;
                    }
                }

                //$output .= run("weblogs:rss:getitems", array($blog_id, 10000,null,"complete"));

                $output .= <<< END

                </body>
            </html>
    
END;
            return $output;
        }

?>
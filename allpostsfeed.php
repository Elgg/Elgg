<?php 
# allpostsfeed.php : script to create a feed out of all public weblog
# post of a single elgg installation
# coded by Vermario (www.vermario.com) , based by index.php for elgg.net

//let's include the basic stuff
require_once("includes.php");

# let's create the top part of the feed, with the usual declarations:
# for the language part, let's use the locale setting, that makes some sense.

$output = '<?xml version="1.0" encoding="UTF-8" ?' . '>';
$output .= <<< END
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title><![CDATA[{$CFG->sitename}: Latest blog posts]]></title>
<link>{$CFG->wwwroot}</link>
<description><![CDATA[Latest public blog posts from {$CFG->sitename}]]></description>
<generator><![CDATA[{$CFG->sitename}]]></generator>
<language>{$CFG->defaultlocale}</language>
END;

// query the database directly:
$posts = get_records_sql('SELECT wp.ident, u.name, u.username, wp.body, wp.title, wp.ident as postid, wp.posted
FROM '.$CFG->prefix.'weblog_posts wp
LEFT JOIN '.$CFG->prefix.'users u ON u.ident = wp.weblog
WHERE wp.access = "PUBLIC"
ORDER BY wp.posted DESC LIMIT 20');

foreach ($posts as $post) {
    
    $body = strip_tags($post->body);
    $body = preg_replace( "|\w{3,10}://[\w\.\-_]+(:\d+)?[^\s\"\'<>\(\)\{\}]*|", "", $body);
    $date = date("D, d M Y G:i:s O",$post->posted);
    $output .= <<< END
    
    <item>
        <title><![CDATA[{$post->title}]]></title>
        <link>{$CFG->wwwroot}{$post->username}/weblog/{$post->postid}.html</link>
        <guid isPermaLink="true">{$CFG->wwwroot}{$post->username}/weblog/{$post->postid}.html</guid>
        <pubDate>{$date}</pubDate>
        <description><![CDATA[{$body}]]></description>
    </item>
    
END;

}

$output .= <<< END
</channel></rss>
END;

    if ($output) {
        header("Pragma: public");
        header("Cache-Control: public"); 
        header('Expires: ' . gmdate("D, d M Y H:i:s", (time()+60)) . " GMT");
        
        $if_none_match = (isset($_SERVER['HTTP_IF_NONE_MATCH'])) ? preg_replace('/[^0-9a-f]/', '', $_SERVER['HTTP_IF_NONE_MATCH']) : false;
        
        $etag = md5($output);
        header('ETag: "' . $etag . '"');
        
        if ($if_none_match && $if_none_match == $etag) {
            header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
            exit;
        }
        
        header("Content-Length: " . strlen($output));
        
        header("Content-type: text/xml; charset=utf-8");
        echo $output;
    }
    
?>
<?php
global $CFG;
//    ELGG files RSS 2.0 page
// this is now only used for tag-search feeds

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

run("profile:init");
run("friends:init");
run("files:init");
        
global $profile_id;
global $individual;
global $page_owner;

$individual = 1;

$sitename = htmlspecialchars(sitename, ENT_COMPAT, 'utf-8');
$tag = trim(optional_param('tag'));
$output = "";
       
if (isset($profile_id)) {

    $rssfiles = sprintf(__gettext("Files tagged with %s"),$tag);

    if ($info = get_record('users','ident',$page_owner)) {
        $name = stripslashes(user_name($info->ident));
        $mainurl = $CFG->wwwroot . $info->username . "/files/";
        $rssurl = $mainurl . "rss/" . urlencode($tag);
        $rssdescription = sprintf(__gettext("Files for %s, hosted on %s."),$name,$sitename);
        $output .= <<< END
<?xml-stylesheet type="text/xsl" href="{$rssurl}/rssstyles.xsl"?>
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
    <channel xml:base='$mainurl'>
        <title><![CDATA[$name : $rssfiles]]></title>
        <description><![CDATA[$rssdescription]]></description>
        <link>$mainurl</link>
END;
        $output .= run("files:rss:getitems", array($page_owner, 10, $tag));
        $output .= <<< END

    </channel>
</rss>
END;
    }
    if ($output) {
        header("Pragma: public");
        header("Cache-Control: public"); 
        header('Expires: ' . gmdate("D, d M Y H:i:s", (time()+3600)) . " GMT");
        
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
}
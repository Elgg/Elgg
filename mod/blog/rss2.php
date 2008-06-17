<?php
global $CFG;
//    ELGG weblog RSS 2.0 page
// this is now only used for tag-search feeds

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

run("profile:init");
run("friends:init");
run("weblogs:init");

global $page_owner;
$tag = trim(optional_param('tag'));
$modifier = optional_param('modifier');

if ($modifier == "all") {
    $page_owner = -1;
}

if (isset($page_owner)) {

    if ($page_owner != -1) {
        $username = user_info('username', $page_owner);
    } else {
        $username = "news";
    }
    if ($username) {
        /*
        if (!isset($_REQUEST['tag']) || trim($_REQUEST['tag'])=="" ) {
            // no tag, serve plain file
        $publish_folder = substr($username,0,1);
        NOTE THAT IF THIS GETS UNCOMMENTED THIS NEEDS TO CHANGE TO BE INSIDE DATAROOT NOW. (Penny)
        $rssfile = path . "_rss/data/" . $publish_folder . "/" . $username . "/weblog.xml";
        $rssurl = url . $username . "/weblog/rss2/";

        if (!file_exists($rssfile)) {
            $rssresult = run("weblogs:rss:publish", array($page_owner, false));
        }
        header("{$_SERVER['SERVER_PROTOCOL']} 301 Moved Permanently");
        header("Location: $rssurl");
        exit;
        } else {
            // a tag has been set
            // not using static file for tags, because number of tags * number of users...
*/
            $sitename = sitename;

            $output = "";
            if ($tag) {
                $rssweblog = sprintf(__gettext("Weblog items tagged with %s"),$tag);
                $tagurl = urlencode($tag) . '/';
            } else {
                $rssweblog = __gettext("Weblog items");
                $tagurl = '';
            }

            if ($page_owner == -1 || $info = get_record('users','ident',$page_owner)) {
                if ($page_owner == -1) {
                    $info = (object) "";
                    $name = __gettext("All users");
                    //$xslurl = $CFG->wwwroot . "news/weblog/rss/" . urlencode(trim($tag)) . "/rssstyles.xsl";
                    $xslurl = "";
                } else {
                    $mainurl = $CFG->wwwroot . $info->username . "/weblog/";
                    $rssurl = $mainurl . "rss/" . $tagurl;
                    $xslurl = $mainurl . "rss/" . $tagurl . "rssstyles.xsl";
                }

                switch($modifier) {

                    case "all":     $rssurl = $CFG->wwwroot . "_weblog/rss2.php?page_owner=-1&amp;modifier=all";
                                    $mainurl = $CFG->wwwroot . "_weblog/everyone.php";
                                    $rssdescription = sprintf(__gettext("The most recent weblog posts on %s."),$sitename);
                                    $rssweblog = $rssdescription;
                                    break;
                    default:        $name = (stripslashes(user_name($info->ident)));
                                    $rssdescription = sprintf(__gettext("The weblog for %s, hosted on %s."),$name,$sitename);
                                    break;

                }

                    /* <?xml-stylesheet type="text/xsl" href="{$CFG->wwwroot}_rss/styles.xsl?url=$mainurl&rssurl=$rssurl"?> */
                    /* <?xml-stylesheet type="text/xsl" href="{$rssurl}/rssstyles.xsl"?> */
                if (!empty($xslurl)) {
                    $output .= "<?xml-stylesheet type=\"text/xsl\" href=\"{$xslurl}\"?>\n\n";
                }
                $output .= <<< END
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
    <channel xml:base='$mainurl'>
        <title><![CDATA[$name : $rssweblog]]></title>
        <description><![CDATA[$rssdescription]]></description>
        <link>$mainurl</link>
END;
                $modifier = optional_param('modifier');
                $output .= run("weblogs:rss:getitems", array($page_owner, 10, $tag, $modifier));

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
            //        }
    }
}

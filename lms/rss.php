<?php
/** 
We're either going to return an rss feed of the users recent activity,
or we're going to return an invalid rss fragment which means that
the lms will have to do something else (like display a 'join' link)
*/

require_once(dirname(dirname(__FILE__)).'/includes.php');
require_once($CFG->dirroot.'lib/lmslib.php');

// the POST parameters we expect are:
$installid = optional_param('installid');
$username = optional_param('username');
$firstname = optional_param('firstname');
$lastname = optional_param('lastname');
$email = optional_param('email');
$signature = optional_param('signature');

$user = find_lms_user($installid,$username,$signature,null,$firstname,$lastname,$email);
if (is_string($user)) {
    echo $user;
    die();
} 
// else we have a the user object.

// now start doing the rss feed
$starttime = time()-(86400*30);
$rssdescription = sprintf(__gettext("Activity for %s, hosted on %s."),$user->name,$CFG->sitename);
$mainurl = $CFG->wwwroot.'_activity/';
echo '
<rss version="2.0"   xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel xml:base="'.$mainurl.'">
        <title><![CDATA['.$user->name.' : '.__gettext("Activity").']]></title>
        <description><![CDATA['.$rssdescription.']]></description>
        <link>'.$mainurl.'</link>
';
$something = false;
if ($activities = get_records_sql('SELECT u.username,wc.*,wp.ident AS weblogpost,wp.title AS weblogtitle, wp.weblog AS weblog
                                   FROM '.$CFG->prefix.'weblog_comments wc LEFT JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = wc.post_id
                                   LEFT JOIN '.$CFG->prefix.'users u on u.ident = wp.weblog 
                                   WHERE wc.posted >= ? AND wp.owner = ? 
                                   ORDER BY wc.posted DESC LIMIT 10',array($starttime,$user->ident))) {
    $postedby = __gettext("%s posted on weblog post:");
    foreach($activities as $activity) {
        $title = sprintf($postedby,stripslashes($activity->postedname)) . " " . stripslashes($activity->weblogtitle);
        $link = $CFG->wwwroot.$activity->username . "/weblog/" . $activity->weblogpost . ".html";
        $pubdate = strftime("%B %d, %Y",$activity->posted);
        $body = stripslashes($activity->body);
        echo "
        <item>
            <title><![CDATA[$title]]></title>
            <link>$link</link>
            <pubDate>$pubdate</pubDate>
            <description><![CDATA[$body]]></description>
        </item>
";
        $something = true;
    }
} 
if ($activities = get_records_sql('SELECT DISTINCT u.username,u.name as weblogname, wc.*,wp.weblog, wp.ident AS weblogpost, wp.title AS weblogtitle, wp.weblog AS weblog
                                   FROM '.$CFG->prefix.'weblog_watchlist wl LEFT JOIN '.$CFG->prefix.'weblog_comments wc ON wc.post_id = wl.weblog_post
                                   LEFT JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = wc.post_id
                                   LEFT JOIN '.$CFG->prefix.'users u ON u.ident = wp.weblog 
                                   WHERE wl.owner = ? AND wc.posted >= ? ORDER BY wc.posted DESC',array($user->ident,$starttime))) {
    $postedby = __gettext("%s posted on weblog post '%s'");
    foreach($activities as $activity) {
        $title = sprintf($postedby,stripslashes($activity->postedname), stripslashes($activity->weblogtitle));
        $link = $CFG->wwwroot.$activity->username . "/weblog/" . $activity->weblogpost . ".html";
        $pubdate = strftime("%B %d, %Y",$activity->posted);
        $body = stripslashes($activity->body);
        echo "
        <item>
            <title><![CDATA[$title]]></title>
            <link>$link</link>
            <pubDate>$pubdate</pubDate>
            <description><![CDATA[$body]]></description>
        </item>
";
        $something = true;
     }
}
if (empty($something)) {
    $title = __gettext('No activity');
    $link = $mainurl;
    echo "
        <item>
            <title><![CDATA[$title]]></title>
            <link>$link</link>
            <description><![CDATA[$title]]></description>
        </item>
";
}
echo '
    </channel>
</rss>';

?>
<?php
global $CFG;
global $db;

$sitename = sitename;

$run_result = "<h5>" . __gettext("Welcome") . "</h5>";
$run_result .= "<p>" . sprintf(__gettext("This is %s, a learning landscape. Why not check out <a href=\"%s\">what people are saying</a> right now."), $sitename, url . "_weblog/everyone.php") . "<br />";
$run_result .= "<br />". sprintf(__gettext("<a href=\"%s\">Find others</a> with similar interests and goals."), url . "search/tags.php") . "<br /><br />";

if ($users = get_records_sql("SELECT DISTINCT u.*,i.filename AS iconfile, ".$db->random." as rand 
                            FROM ".$CFG->prefix."tags t JOIN ".$CFG->prefix."users u ON u.ident = t.owner
                            LEFT JOIN ".$CFG->prefix."icons i ON i.ident = u.icon 
                            WHERE t.tagtype IN (?,?,?) AND u.icon != ? AND t.access = ? AND u.user_type = ? 
                            ORDER BY rand LIMIT 3",array('biography','minibio','interests',-1,'PUBLIC','person'))) {
    if (count($users) > 1) {
        $run_result .= __gettext("Here are some example users:");
    } else {
        $run_result .= __gettext("Here is an example user:");
    }
    foreach($users as $key => $user) {
        if ($key > 0) {
            $run_result .= ", ";
        } else {
            $run_result .= " ";
        }
        $run_result .= "<a href=\"" . url . $user->username . "/\">" . stripslashes($user->name) . "</a>";
    }
}
    
$run_result .= "</p>";

$run_result .= "<p>" . sprintf(__gettext("If you like what you see, why not <a href=\"%s\">register for an account</a>?"), url . "_invite/register.php") . "</p>";
$run_result .= "<p>&nbsp;</p>";

if ($news = get_record_sql("SELECT wp.* FROM ".$CFG->prefix."weblog_posts wp
                            JOIN ".$CFG->prefix."users u ON u.ident = wp.weblog
                            WHERE u.username = ? ORDER BY posted DESC",array('news'),false)) {
    $run_result .= "<div class=\"siteNews\">";
    $run_result .= "<h2>" . __gettext("Latest news") . "</h2>";
    $run_result .= "<p>" . run("weblogs:text:process",nl2br(stripslashes($news->body))) . "</p>";
    $run_result .= "</div>";
}

?>
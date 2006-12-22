<?php
global $CFG;
global $db;

$sitename = sitename;

$run_result = "<h3>" . __gettext("Welcome") . "</h3>";
$run_result .= "<p><b>" . sprintf(__gettext("Why not <a href=\"%s\">create your profile</a>?"), url . "profile/edit.php") . "</b></p>";
$run_result .= "<p>". __gettext("Tell people about yourself and connect to others with similar interests and goals.") . "<br />";

if ($users = get_records_sql("SELECT DISTINCT u.*,i.filename AS iconfile, ".$db->random." as rand 
                            FROM ".$CFG->prefix."tags t JOIN ".$CFG->prefix."users u ON u.ident = t.owner
                            LEFT JOIN ".$CFG->prefix."icons i ON i.ident = u.icon 
                            WHERE t.tagtype IN (?,?,?) AND u.icon != ? AND t.access = ? AND u.user_type = ? 
                            ORDER BY rand LIMIT 3",array('biography','minibio','interests',-1,'PUBLIC','person'))) {
    if (count($users) > 1) {
        $run_result .= __gettext("Here are some examples of complete profiles:");
    } else {
        $run_result .= __gettext("Here is an example of a complete profile:");
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
    
$run_result .= "<p><b>" . sprintf(__gettext("Or you could <a href=\"%s\">start your blog</a>?"),url . "_weblog/edit.php") . "</b><br /><br />";
$run_result .= sprintf(__gettext("Comment on what you're learning, collect interesting links and decide who gets to see what you're writing. Here's what <a href=\"%s\">everyone else is talking about</a> right now."),url . "_weblog/everyone.php") . "</p>";
$run_result .= "<p>&nbsp;</p>";

if ($news = get_record_sql("SELECT wp.* FROM ".$CFG->prefix."weblog_posts wp
                            JOIN ".$CFG->prefix."users u ON u.ident = wp.weblog
                            WHERE u.username = ? ORDER BY posted DESC",array('news'),false)) {
    $run_result .= "<div class=\"sitenews\">";
    $run_result .= "<h2>" . __gettext("Latest news") . "</h2>";
    $run_result .= "<p>" . run("weblogs:text:process",nl2br(stripslashes($news->body))) . "</p>";
    $run_result .= "</div>";
}
?>
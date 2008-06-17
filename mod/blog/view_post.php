<?php

//    ELGG weblog view page

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

run("profile:init");
run("friends:init");
run("weblogs:init");

$extensionContext = trim(optional_param('extension','weblog'));

define("context", $extensionContext);

global $profile_id;
global $individual;

$individual = 1;

$post = optional_param('post',0,PARAM_INT);
if (!empty($post)) {

    $where = run("users:access_level_sql_where",$_SESSION['userid']);

    if (!$post = get_record_select('weblog_posts','('.$where.') AND ident = '.$post)) {
        $post = new StdClass;
        $post->weblog = -1;
        $post->owner = -1;
        $post->title = __gettext("Access denied or post not found");
        $post->posted = time();
        $post->ident = -1;
        $post->body = __gettext("Either this blog post doesn't exist or you don't currently have access privileges to view it.");
    }

    global $page_owner;
    global $profile_id;
    $profile_id = $post->weblog;
    $page_owner = $post->weblog;

    $type = blog_get_extension($extensionContext, 'name');

    $title = run("profile:display:name") . " :: " . $type . " :: " . stripslashes($post->title);
    templates_page_setup();

    $time = gmstrftime("%B %d, %Y",$post->posted);
    $body = "<h2 class=\"weblog_dateheader\">$time</h2>\n";

    $body .= run("weblogs:posts:view:individual",$post);

    templates_page_output($title, $body);

}

?>
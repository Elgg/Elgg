<?php 

//let's include the basic stuff
require_once("includes.php");
global $CFG;

if (!$CFG->walledgarden) {

    $limit = optional_param('limit',3,PARAM_INT);
    
// query the database directly:
$posts = get_records_sql('SELECT wp.ident, u.name, u.username, wp.body, wp.title, wp.ident as postid, wp.posted
FROM '.$CFG->prefix.'weblog_posts wp
LEFT JOIN '.$CFG->prefix.'users u ON u.ident = wp.weblog
WHERE wp.access = "PUBLIC"
ORDER BY wp.posted DESC LIMIT ' . $limit);

foreach ($posts as $post) {
    
    $body = strip_tags($post->body);
    $body = preg_replace( "|\w{3,10}://[\w\.\-_]+(:\d+)?[^\s\"\'<>\(\)\{\}]*|", "", $body);
    $date = date("D, d M Y",$post->posted);
    $output .= <<< END
    
    <p><b><a href="{$CFG->wwwroot}{$post->username}/weblog/{$post->postid}.html">{$post->title}</a></b><br />{$date}</p>
    
END;

} 
}   


echo $output;
?>
<?php

//    ELGG search through everything page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

run("search:init");
run("search:all:tagtypes");

define("context","rss");

$tag = optional_param('tag');
$output = run("search:all:display:rss", $tag);

if ($output) {
    header("Pragma: public");
    header("Cache-Control: public"); 
    
    // no time data on this RSS, at least not without rewriting some function outputs
    
    $if_none_match = (isset($_SERVER['HTTP_IF_NONE_MATCH'])) ? preg_replace('/[^0-9a-f]/', '', $_SERVER['HTTP_IF_NONE_MATCH']) : false;
    
    $etag = md5($output);
    
    if ($if_none_match && $if_none_match == $etag) {
        header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
        exit;
    }
    
    header("Content-Length: " . strlen($output));
    header('ETag: "' . $etag . '"');
    
    header("Content-Type: text/xml; charset=utf-8");
    echo $output;
}
?>
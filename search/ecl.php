<?php

//    ELGG search through everything page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

run("search:init");
run("search:all:tagtypes");

define("context","rss");

header("Content-Type: text/xml; charset=utf-8");
$tag = optional_param('tag');
echo run("search:all:display:ecl", $tag);
        
?>
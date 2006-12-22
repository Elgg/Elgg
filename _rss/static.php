<?php

require_once(dirname(dirname(__FILE__)).'/includes.php');

$userref = optional_param('userref');
$username = optional_param('username');
$type = optional_param('type');

$file = $CFG->dataroot.'rss/'.$userref.'/'.$username.'/'.$type.'.xml'; 

if (!file_exists($file)) {
    @header('HTTP/1.0 404 Not Found');
    exit;
}
header("Pragma: public");
header("Cache-Control: public"); 

require_once($CFG->dirroot . 'lib/filelib.php');
spitfile_with_mtime_check($file, "text/xml; charset=utf-8");

?>
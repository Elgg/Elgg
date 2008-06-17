<?php

define('context', 'external');
require_once(dirname(dirname(__FILE__))."/../includes.php");

header("Content-type: text/css; charset=utf-8");

$template_id = optional_param('template');

// templates_draw() uses the global $template_name, not the 'template' array param, to choose a template
$template_name = $template_id;

$css = templates_draw(array(
                          'template' => $template_id,
                          'context' => 'css'
                          )
                    );

$etag = md5($css);

header('Cache-Control: public');
header('Pragma: ');

header('Expires: ' . gmdate("D, d M Y H:i:s", (time()+3600)) . " GMT");
header('ETag: ' . $etag . '');

// Send 304s where possible, rather than spitting out the file each time
if (array_key_exists('HTTP_IF_NONE_MATCH',$_SERVER)) {
    $if_none_match = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_NONE_MATCH']);
    if ($if_none_match == $etag) {
        header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
        exit;
    }
}

echo $css;

?>
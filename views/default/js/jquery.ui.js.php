<?php

$js = file_get_contents(elgg_get_root_path() . "vendor/bower-asset/jquery-ui/jquery-ui.min.js");

// https://github.com/Elgg/Elgg/issues/8418
// patch for having to loading this via SCRIPT (requires named define)
// TODO load this via require()
$js = str_replace('define.amd?define(["', 'define.amd?define("jquery-ui",["', $js);

echo $js;

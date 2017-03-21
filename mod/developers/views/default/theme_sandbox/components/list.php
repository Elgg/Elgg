<?php
/**
 * List of objects
 */

$ipsum = elgg_view('developers/ipsum');

$obj1 = new ThemeSandboxObject();
$obj1->title = "Object 1";
$obj1->description = $ipsum;

$obj2 = new ThemeSandboxObject();
$obj2->title = "Object 2";
$obj2->description = $ipsum;

$obj3 = new ThemeSandboxObject();
$obj3->title = "Object 3";
$obj3->description = $ipsum;

$obj4 = new ThemeSandboxObject();
$obj4->title = "Object 4";
$obj4->description = $ipsum;

echo elgg_view('page/components/list', ['items' => [$obj1, $obj2, $obj3, $obj4]]);

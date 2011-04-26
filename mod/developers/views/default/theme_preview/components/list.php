<?php

$obj1 = new ElggObject();
$obj1->title = "Object 1";
$obj1->description = $ipsum;

$obj2 = new ElggObject();
$obj2->title = "Object 2";
$obj2->description = $ipsum;

$obj3 = new ElggObject();
$obj3->title = "Object 3";
$obj3->description = $ipsum;

$obj4 = new ElggObject();
$obj4->title = "Object 4";
$obj4->description = $ipsum;

echo elgg_view('page/components/list', array('items' => array($obj1, $obj2, $obj3, $obj4)));

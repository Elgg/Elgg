<?php

require __DIR__ . '/vendor/autoload.php';

$compiler = new Elgg\Views\ViewCompiler();
$compiler->parse('output/friendlytime2', __DIR__ . "/views/default/output/friendlytime2.php");
$compiler->parse('elgg/init.js', __DIR__ . "/views/default/elgg/init.js.php");

header('Content-Type: text/plain');

echo $compiler->getCode();

var_export($compiler->getViewFunctions());

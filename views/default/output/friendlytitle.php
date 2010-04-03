<?php
/**
 * Friendly title
 * Makes a URL-friendly title.
 * 
 * @uses string $vars['title'] Title to create from.
 */


$title = $vars['title'];

$title = elgg_strtolower($title);
$title = preg_replace("/[^\w ]/","",$title);
$title = trim($title);
$title = str_replace(" ","-",$title);
$title = str_replace("--","-",$title);

echo $title;

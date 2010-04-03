<?php
/**
 * Friendly title
 * Makes a URL-friendly title.
 * 
 * @uses string $vars['title'] Title to create from.
 */


$title = $vars['title'];

//$title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
$title = preg_replace("/[^\w ]/","",$title);
$title = str_replace(" ","-",$title);
$title = str_replace("--","-",$title);
$title = trim($title);
$title = strtolower($title);

echo $title;

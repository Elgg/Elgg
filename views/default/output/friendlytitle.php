<?php
/**
 * Friendly title
 * Makes a URL-friendly title.
 *
 * @uses string $vars['title'] Title to create from.
 */


$title = elgg_extract('title', $vars);

//$title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
$title = preg_replace("/[^\w ]/", "", $title);
$title = str_replace(" ", "-", $title);
$title = str_replace("--", "-", $title);
$title = trim($title);
$title = elgg_strtolower($title);

echo $title;

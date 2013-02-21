<?php
/**
 * Friendly time
 * Translates an epoch time into a human-readable time.
 * 
 * @uses string $vars['time'] Unix-style epoch timestamp
 * @uses Boolean $vars['pubdate'] 
 */

$datetime = date(DATE_ISO8601, $vars['time']);
$pubdate = $vars['pubdate'] ? " pubdate" : "";

echo "<time class=\"elgg-friendlytime\"$pubdate>$datetime</time>";

<?php
/**
 * 
 */

$url = "{$CONFIG->url}pg/pages/owned/{$page_owner->username}";
$pages = elgg_echo('pages');

echo "<div class=\"profile\"><a href=\"$url\">$pages</a></div>";

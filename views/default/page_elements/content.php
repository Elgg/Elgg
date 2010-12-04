<?php
/**
 * Elgg content wrapper
 * This file holds the main content
 **/

$content = isset($vars['body']) ? $vars['body'] : '';

echo '<div class="elgg-body">';
echo $content;
echo '</div>';

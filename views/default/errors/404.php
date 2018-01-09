<?php
/**
 * Page not found error
 */

$error = elgg_extract('error', $vars, elgg_echo('error:404:content'));
echo elgg_view_message('error', $error);

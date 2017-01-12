<?php
/**
 * General error
 */

$error = elgg_extract('error', $vars, elgg_echo('error:default:content'));
echo elgg_view_message('error', $error);

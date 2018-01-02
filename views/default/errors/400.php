<?php
/**
 * Bad request error
 */

$error = elgg_extract('error', $vars, elgg_echo('error:400:content'));
echo elgg_view_message('error', $error);

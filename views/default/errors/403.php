<?php
/**
 * Forbidden error
 */

$error = elgg_extract('error', $vars, elgg_echo('error:403:content'));
echo elgg_view_message('error', $error);

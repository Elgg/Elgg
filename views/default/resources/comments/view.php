<?php

/**
 * Comment view resource
 * By default, redirects to the page of an item the comment was made on
 */

$guid = elgg_extract('guid', $vars);
$fallback_guid = elgg_extract('container_guid', $vars);

_elgg_comment_redirect($guid, $fallback_guid);

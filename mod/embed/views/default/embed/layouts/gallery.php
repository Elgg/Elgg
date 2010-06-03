<?php
/**
 * Embed - Gallery items
 *
 * @uses string $vars['content'] Pre-formatted content.
 *
 */

$content = elgg_get_array_value('content', $vars, '');

echo $content;
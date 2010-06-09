<?php
/**
 * Embed - List items
 *
 * @uses string $vars['content'] Pre-formatted content.
 *
 */
$active_section = elgg_get_array_value('section', $vars, array());

$content = "<div class='embed_modal_" . $active_section . "'>" . elgg_get_array_value('content', $vars, '') . "</div>";

echo $content;
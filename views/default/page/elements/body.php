<?php
/**
 * Elgg page body wrapper
 *
 * @uses $vars['body'] The HTML of the page body
 */

echo elgg_get_array_value('body', $vars, '');
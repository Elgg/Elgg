<?php
/**
 * Elgg display long text
 * Displays a large amount of text, with new lines converted to line breaks
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['text'] The text to display
 *
 */

global $CONFIG;

echo autop(parse_urls(filter_tags($vars['value'])));
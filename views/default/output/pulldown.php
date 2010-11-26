<?php
/**
 * Elgg pulldown display
 * Displays a value that was entered into the system via a pulldown
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['text'] The text to display
 *
 */

echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); //$vars['value'];
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
 * @deprecated 1.8
 */

echo htmlspecialchars($vars['value'], ENT_QUOTES, 'UTF-8');
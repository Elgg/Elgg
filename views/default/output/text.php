<?php
/**
 * Elgg text output
 * Displays some text that was input using a standard text field
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['text'] The text to display
 *
 */

echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); // $vars['value'];
<?php
/**
 * Elgg calendar output
 * Displays a calendar output field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 *
 */
elgg_deprecated_notice('output/calendar was deprecated in favor of output/date', 1.8);
echo elgg_view('output/date', $vars);
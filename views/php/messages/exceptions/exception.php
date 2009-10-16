<?php
/**
 * Elgg exception
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] An exception
 */

echo serialize($vars['object']);
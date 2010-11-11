<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 */

// @todo deprecated so remove in Elgg 2.0
if (isset($vars['area1'])) {
	echo $vars['area1'];
}

if (isset($vars['content'])) {
	echo $vars['content'];
}

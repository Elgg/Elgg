<?php
/**
 * Layout footer
 *
 * @uses $vars['footer']
 */

if (isset($vars['footer']) && $vars['footer']) {
	echo '<div class="elgg-foot clearfix">';
	echo $vars['footer'];
	echo '</div>';
}

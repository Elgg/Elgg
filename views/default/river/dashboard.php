<?php
/**
 * Elgg river for dashboard.
 *
 * @package Elgg
 * @author Curverider Ltd
 * @link http://elgg.com/
 */

/// Extract the river
$river = $vars['river'];
?>
<div id="river">
<?php
if (($river) && (count($river)>0)) {
	foreach ($river as $r) {
		echo $r;
	}
} else {
	echo elgg_echo('river:widget:noactivity');
}
?>
</div>
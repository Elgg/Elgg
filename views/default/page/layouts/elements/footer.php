<?php
/**
 * Layout footer
 *
 * @uses $vars['footer'] Footer view
 */

$footer = elgg_extract('footer', $vars);
if (!isset($footer) || empty($footer)) {
	return;
}
?>
<div class="elgg-foot elgg-layout-footer">
	<?= $footer ?>
</div>

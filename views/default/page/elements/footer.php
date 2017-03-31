<?php
/**
 * Elgg footer
 * The standard HTML footer that displays across the site
 */

$footer = elgg_view_menu('footer', ['sort_by' => 'priority', 'class' => 'elgg-menu-hz']);
if (!$footer) {
	return;
}
?>
<div class="elgg-inner container">
	<?= $footer ?>
</div>

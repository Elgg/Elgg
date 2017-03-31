<?php
/**
 * Elgg admin footer. Extend this view to add content to the admin footer
 */
$options = [
	'class' => 'elgg-menu-hz'
];

$footer = elgg_view_menu('admin_footer', $options);
?>
<div class="elgg-inner container">
	<?= $footer ?>
</div>
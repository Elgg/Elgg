<?php
/**
 * Elgg admin footer. Extend this view to add content to the admin footer
 */

$options = [
	'class' => 'elgg-menu-hz elgg-menu-footer',
];
echo elgg_view_menu('admin_footer', $options);

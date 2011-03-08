<?php
/**
 * Elgg admin footer. Extend this view to add content to the admin footer
 */

$options = array(
	'class' => 'elgg-menu-hz'
);
echo elgg_view_menu('admin_footer', $options);
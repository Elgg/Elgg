<?php
/**
 * Admin sidebar -- just outputs the page menus
 */

echo elgg_view_menu('page', [
	'show_section_headers' => true,
	'prepare_vertical' => true,
]);

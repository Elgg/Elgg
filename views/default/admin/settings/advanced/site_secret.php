<?php
/**
 * Elgg administration site secret settings
 *
 * @package Elgg
 * @subpackage Core
 */

echo elgg_view_form('admin/site/regenerate_secret', array(), array(
	'strength' => _elgg_get_site_secret_strength(),
));

<?php
/**
 * Settings Site Pages
 *
 * @package SitePages
 */

$page_type = 'front';
if (isset($vars['page'][2])) {
	$page_type = $vars['page'][2];
}

echo sitepages_get_edit_section_content($page_type, $vars['entity']);

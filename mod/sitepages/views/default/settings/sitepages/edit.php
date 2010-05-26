<?php
/**
 * Settings Site Pages
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$page_type = 'front';
if (isset($vars['page'][2])) {
	$page_type = $vars['page'][2];
}

echo sitepages_get_edit_section_content($page_type, $vars['entity']);

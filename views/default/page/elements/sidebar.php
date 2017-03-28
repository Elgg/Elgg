<?php
/**
 * Elgg sidebar contents
 *
 * @uses $vars['entity']           Entity of the current page
 * @uses $vars['owner_block']      Overwrite the default owner block
 * @uses $vars['page_menu']        Overwrite the default page menu
 * @uses $vars['page_menu_params'] Provide additional vars for default page menu
 * @uses $vars['sidebar']          Optional content that is displayed at the bottom of the sidebar
 */


$owner_block = elgg_view('page/elements/owner_block', $vars);
if ($owner_block) {
	echo elgg_format_element('div', ['class' => 'card'], $owner_block);
}


$page_menu = elgg_view('page/elements/menu', $vars);
if ($page_menu) {
	echo elgg_format_element('div', ['class' => 'card'], $page_menu);
}

echo elgg_extract('sidebar', $vars, '');

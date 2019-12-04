<?php
/**
 * Elgg sidebar contents
 *
 * @uses $vars['show_owner_block'] (bool) Display owner block
 * @uses $vars['owner_block_menu_params'] (array) Params to pass to owner block menu
 * @uses $vars['show_page_menu'] (bool) Display page menu (default: true)
 * @uses $vars['page_menu_params'] (array) Params to pass to page menu
 * @uses $vars['sidebar'] Optional content that is displayed at the bottom of sidebar
 */

echo elgg_view('page/elements/owner_block', $vars);
echo elgg_view('page/elements/page_menu', $vars);

// optional 'sidebar' parameter
echo elgg_extract('sidebar', $vars, '');

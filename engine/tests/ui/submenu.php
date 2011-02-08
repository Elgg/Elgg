<?php
/**
 * 1.8 submenu test.
 *
 * Submenu needs to be able to support being added out of order.
 * Children can be added before parents.
 * 	Children of parents never defined are never shown.
 *
 * Test against:
 * 	different contexts
 * 	different groups
 * 	old add_submenu_item() wrapper.
 *
 */

/*
 * Tests need to be ported to new menu system
 *

require_once('../../start.php');

$url = "engine/tests/ui/submenu.php";

$items = array(
	array(
		'text' => 'Upper level 1',
		'href' => "$url?upper_level_1",
		'id' => 'ul1'
	),
		array(
			'text' => 'CD (No link)',
			'parent_id' => 'cup',
			'id' => 'cd',
		),
			array(
				'text' => 'Sub CD',
				'href' => "$url?sub_cd",
				'parent_id' => 'cd'
			),
	array(
		'text' => 'Cup',
		'href' => "$url?cup",
		'id' => 'cup'
	),
		array(
			'text' => 'Phone',
			'href' => "$url?phone",
			'id' => 'phone',
			'parent_id' => 'cup'
		),
			array(
				'text' => 'Wallet',
				'href' => "$url?wallet",
				'id' => 'wallet',
				'parent_id' => 'phone'
			),
	array(
		'text' => 'Upper level',
		'href' => "$url?upper_level",
		'id' => 'ul'
	),
		array(
			'text' => 'Sub Upper level',
			'href' => "$url?sub_upper_level",
			'parent_id' => 'ul'
		),
	array(
		'text' => 'Root',
		'href' => $url,
	),

	array(
		'text' => 'I am an orphan',
		'href' => 'http://google.com',
		'parent_id' => 'missing_parent'
	),

	array(
		'text' => 'JS Test',
		'href' => 'http://elgg.org',
		'vars' => array('js' => 'onclick="alert(\'Link to \' + $(this).attr(\'href\') + \'!\'); return false;"')
	)
);

foreach ($items as $item) {
	elgg_add_submenu_item($item, 'main');
}

add_submenu_item('Old Onclick Test', 'http://elgg.com', NULL, TRUE);
add_submenu_item('Old Selected Test', 'http://elgg.com', NULL, '', TRUE);


elgg_add_submenu_item(array('text' => 'Not Main Test', 'href' => "$url?not_main_test"), 'not_main', 'new_menu');
elgg_add_submenu_item(array('text' => 'Not Main C Test', 'href' => "$url?not_main_c_test"), 'not_main', 'new_menu');

elgg_add_submenu_item(array('text' => 'All test', 'href' => "$url?all"), 'all');

//elgg_set_context('not_main');

$body = elgg_view_layout('one_sidebar', array('content' => 'Look right.'));
echo elgg_view_page('Submenu Test', $body);

*/

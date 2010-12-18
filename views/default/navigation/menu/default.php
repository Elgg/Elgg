<?php

foreach ($vars['menu'] as $section => $menu_items) {
	echo '<ul class="elgg-menu">';
	foreach ($menu_items as $menu_item) {
		echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
	}
	echo '</ul>';
}
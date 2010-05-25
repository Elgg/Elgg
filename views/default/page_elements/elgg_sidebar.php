<?php
/**
 * Elgg sidebar contents
 * 
 **/

echo elgg_view('page_elements/owner_block');

if (isset($vars['area2'])) {
	echo $vars['area2'];
}

// optional third parameter of elgg_view_layout
if (isset($vars['area3'])) {
	echo $vars['area3'];
}


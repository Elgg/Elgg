<?php
/**
* Inspect View
*
* Inspect global variables of Elgg
*/

elgg_load_js('jquery.jstree');
elgg_load_css('jquery.jstree');

echo elgg_view_form('developers/inspect', array('class' => 'developers-form-inspect'));

echo '<div id="developers-inspect-results"></div>';
echo elgg_view('graphics/ajax_loader', array('id' => 'developers-ajax-loader'));

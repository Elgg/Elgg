<?php
/**
 * Displays an autocomplete text input.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @todo This currently only works for ONE AUTOCOMPLETE TEXT FIELD on a page.
 *
 * @uses $vars['value']       Current value for the text input
 * @uses $vars['match_on']    Array | str What to match on. all|array(groups|users|friends)
 * @uses $vars['match_owner'] Bool.  Match only entities that are owned by logged in user.
 * @uses $vars['class']       Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-autocomplete {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-autocomplete";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

$params = array();
if (isset($vars['match_on'])) {
	$params['match_on'] = $vars['match_on'];
	unset($vars['match_on']);
}
if (isset($vars['match_owner'])) {
	$params['match_owner'] = $vars['match_owner'];
	unset($vars['match_owner']);
}
$vars['data-url'] = elgg_http_add_url_query_elements(elgg_get_site_url() . 'livesearch', $params);

elgg_load_js('elgg.autocomplete');
elgg_load_js('jquery.ui.autocomplete.html');

?>

<div class="elgg-autocomplete-content hidden">
	<ul class="elgg-menu elgg-menu-entity elgg-menu-autocomplete">
		<li>
<?php 
	echo elgg_view('output/confirmlink', array(
		'href' => '#',
		'class' => 'elgg-autocomplete-clear',
		'title' => elgg_echo('autocomplete:clear'),
		'text' => elgg_view_icon('delete'),
	));
?>
		</li>
	</ul>
</div>
<input type="text" <?php echo elgg_format_attributes($vars); ?> />

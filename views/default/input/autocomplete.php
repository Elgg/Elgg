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
$vars['type'] = 'text';
$vars['data-source'] = elgg_get_site_url() . 'livesearch?' . http_build_query($params);

echo elgg_format_element('input', $vars);

// inline script in case loaded via ajax
?>
<script>
require(['elgg/autocomplete'], function (autoc) {
	autoc.init();
});
</script>

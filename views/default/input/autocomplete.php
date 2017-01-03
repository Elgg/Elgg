<?php
/**
 * Displays an autocomplete text input.
 *
 * @uses $vars['value']       Current value for the text input
 * @uses $vars['match_on']    Array | str What to match on. all|array(groups|users|friends)
 * @uses $vars['match_owner'] Bool.  Match only entities that are owned by logged in user.
 * @uses $vars['class']       Additional CSS class
 * @uses $vars['handler']     (optional) search handler (defaults to 'livesearch')
 */

$defaults = [
	'value' => '',
	'disabled' => false,
];

$vars = array_merge($defaults, $vars);

$vars['class'] = elgg_extract_class($vars, 'elgg-input-autocomplete');

$handler = elgg_extract('handler', $vars, 'livesearch');

$params = [
	'match_on' => elgg_extract('match_on', $vars),
	'match_owner' => elgg_extract('match_owner', $vars),
];

unset($vars['handler'], $vars['match_on'], $vars['match_owner']);

$vars['data-source'] = elgg_normalize_url(elgg_http_add_url_query_elements($handler, $params));

if (!isset($vars['id'])) {
	$vars['id'] = "elgg-input-autocomplete-" . base_convert(mt_rand(), 10, 36);
}

$vars['type'] = 'text';

echo elgg_format_element('input', $vars);

?>
<script>
require(['input/autocomplete'], function (autocomplete) {
	autocomplete.init('#<?php echo $vars['id']; ?>');
});
</script>

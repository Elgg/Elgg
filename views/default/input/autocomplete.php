<?php
/**
 * Displays an autocomplete text input.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @todo This currently only works for ONE AUTOCOMPLETE TEXT FIELD on a page.
 *
 * @uses $vars['match_on'] Array | str What to match on. all|array(groups|users|friends|subtype)
 * @uses $vars['match_owner'] Bool.  Match only entities that are owned by logged in user.
 *
 */

$defaults = array(
	'class' => '',
	'value' => '',
);

$vars = array_merge($defaults, $vars);

$vars['class'] = trim("elgg-input-autocomplete {$vars['class']}");

$ac_url_params = http_build_query(array(
	'match_on' => $vars['match_on'],
	'match_owner' => $vars['match_owner'],
));

unset($vars['match_on']);
unset($vars['match_owner']);

elgg_load_js('elgg.autocomplete');

?>

<script type="text/javascript">
elgg.provide('elgg.autocomplete');
elgg.autocomplete.url = "<?php echo elgg_get_site_url() . 'livesearch?' . $ac_url_params; ?>";
</script> 
<input type="text" <?php echo elgg_format_attributes($vars); ?> />


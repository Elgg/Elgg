<?php
/**
 * Search box
 *
 * @uses $vars['value'] Current search query
 * @uses $vars['class'] Additional class
 */

if (array_key_exists('value', $vars)) {
	$value = $vars['value'];
} elseif ($value = get_input('q', get_input('tag', NULL))) {
	$value = $value;
} else {
	$value = elgg_echo('search');
}

$class = "elgg-search";
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// @todo - why the strip slashes?
$value = stripslashes($value);

// @todo - create function for sanitization of strings for display in 1.8
// encode <,>,&, quotes and characters above 127
$display_query = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
$display_query = htmlspecialchars($display_query, ENT_QUOTES, 'UTF-8', false);


?>

<form class="<?php echo $class; ?>" action="<?php echo elgg_get_site_url(); ?>search" method="get">
	<fieldset>
		<input type="text" class="search-input" size="21" name="q" value="<?php echo elgg_echo('search'); ?>" onblur="if (this.value=='') { this.value='<?php echo elgg_echo('search'); ?>' }" onfocus="if (this.value=='<?php echo elgg_echo('search'); ?>') { this.value='' };" />
		<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search-submit-button" />
	</fieldset>
</form>
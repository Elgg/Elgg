<?php

if (array_key_exists('value', $vars)) {
	$value = $vars['value'];
} elseif ($value = get_input('q', get_input('tag', NULL))) {
	$value = $value;
} else {
	$value = elgg_echo('search');
}

// @todo - why the strip slashes?
$value = stripslashes($value);

// @todo - create function for sanitization of strings for display in 1.8
// encode <,>,&, quotes and characters above 127
if (function_exists('mb_convert_encoding')) {
	$display_query = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
	$display_query = htmlspecialchars($display_query, ENT_QUOTES, 'UTF-8', false);
} else {
	// we list mb_string as a requirement, why do we check if the function exists?
	$display_query = htmlentities($display_query, ENT_QUOTES, 'UTF-8', false);
}

?>

<form id="searchform" action="<?php echo $vars['url']; ?>pg/search/" method="get">
	<input type="text" size="21" name="q" value="<?php echo $display_query; ?>" onclick="if (this.value=='<?php echo elgg_echo('search'); ?>') { this.value='' }" class="search_input" />
	<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search_submit_button" />
</form>

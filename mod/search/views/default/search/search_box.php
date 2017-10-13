<?php
/**
 * Search box
 *
 * @uses $vars['value'] Current search query
 * @uses $vars['class'] Additional class
 */

$value = elgg_extract('value', $vars, get_input('q', get_input('tag')));

$search_attrs = elgg_format_attributes([
	'type' => 'text',
	'class' => 'search-input',
	'size' => '21',
	'name' => 'q',
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
	'required' => true,
	'value' => _elgg_get_display_query($value),
	'placeholder' => elgg_echo('search'),
]);

$form_attrs = elgg_format_attributes([
	'class' => elgg_extract_class($vars, ['elgg-search']),
	'action' => elgg_normalize_site_url('search'),
	'method' => 'GET',
]);

?>

<form <?php echo $form_attrs; ?>>
	<fieldset>
		<input <?php echo $search_attrs; ?> />
		<input type="hidden" name="search_type" value="all" />
		<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search-submit-button" />
	</fieldset>
</form>

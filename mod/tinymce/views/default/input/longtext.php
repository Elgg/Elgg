<?php
/**
 * Long text input with the tinymce text editor
 *
 * @package ElggTinyMCE
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js']    Any Javascript to enter into the input tag
 * @uses $vars['name']  The name of the input field
 * @uses $vars['id']    The id of the input field
 *
 */

elgg_register_js('mod/tinymce/vendor/tinymce/jscripts/tiny_mce/tiny_mce.js', 'tinymce');

// make sure the init script is only loaded once
elgg_unextend_view('footer/analytics', 'tinymce/init');
elgg_extend_view('footer/analytics', 'tinymce/init');

if (!isset($vars['id'])) {
	$vars['id'] = 'tinymce-longtext-' . rand();
}

?>

<a class="elgg-longtext-control tinymce-toggle-editor small" href="javascript:toggleEditor('<?php echo $vars['id']; ?>');">
	<?php echo elgg_echo('tinymce:remove'); ?>
</a>

<textarea class="elgg-input-textarea mceEditor" name="<?php echo $vars['name']; ?>" id="<?php echo $vars['id']; ?>" <?php echo $vars['js']; ?>>
<?php echo htmlspecialchars($vars['value'], null, 'UTF-8'); ?>
</textarea>

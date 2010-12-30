<?php
/**
 * Elgg long text input with the tinymce text editor intacts
 * Displays a long text input field
 *
 * @package ElggTinyMCE
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['internalid'] The id of the input field
 *
 */

global $tinymce_js_loaded;

if (!isset($vars['value']) || $vars['value'] === FALSE) {
	$vars['value'] = elgg_get_sticky_value($vars['internalname']);
}

$input = rand(0,9999);

if (!isset($tinymce_js_loaded)) {
	$tinymce_js_loaded = false;
}

if (!$tinymce_js_loaded) {

?>
<!-- include tinymce -->
<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/tinymce/vendor/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<!-- intialise tinymce, you can find other configurations here http://wiki.moxiecode.com/examples/tinymce/installation_example_01.php -->
<script type="text/javascript">
tinyMCE.init({
	mode : "specific_textareas",
	editor_selector : "mceEditor",
	theme : "advanced",
	plugins : "spellchecker,autosave,fullscreen,paste",
	relative_urls : false,
	remove_script_host : false,
	document_base_url : "<?php echo elgg_get_site_url(); ?>",
	theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,bullist,numlist,undo,redo,link,unlink,image,blockquote,code,pastetext,pasteword,more,fullscreen",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	theme_advanced_path : true,
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	setup : function(ed) {
		//show the number of words
		ed.onLoadContent.add(function(ed, o) {
			var strip = (tinyMCE.activeEditor.getContent()).replace(/(&lt;([^&gt;]+)&gt;)/ig,"");
			var text = " <?php echo elgg_echo('tinymce:word_count'); ?>" + strip.split(' ').length + ' ';
			tinymce.DOM.setHTML(tinymce.DOM.get(tinyMCE.activeEditor.id + '_path_row'), text);
		});

		ed.onKeyUp.add(function(ed, e) {
			var strip = (tinyMCE.activeEditor.getContent()).replace(/(&lt;([^&gt;]+)&gt;)/ig,"");
			var text = " <?php echo elgg_echo('tinymce:word_count'); ?>" + strip.split(' ').length + ' ';
			tinymce.DOM.setHTML(tinymce.DOM.get(tinyMCE.activeEditor.id + '_path_row'), text);
		});
	},
	content_css: '<?php echo elgg_get_site_url(); ?>mod/tinymce/tinymce_content.css'
});

function toggleEditor(id) {
	if (!tinyMCE.get(id)) {
		tinyMCE.execCommand('mceAddControl', false, id);
		<?php $toggleEditor_linktext = elgg_echo('tinymce:remove'); ?>
		$("a.toggle_editor").html('<?php echo $toggleEditor_linktext ?>');
	}
	else {
		tinyMCE.execCommand('mceRemoveControl', false, id);
		<?php $toggleEditor_linktext = elgg_echo('tinymce:add'); ?>
		$("a.toggle_editor").html('<?php echo $toggleEditor_linktext ?>');
	}
}
</script>
<?php

	$tinymce_js_loaded = true;
}

?>

<a class="longtext-control toggle_editor small link" href="javascript:toggleEditor('<?php echo $vars['internalname']; ?>');"><?php echo elgg_echo('tinymce:remove'); ?></a>
<!-- show the textarea -->
<textarea class="elgg-input-textarea mceEditor" name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> <?php echo $vars['js']; ?>><?php echo htmlentities($vars['value'], null, 'UTF-8'); ?></textarea>

<script type="text/javascript">
	$(document).ready(function() {
		$('textarea').parents('form').submit(function() {
			tinyMCE.triggerSave();
		});
	});
</script>

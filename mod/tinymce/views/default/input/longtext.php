<?php

	/**
	 * Elgg long text input with the tinymce text editor intacts
	 * Displays a long text input field
	 *
	 * @package ElggTinyMCE
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 *
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 *
	 */

	global $tinymce_js_loaded;

	if (!isset($vars['value']) || $vars['value'] === FALSE) {
		$vars['value'] = elgg_get_sticky_value($vars['internalname']);
	}

	$input = rand(0,9999);

	if (!isset($tinymce_js_loaded)) $tinymce_js_loaded = false;

	if (!$tinymce_js_loaded) {

?>
<!-- include tinymce -->
<script language="javascript" type="text/javascript" src="<?php echo $vars['url']; ?>mod/tinymce/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<!-- intialise tinymce, you can find other configurations here http://wiki.moxiecode.com/examples/tinymce/installation_example_01.php -->
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "specific_textareas",
	editor_selector : "mceEditor",
	theme : "advanced",
	plugins : "safari,spellchecker,autosave,fullscreen,preview,paste",
	relative_urls : false,
	theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,bullist,numlist,undo,redo,link,unlink,image,blockquote,code,pastetext,pasteword,more,fullscreen,",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	theme_advanced_path : true,
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	setup : function(ed) {
		// Add a custom button
		//ed.addButton('more', {
		//    title : 'more',
		//    image : '<?php echo $vars['url']; ?>mod/tinymce/graphics/more.gif',
		//    onclick : function() {
		//        ed.selection.setContent('{{more}}');
		//    }
		//});

		//show the number of words
		ed.onLoadContent.add(function(ed, o) {
		var strip = (tinyMCE.activeEditor.getContent()).replace(/(&lt;([^&gt;]+)&gt;)/ig,"");
		var text = " Word count: " + strip.split(' ').length;
		tinymce.DOM.setHTML(tinymce.DOM.get(tinyMCE.activeEditor.id + '_path_row'), text);
		});

		ed.onKeyUp.add(function(ed, e) {
		var strip = (tinyMCE.activeEditor.getContent()).replace(/(&lt;([^&gt;]+)&gt;)/ig,"");
		var text = " Word count: " + strip.split(' ').length;
		tinymce.DOM.setHTML(tinymce.DOM.get(tinyMCE.activeEditor.id + '_path_row'), text);
		});
	}
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

<a class="longtext_control toggle_editor small link" href="javascript:toggleEditor('<?php echo $vars['internalname']; ?>');"><?php echo elgg_echo('tinymce:remove'); ?></a>
<!-- show the textarea -->
<textarea class="input_textarea mceEditor" name="<?php echo $vars['internalname']; ?>" <?php echo $vars['js']; ?>><?php echo htmlentities($vars['value'], null, 'UTF-8'); ?></textarea>

<script type="text/javascript">
	$(document).ready(function() {
		$('textarea').parents('form').submit(function() {
			tinyMCE.triggerSave();
		});
	});
</script>

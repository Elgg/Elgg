<script language="javascript" type="text/javascript" src="<?php echo $vars['url']?>mod/profile/vendor/jquery.jeditable.mini.js"></script>
<script language="javascript" type="text/javascript">
var reorderURL = '<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/profile/editdefault/reorder', FALSE); ?>';
function sortCallback(event, ui) {
	var orderArr = $('#sortableList').sortable('toArray');
	var orderStr = orderArr.join(',');
	console.log(orderArr);
	console.log(orderStr);
	jQuery.post(reorderURL, {'fieldorder': orderStr});
}

$(document).ready(function() {
	$('#sortableList').sortable({
		items: 'li',
		handle: '.handle',
		stop: sortCallback
	});
});

</script>
<script language="javascript" type="text/javascript" src="<?php echo $vars['url']; ?>mod/multiadmin/vendors/js/jquery.jeditable.js" ></script>

<div id="list">
	<ul id="sortableList">
<?php

	$save = elgg_echo('save');
	$cancel = elgg_echo('cancel');

	foreach($vars['items'] as $item) {
		$url = elgg_add_action_tokens_to_url("{$vars['url']}action/profile/editdefault/editfield");
		echo <<< END

<script language="javascript" type="text/javascript">

	$(function() {
		$(".{$item->shortname}_editable").editable("$url", {
			type   : 'text',
			submitdata: { _method: "post", 'field': '{$item->shortname}' },
			onblur: 'submit',
			width:'300px',
			height:'none',
			style:'display:inline;',
			tooltip:'Click to edit label'
		});
	});

</script>

END;

		echo elgg_view("profile/", array('value' => $item->translation));

		//$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
		$url = elgg_add_action_tokens_to_url("{$vars['url']}action/profile/editdefault/delete?id={$item->shortname}");
		echo "<li id=\"{$item->shortname}\"><div class=\"delete_note\" style=\"float:right\"><a href=\"$url\">" . elgg_echo('delete') . "</a></div>";
		echo "<img width='16' height='16' class='handle' alt='move' title='Drag here to reorder this item' src='{$vars['url']}mod/profile/graphics/drag_handle.png'/>";
		echo "<b class=\"profile_field_editable\"><span class=\"{$item->shortname}_editable\">$item->translation</span></b>:  [".elgg_echo($item->type)."]";
		echo "</li>";

	}

?>
	</ul>
</div>
<div id="tempList"></div>

<input name="sortableListOrder" type="hidden" id="sortableListOrder" value="<?php echo $vars['fieldlist']; ?>" />

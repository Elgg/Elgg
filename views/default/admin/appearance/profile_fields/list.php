<?php


// List form elements
$n = 0;
$loaded_defaults = array();
$items = array();
if ($fieldlist = get_plugin_setting('user_defined_fields', 'profile')) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach($fieldlistarray as $listitem) {
		if ($translation = get_plugin_setting("admin_defined_profile_{$listitem}", 'profile')) {
			$item = new stdClass;
			$item->translation = $translation;
			$item->shortname = $listitem;
			$item->name = "admin_defined_profile_{$listitem}";
			$item->type = get_plugin_setting("admin_defined_profile_type_{$listitem}", 'profile');
			$items[] = $item;
		}
	}
}
?>

<script language="javascript" type="text/javascript" src="<?php echo elgg_get_site_url()?>vendors/jquery/jquery.jeditable.mini.js"></script>
<script language="javascript" type="text/javascript">
var reorderURL = '<?php echo elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/profile/fields/reorder', FALSE); ?>';
function sortCallback(event, ui) {
	var orderArr = $('#sortable_profile_fields').sortable('toArray');
	var orderStr = orderArr.join(',');
	jQuery.post(reorderURL, {'fieldorder': orderStr});
}

$(document).ready(function() {
	$('#sortable_profile_fields').sortable({
		items: 'li',
		handle: '.handle',
		stop: sortCallback
	});
});

</script>

<div id="list">
	<ul id="sortable_profile_fields">
<?php

	$save = elgg_echo('save');
	$cancel = elgg_echo('cancel');
	$edit_url = elgg_add_action_tokens_to_url(elgg_get_site_url()."action/profile/editdefault/editfield", FALSE);

	foreach($items as $item) {
		echo <<< END

<script language="javascript" type="text/javascript">

	$(function() {
		$(".{$item->shortname}_editable").editable("$edit_url ", {
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
		$url = elgg_add_action_tokens_to_url(elgg_get_site_url()."action/profile/fields/delete?id={$item->shortname}");
		echo "<li id=\"{$item->shortname}\"><div class='delete-button'><a href=\"$url\">" . elgg_echo('delete') . "</a></div>";
		echo "<img width='16' height='16' class='handle' alt='move' title='Drag here to reorder this item' src='".elgg_get_site_url()."mod/profile/graphics/drag-handle.png'/>";
		echo "<b class=\"profile_field_editable\"><span class=\"{$item->shortname}_editable\">$item->translation</span></b>:  [".elgg_echo($item->type)."]";
		echo "</li>";

	}

?>
	</ul>
</div>
<div id="tempList"></div>

<input name="sortableListOrder" type="hidden" id="sortableListOrder" value="<?php echo $fieldlist; ?>" />


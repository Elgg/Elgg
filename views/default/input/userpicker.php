<?php
/**
 * User Picker.  Sends an array of user guids.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['internalname'] The name of the input field
 *
 *
 * pops up defaulted to lazy load friends lists in paginated alphabetical order.
 * upon
 *
 * As users are checked they move down to a "users" box.
 * When this happens, a hidden input is created also.
 * 	{$internalnal}[] with the value th GUID.
 *
 */

global $user_picker_js_sent;

if (!$user_picker_js_sent) {
?>
<!-- User picker JS -->
<script language="javascript" type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery/jquery.autocomplete.min.js"></script>
<script type="text/javascript">
// set up a few required variables
userPickerURL = '<?php echo $vars['url'] ?>pg/livesearch';

function userPickerBindEvents() {
	// binding autocomplete.
	// doing this as an each so we can past this to functions.
	$('.user_picker .search').each(function (i, e) {
		userPickerBindAutocomplete(e);
	});

	// changing friends vs all users.
	$('.user_picker .all_users').click(function() {
		// update the extra params for the autocomplete.
		var e = $(this).parents('.user_picker').find('.search');
		var params = userPickerGetSearchParams(e);
		e.setOptions({extraParams: params});
		e.flushCache();
	});

	// hitting enter on the text field
//	$('.user_picker .search').bind($.browser.opera ? "keypress" : "keydown", function(event) {
//		if(event.keyCode == 13) {
////			console.log($(this).val());
//			userPickerAddUser(this);
//		}
//	});
}

function userPickerBindAutocomplete(e) {
	var params = userPickerGetSearchParams(e);

	$(e).autocomplete(userPickerURL, {
		extraParams: params,
		max: 25,
		minChars: 2,
		matchContains: false,
		autoFill: false,
		formatItem: userPickerFormatItem,
		formatResult: function (row, i, max) {
			eval("var info = " + row + ";");
			// returning the just name
			return info.name;
		}
	});

	// add users when a result is picked.
	$(e).result(userPickerAddUser);
}

function userPickerFormatItem(row, i, max, term) {
	eval("var info = " + row + ";");
	var r = '';
	var name = info.name.replace(new RegExp("(" + term + ")", "gi"), "<span class=\"user_picker_highlight\">$1</b>");
	var desc = info.desc.replace(new RegExp("(" + term + ")", "gi"), "<span class=\"user_picker_highlight\">$1</b>");

	switch (info.type) {
		case 'user':
		case 'group':
			r = info.icon + name + ' - ' + desc;
			break;

		default:
			r = name + ' - ' + desc;
			break;
	}
	return r;
	//return r.replace(new RegExp("(" + term + ")", "gi"), "<span class=\"user_picker_highlight\">$1</b>");
}

function userPickerAddUser(event, data, formatted) {
	eval("var info = " + data + ";");
	var picker = $(this).parent('.user_picker');
	var users = picker.find('.users');
	var internalName = picker.find('input.internalname').val();
	// not sure why formatted isn't.
	var formatted = userPickerFormatItem(data);

	// add guid as hidden input and to list.
	var li = formatted + ' <a class="delete_collection" onclick="userPickerRemoveUser(this, ' + info.guid + ')"><strong>X</strong></a>'
	+ '<input type="hidden" name="' + internalName + '[]" value="' + info.guid + '" />';
	$('<li class="user_picker_entry">').html(li).appendTo(users);

	$(this).val('');
}

function userPickerRemoveUser(link, guid) {
	$(link).parent('.user_picker_entry').remove();
}

function userPickerGetSearchParams(e) {
	if ($(e).parent().find('.all_users').attr('checked')) {
		return {'match_on[]': 'friends'};
	} else {
		return {'match_on[]': 'users'};
	}
}

$(document).ready(function() {
	userPickerBindEvents();
});
</script>
<?php
	$user_picker_js_sent = true;
}

?>
<div class="user_picker">
	<input class="internalname" type="hidden" name="internalname" value="<?php echo $vars['internalname']; ?>" />
	<input class="search" type="text" name="user_search" size="30"/>
	<span class="controls">
		<label><input class="all_users" type="checkbox" name="match_on" value="true" /><?php echo elgg_echo('userpicker:only_friends'); ?></label>
	</span>
	<div class="results">
		<!-- This space will be filled with users, checkboxes and magic. -->
	</div>
	<ul class="users"></ul>
</div>

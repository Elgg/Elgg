<?php
/**
 * Configs granular access
 *
 * @package ECML
 */

$views = $vars['config']->ecml_parse_views;
$keywords = $vars['config']->ecml_keywords;
$perms = $vars['config']->ecml_permissions;

ksort($views);
ksort($keywords);

echo '<p class="margin_top">' . elgg_echo('ecml:admin:instruction') . '</p>';

// yes I'm using a table because this is table.
$form_body = <<<___END
<table class="ecml_admin_table">
	<tr>
		<th>&nbsp</th>
___END;

foreach ($views as $view => $view_desc) {
	$form_body .= "<th><acronym class=\"ecml_view ecml_check_all\" title=\"$view\">$view_desc</acronym></th>";
	$n++;
}
$form_body .= '</tr>';

$odd = 'odd';
foreach ($keywords as $keyword => $keyword_info) {
	$keyword_desc = $keyword_info['description'];
	if (isset($keyword_info['restricted'])) {
		$restricted = elgg_echo('ecml:admin:restricted');
		$form_body .= "
		<tr class=\"ecml_row_$odd\">
			<td class=\"ecml_keyword_desc\"><acronym class=\"ecml_keyword ecml_restricted\" title=\"$keyword_desc\">$keyword ($restricted)</acronym></td>
		";
	} else {
		$form_body .= "
		<tr class=\"ecml_row_$odd\">
			<td class=\"ecml_keyword_desc\"><acronym class=\"ecml_keyword ecml_check_all\" title=\"$keyword_desc\">$keyword</acronym></td>
		";
	}
	foreach ($views as $view => $view_info) {
		// if this is restricted and we're not on the specified view don't allow changes
		// since we don't save this, no need to pass a name
		if (isset($keyword_info['restricted'])) {
			$checked = (in_array($view, $keyword_info['restricted'])) ? 'checked="checked"' : '';
			$form_body .= "<td><input type=\"checkbox\" $checked name=\"whitelist[$view][]\" value=\"$keyword\" disabled=\"disabled\"/></td>";
		} else {
			$checked = (!in_array($keyword, $perms[$view])) ? 'checked="checked"' : '';

			// ooook. input/checkboxes isn't overly useful.
			// do it ourself.
			$form_body .= "<td><input type=\"checkbox\" name=\"whitelist[$view][]\" value=\"$keyword\" $checked /></td>";
		}
	}
	$form_body .= '</tr>';

	$odd = ($odd == 'odd') ? 'even' : 'odd';
}

$form_body .= '</table>';

echo $form_body;

?>
<script type="text/javascript">

$(document).ready(function() {
	// append check all link
	$('.ecml_check_all').before('<input type="checkbox" checked="checked" class="check_all">');

	// determin initial state of checkall checkbox.
	$('.ecml_check_all').each(function() {
		var keyword = $(this).hasClass('ecml_keyword');
		var checkbox = $(this).parent().find('input[type=checkbox]');
		var checked;

		// no keywords checked, checkall unchecked
		// any keyword checked, checkall unchecked
		// all keywords checked, checkall checked

		// if keyword, check the TR
		if (keyword) {
			checked = true;
			$(this).parent().parent().find('input').each(function() {
				if (!$(this).hasClass('check_all') && !$(this).attr('disabled')) {
					checked = (checked && $(this).attr('checked'));
					// can't break...
				}
			});
			checkbox.attr('checked', checked);
		} else {
			checked = true;
			var rowIndex = $(this).parent().parent().children().index($(this).parent());

			$('.ecml_admin_table > tbody > tr td:nth-child(' + (rowIndex + 1) + ') input[type=checkbox]').each(function() {
				if (!$(this).hasClass('check_all') && !$(this).attr('disabled')) {
					checked = (checked && $(this).attr('checked'));
					// can't break...
				}
			});
			checkbox.attr('checked', checked);
		}
	});

	// handle checkall boxes
	$('input.check_all').click(function() {
		// yoinked from
		// http://stackoverflow.com/questions/788225/table-row-and-column-number-in-jquery
		var rowIndex = $(this).parent().parent().children().index($(this).parent());
		var check = $(this).attr('checked');

		// clicked on a keyword on the left, check all boxes in the tr
		if (rowIndex == 0) {
			$(this).parent().parent().find('input').each(function() {
				if (!$(this).attr('disabled')) {
					$(this).attr('checked', check);
				}
			});
		} else {
			boxes = $('.ecml_admin_table > tbody > tr td:nth-child(' + (rowIndex + 1) + ') input[type=checkbox]');
			boxes.each(function() {
				if (!$(this).attr('disabled')) {
					$(this).attr('checked', check);
				}
			});
		}
	});
});
</script>

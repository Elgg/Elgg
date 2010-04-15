<?php
/**
 * Configs granular access
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$views = $vars['config']->ecml_parse_views;
$keywords = $vars['config']->ecml_keywords;
$perms = $vars['config']->ecml_permissions;

ksort($views);
ksort($keywords);

echo elgg_view_title(elgg_echo('ecml:admin:admin'));
echo '<p class="margin_top">' . elgg_echo('ecml:admin:instruction') . '</p>';

// yes I'm using a table because this is table.
$form_body = <<<___END
<table class="ecml_admin_table">
	<tr>
		<th>&nbsp</th>
___END;

foreach ($keywords as $keyword => $info) {
	$desc = $info['description'];

	$form_body .= "<th><acronym title=\"$desc\">$keyword</acronym></th>";
}
$form_body .= '</tr>';

$odd = 'odd';
foreach ($views as $view => $desc) {
	$form_body .= "
	<tr class=\"ecml_row_$odd\">
		<td class=\"ecml_view_desc\">$desc</td>
";
	foreach ($keywords as $keyword => $info) {
		// if this is restricted and we're not on the specified view don't allow changes
		// since we don't save this, no need to pass a name
		if (isset($info['restricted']) && !in_array($view, $info['restricted'])) {
			$form_body .= "<td><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\"/></td>";
		} else {
			$checked = (in_array($keyword, $perms[$view])) ? 'checked="checked"' : '';

			// ooook. input/checkboxes isn't overly useful.
			// do it ourself.
			$form_body .= "<td><input type=\"checkbox\" name=\"perms[$view][]\" value=\"$keyword\" $checked /></td>";
		}
	}
	$form_body .= '</tr>';

	$odd = ($odd == 'odd') ? 'even' : 'odd';
}

$form_body .= '</table>';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('submit')));
$form_body .= elgg_view('input/reset', array('value' => elgg_echo('reset'), 'class' => 'cancel_button'));

echo elgg_view('input/form', array(
	'body' => $form_body,
	'action' => $vars['url'] . 'action/ecml/save_permissions'
));

//foreach ($views as $view => $desc) {
//	echo elgg_view_title($desc);
//	echo '<ul>';
//	foreach ($keywords as $keyword => $info) {
//		$description = $info['description'];
//
//		echo "<li>$keyword</li>";
//	}
//	echo '</ul>';
//
//echo <<<___END
//	<br />
//	</li>
//
//___END;
//}
//
//echo '</ul>';
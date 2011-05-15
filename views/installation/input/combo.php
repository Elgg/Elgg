<?php
/**
 * Combination of text box and check box. When the checkbox is checked, the
 * text field is cleared and disabled.
 *
 */

$label = elgg_echo('install:label:combo:' . $vars['name']);

$vars['class'] = "elgg-combo-text";
echo elgg_view('input/text', $vars);

$vars['class'] = "elgg-combo-checkbox";
$vars['value'] = "{$vars['name']}-checkbox";
echo elgg_view('input/checkbox', $vars);

echo "<label class=\"elgg-combo-label\">$label</label>";

echo '<div class="clearfloat"></div>';
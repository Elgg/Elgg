<?php
/**
 * Displays formatted telephone
 *
 * @uses $vars['value'] Telephone in text
 */

$value = elgg_extract('value', $vars);

echo elgg_view('output/url', [
  'text' => $value,
  'href' => "tel:{$value}",
]);

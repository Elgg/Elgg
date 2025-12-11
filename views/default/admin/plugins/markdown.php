<?php

use Michelf\MarkdownExtra;

elgg_require_css('admin/plugins/markdown');

$value = (string) elgg_extract('value', $vars);

echo elgg_format_element('div', ['class' => 'elgg-markdown'], MarkdownExtra::defaultTransform($value));

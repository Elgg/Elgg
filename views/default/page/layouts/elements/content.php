<?php
/**
 * Layout content
 *
 * @uses $vars['content'] Content
 */

echo elgg_format_element('div', ['class' => 'elgg-layout-content'], (string) elgg_extract('content', $vars));

<?php
/**
 * Create a submit input button
 *
 * @todo ... huh?
 * Use this view for forms rather than creating a submit/reset button tag in the wild as it provides
 * extra security which help prevent CSRF attacks.
 *
 * @package Elgg
 * @subpackage Core
 */

$vars['type'] = 'submit';

echo elgg_view('input/button', $vars);
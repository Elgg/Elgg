<?php

$vars['pagination'] = false;

// output a regular HTML table...
elgg_set_viewtype('default');
echo elgg_view('page/components/table', $vars);
elgg_set_viewtype('rss');

<?php
elgg_push_breadcrumb('First', "#");
elgg_push_breadcrumb('Second', "#");
elgg_push_breadcrumb('Third');

echo elgg_view('navigation/breadcrumbs', ['class' => 'mts']);

elgg_pop_breadcrumb();
elgg_pop_breadcrumb();
elgg_pop_breadcrumb();

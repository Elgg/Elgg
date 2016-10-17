<?php

$content = get_input('content', 'No data was sent by the tab with the request');
echo elgg_view_module('featured', 'Ajax Content', $content);

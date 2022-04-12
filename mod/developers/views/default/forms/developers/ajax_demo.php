<?php
// This will be fetched via ajax by the developers/ajax_example AMD module

if (!isset($vars['entity']) || !$vars['entity'] instanceof ElggSite) {
	elgg_register_error_message('$vars not set by ajax.view()');
}

echo "form demo";

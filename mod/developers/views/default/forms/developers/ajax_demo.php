<?php
// This will be fetched via ajax by the developers/ajax_example AMD module

if (!isset($vars['entity']) || !$vars['entity'] instanceof ElggSite) {
	register_error('$vars not set by ajax.view()');
}

echo "form demo";

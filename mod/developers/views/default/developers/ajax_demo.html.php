<?php

if (!isset($vars['entity']) || !$vars['entity'] instanceof ElggSite) {
	elgg_register_error_message('$vars not set by ajax.form()');
}

?><p>view demo</p>

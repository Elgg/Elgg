<?php

if (!isset($vars['entity']) || !$vars['entity'] instanceof ElggSite) {
	register_error('$vars not set by ajax.form()');
}

?><p>view demo</p>

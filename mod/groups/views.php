<?php

return call_user_func(function() {
	foreach (['large', 'medium', 'small', 'tiny'] as $size) {
		$ret['default']["groups/default$size.gif"] = __DIR__ . "/graphics/default$size.gif";
	}

	return $ret;
});

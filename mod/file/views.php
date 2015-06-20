<?php

return call_user_func(function() {
	$dir = dir(__DIR__ . '/graphics/icons');
	$ret = [];

	while (false !== ($entry = $dir->read())) {
		if ($entry[0] === '.') {
			continue;
		}

		$ret['default']["file/icons/$entry"] = "{$dir->path}/$entry";
	}

	return $ret;
});
